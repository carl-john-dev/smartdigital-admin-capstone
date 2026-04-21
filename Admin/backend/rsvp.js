/**
 * Dahil iba yung structure, di to pwede ilagay sa backend.js
 * -Arjon
 */
import { db } from '../Firebase/firebase_conn.js';
import { collection, query, doc, getDocs, getDoc, addDoc, updateDoc, deleteDoc, serverTimestamp, orderBy } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-firestore.js";

// Global variables
let selectedEventId = null;
let upcomingEvents = [];
let currentRSVPs = [];
let currentWalkins = [];

// Booth data storage
let currentBoothData = {
    totalBooths: 0,
    reservedBooths: 0
};

// Three Dots Menu Functions
function exportRSVPs() {
    showNotification('Export feature available in the export buttons below', 'info');
};

function printRSVPList() {
    window.print();
};

function refreshRSVPs() {
    location.reload();
};

function showRSVPHelp() {
    alert(`
RSVP Tracker Help:
- Add RSVPs using the "Add RSVP" button
- Add Walk-ins using the "Add Walk-in" button
- Aggregate attendance automatically combines RSVPs + Walk-ins
- Attendance Hit Rate shows RSVP to attendance conversion
- Booth availability shows available booths per event
- Click on event date/venue to edit
- Manage booths using the "Manage Booths" button
- Export data as CSV or PDF
- Event conflict detection - cannot create two events on the same day
- Apply for booth when adding RSVP
- Mobile app RSVPs are shown with a 📱 badge
    `);
};

// Dark Mode Toggle
const darkModeToggle = document.getElementById('darkModeToggle');
const darkModeIcon = document.getElementById('darkModeIcon');
const body = document.body;

const isDarkMode = localStorage.getItem('darkMode') === 'enabled';
if (isDarkMode) {
    body.classList.add('dark-mode');
    darkModeIcon.classList.remove('fa-moon');
    darkModeIcon.classList.add('fa-sun');
}

darkModeToggle.addEventListener('click', function() {
    body.classList.toggle('dark-mode');
    if (body.classList.contains('dark-mode')) {
        darkModeIcon.classList.remove('fa-moon');
        darkModeIcon.classList.add('fa-sun');
        localStorage.setItem('darkMode', 'enabled');
    } else {
        darkModeIcon.classList.remove('fa-sun');
        darkModeIcon.classList.add('fa-moon');
        localStorage.setItem('darkMode', 'disabled');
    }
});

// Three Dots Menu Toggle
const dotsMenuBtn = document.getElementById('dotsMenuBtn');
const dotsDropdown = document.getElementById('dotsDropdown');

dotsMenuBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    dotsDropdown.classList.toggle('show');
});

document.addEventListener('click', function() {
    dotsDropdown.classList.remove('show');
});

// Toggle booth application details
document.getElementById('applyForBooth')?.addEventListener('change', function(e) {
    const detailsDiv = document.getElementById('boothApplicationDetails');
    detailsDiv.style.display = e.target.checked ? 'block' : 'none';
});

// Check for event conflicts on a specific date
async function checkEventConflict(dateToCheck, excludeEventId = null) {
    if (!dateToCheck) return false;
    
    const dateStr = dateToCheck.toDateString();
    const conflictingEvents = [];
    
    for (const event of upcomingEvents) {
        if (excludeEventId && event.id === excludeEventId) continue;
        if (event.parsedDate && event.parsedDate.toDateString() === dateStr) {
            conflictingEvents.push(event);
        }
    }
    
    return conflictingEvents.length > 0 ? conflictingEvents : false;
}

// Real-time conflict detection when date is selected
document.getElementById('eventDate')?.addEventListener('change', async function(e) {
    const selectedDate = new Date(this.value);
    if (!selectedDate || isNaN(selectedDate.getTime())) return;
    
    const conflicts = await checkEventConflict(selectedDate);
    const warningDiv = document.getElementById('conflictWarning');
    const conflictMessage = document.getElementById('conflictMessage');
    const submitBtn = document.getElementById('submitEventBtn');
    
    if (conflicts && conflicts.length > 0) {
        const eventTitles = conflicts.map(ev => ev.title || 'Untitled Event').join(', ');
        warningDiv.style.display = 'block';
        warningDiv.className = 'conflict-error';
        conflictMessage.innerHTML = `<strong>⚠️ Event Conflict Detected!</strong><br>Another event "${eventTitles}" already exists on ${selectedDate.toLocaleDateString()}.<br>Please choose a different date.`;
        submitBtn.disabled = true;
        submitBtn.classList.add('btn-secondary');
        submitBtn.classList.remove('btn-primary');
    } else {
        warningDiv.style.display = 'block';
        warningDiv.className = 'conflict-warning';
        conflictMessage.innerHTML = `<i class="fas fa-check-circle"></i> ✓ No conflicts found for ${selectedDate.toLocaleDateString()}. You can create this event.`;
        submitBtn.disabled = false;
        submitBtn.classList.remove('btn-secondary');
        submitBtn.classList.add('btn-primary');
    }
});

// Open Add Event Modal
function openAddEventModal() {
    document.getElementById('addEventForm').reset();
    document.getElementById('conflictWarning').style.display = 'none';
    document.getElementById('submitEventBtn').disabled = false;
    document.getElementById('submitEventBtn').classList.remove('btn-secondary');
    document.getElementById('submitEventBtn').classList.add('btn-primary');
    new bootstrap.Modal(document.getElementById('addEventModal')).show();
};

// Add Event with Conflict Validation
document.getElementById('addEventForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const title = document.getElementById('eventTitle').value.trim();
    const dateStr = document.getElementById('eventDate').value;
    const venue = document.getElementById('eventVenue').value.trim();
    const totalBooths = parseInt(document.getElementById('eventTotalBooths').value) || 0;
    
    if (!title || !dateStr) {
        showNotification('Please fill in event title and date', 'warning');
        return;
    }
    
    const eventDate = new Date(dateStr);
    eventDate.setHours(0, 0, 0, 0);
    
    // Check for conflicts
    const conflicts = await checkEventConflict(eventDate);
    if (conflicts && conflicts.length > 0) {
        showNotification(`❌ Cannot create event! Another event already exists on ${eventDate.toLocaleDateString()}`, 'error');
        return;
    }
    
    try {
        await addDoc(collection(db, "events"), {
            title: title,
            date: eventDate,
            venue: venue || null,
            totalBooths: totalBooths,
            reservedBooths: 0,
            createdAt: new Date()
        });
        
        bootstrap.Modal.getInstance(document.getElementById('addEventModal')).hide();
        showNotification(`✅ Event "${title}" created successfully on ${eventDate.toLocaleDateString()}!`, 'success');
        await loadUpcomingEvents();
    } catch (error) {
        console.error("Error creating event:", error);
        showNotification('Error creating event: ' + error.message, 'error');
    }
});

// Keep sample event function for backward compatibility
window.addSampleEvent = async function() {
    openAddEventModal();
};

async function loadUpcomingEvents() {
    const container = document.getElementById("eventsListContainer");
    container.innerHTML = "";

    const today = new Date();
    today.setHours(0,0,0,0);
    upcomingEvents = [];

    try {
        const q = query(collection(db, "events"), orderBy("date", "asc"));
        const snapshot = await getDocs(q);

        snapshot.forEach(doc => {
            const event = doc.data();
            let eventDate;

            if (event.date?.toDate) {
                eventDate = event.date.toDate();
            } else if (typeof event.date === "string") {
                eventDate = new Date(event.date);
            }

            if (eventDate) {
                eventDate.setHours(0,0,0,0);
                if (eventDate >= today || eventDate.getTime() === today.getTime()) {
                    upcomingEvents.push({
                        id: doc.id,
                        ...event,
                        parsedDate: eventDate,
                        totalBooths: event.totalBooths || 0,
                        reservedBooths: event.reservedBooths || 0
                    });
                }
            } else {
                upcomingEvents.push({
                    id: doc.id,
                    ...event,
                    parsedDate: null,
                    totalBooths: event.totalBooths || 0,
                    reservedBooths: event.reservedBooths || 0
                });
            }
        });

        upcomingEvents.sort((a,b) => {
            if (!a.parsedDate) return 1;
            if (!b.parsedDate) return -1;
            return a.parsedDate - b.parsedDate;
        });

        if (upcomingEvents.length === 0) {
            const wrapper = document.createElement('div');
            wrapper.className = 'text-center text-muted py-4';

            const icon = document.createElement('i');
            icon.className = 'fas fa-calendar-times fa-2x mb-2';

            const text = document.createElement('div');
            text.textContent = 'No upcoming events found.';

            const small = document.createElement('small');
            small.textContent = 'Click "Add New Event" to create your first event.';

            wrapper.append(icon, text, small);
            container.appendChild(wrapper);
            return;
        }

        const listGroup = document.createElement('div');
        listGroup.className = 'list-group';

        upcomingEvents.forEach(event => {
            const item = document.createElement('div');
            item.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center event-item';
            
            if (selectedEventId === event.id) {
                item.classList.add('active');
            }

            item.style.cursor = 'pointer';

            item.addEventListener('click', () => {
                selectEventForRSVP(event.id);
            });

            const leftDiv = document.createElement('div');

            const title = document.createElement('strong');
            title.textContent = event.title || "Untitled Event";

            const meta = document.createElement('div');
            meta.className = 'text-muted small';

            const dateStr = event.parsedDate 
                ? event.parsedDate.toLocaleDateString() 
                : 'Date TBD';

            const availableBooths = (event.totalBooths || 0) - (event.reservedBooths || 0);

            let metaText = `📅 ${dateStr}`;

            if (event.venue) {
                metaText += ` | 📍 ${event.venue}`;
            }

            metaText += ` | Booths: ${availableBooths} available`;

            meta.textContent = metaText;

            leftDiv.append(title, meta);

            const btn = document.createElement('button');
            btn.className = 'btn btn-sm btn-primary';
            btn.textContent = 'View Attendance';

            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                selectEventForRSVP(event.id);
            });

            item.append(leftDiv, btn);
            listGroup.appendChild(item);
        });

        container.appendChild(listGroup);

    } catch (error) {
        console.error("Error loading events:", error);

        const errorDiv = document.createElement('div');
        errorDiv.className = 'text-center text-danger py-3';
        errorDiv.textContent = 'Error loading events';

        container.appendChild(errorDiv);
    }
}

// Calculate and display Attendance Hit Rate
function calculateAndDisplayHitRate() {
    const totalRSVPs = currentRSVPs.length;
    const actualAttendees = currentRSVPs.length + currentWalkins.length;
    let hitRate = 0;
    
    if (totalRSVPs > 0) {
        hitRate = (actualAttendees / totalRSVPs) * 100;
        hitRate = Math.round(hitRate * 10) / 10;
    }
    
    document.getElementById('hitRatePercent').innerHTML = `${hitRate}%`;
    document.getElementById('totalRSVPsCount').innerText = totalRSVPs;
    document.getElementById('actualAttendeesCount').innerText = actualAttendees;
    document.getElementById('hitRateConversionText').innerHTML = `${actualAttendees} / ${totalRSVPs}`;
    
    const progressBar = document.getElementById('hitRateProgressBar');
    progressBar.style.width = `${hitRate}%`;
    
    if (hitRate >= 70) {
        progressBar.className = "progress-bar bg-success";
    } else if (hitRate >= 40) {
        progressBar.className = "progress-bar bg-warning";
    } else {
        progressBar.className = "progress-bar bg-danger";
    }
    
    const hitRateDetail = document.getElementById('hitRateDetail');
    if (hitRateDetail) {
        hitRateDetail.innerText = `(${actualAttendees} attended out of ${totalRSVPs} RSVPs)`;
    }
}


async function loadRSVPsAndWalkins(eventId) {
    if (!eventId) return;

    const tableBody = document.getElementById("tableBody");
    tableBody.innerHTML = `<tr><td colspan="6" class="text-center text-muted">Loading attendees...</td></tr>`;

    try {
        // Load RSVPs (includes both web-added and mobile-app RSVPs)
        const rsvpRef = collection(db, "events", eventId, "rsvp");
        const rsvpSnapshot = await getDocs(rsvpRef);
        currentRSVPs = [];
        rsvpSnapshot.forEach(doc => {
            currentRSVPs.push({ id: doc.id, ...doc.data(), type: 'rsvp' });
        });

        // Load Walk-ins
        const walkinRef = collection(db, "events", eventId, "walkins");
        const walkinSnapshot = await getDocs(walkinRef);
        currentWalkins = [];
        walkinSnapshot.forEach(doc => {
            currentWalkins.push({ id: doc.id, ...doc.data(), type: 'walkin' });
        });
        
        // Load Booth data from event document
        const eventDoc = await getDoc(doc(db, "events", eventId));
        if (eventDoc.exists()) {
            const eventData = eventDoc.data();
            currentBoothData = {
                totalBooths: eventData.totalBooths || 0,
                reservedBooths: eventData.reservedBooths || 0
            };
        } else {
            currentBoothData = { totalBooths: 0, reservedBooths: 0 };
        }

        updateAggregateDisplay();
        updateBoothDisplay();
        calculateAndDisplayHitRate();
        renderAttendeesTable();

    } catch (error) {
        console.error("Error loading data:", error);
        tableBody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Error loading attendees</td></tr>`;
    }
}

function updateBoothDisplay() {
    const available = currentBoothData.totalBooths - currentBoothData.reservedBooths;
    document.getElementById('availableBoothsDisplay').innerText = Math.max(0, available);
    document.getElementById('reservedBoothsDisplay').innerText = currentBoothData.reservedBooths;
    document.getElementById('totalBoothsDisplay').innerText = currentBoothData.totalBooths;
    
    const percentage = currentBoothData.totalBooths > 0 
        ? (currentBoothData.reservedBooths / currentBoothData.totalBooths) * 100 
        : 0;
    document.getElementById('boothProgressFill').style.width = percentage + '%';
}

function updateAggregateDisplay() {
    const rsvpCount = currentRSVPs.length;
    const walkinCount = currentWalkins.length;
    const totalCount = rsvpCount + walkinCount;
    
    document.getElementById('aggregateRSVPCount').innerText = rsvpCount;
    document.getElementById('aggregateWalkinCount').innerText = walkinCount;
    document.getElementById('aggregateTotalCount').innerText = totalCount;
    document.getElementById('totalGuests').innerText = totalCount;
}


function renderAttendeesTable() {
    const tableBody = document.getElementById("tableBody");
    tableBody.innerHTML = "";

    if (currentRSVPs.length === 0 && currentWalkins.length === 0) {
        const tr = document.createElement("tr");
        const td = document.createElement("td");

        td.colSpan = 6;
        td.className = "text-center text-muted";
        td.textContent = "No attendees yet. Add RSVP or walk-in guests.";

        tr.appendChild(td);
        tableBody.appendChild(tr);
        return;
    }


    currentRSVPs.forEach(rsvp => {
        const isMobileRSVP = rsvp.source === 'mobile_app';
        const tr = document.createElement("tr");


        const tdName = document.createElement("td");
        const icon = document.createElement("i");
        icon.className = "fas fa-envelope text-primary me-2";
        tdName.append(icon, document.createTextNode(rsvp.name || "Unnamed"));

        if (isMobileRSVP) {
            const mobileBadge = document.createElement("span");
            mobileBadge.className = "badge bg-info text-dark ms-2";
            mobileBadge.style.fontSize = "0.65rem";
            mobileBadge.textContent = "📱 App";
            tdName.appendChild(mobileBadge);
        }

        const tdEmail = document.createElement("td");
        tdEmail.textContent = rsvp.email || "-";

        const tdType = document.createElement("td");
        const badge = document.createElement("span");
        badge.className = "rsvp-badge";
        badge.textContent = "📧 RSVP";
        tdType.appendChild(badge);

        const tdBooth = document.createElement("td");
        if (!isMobileRSVP && rsvp.appliedForBooth) {
            const boothSpan = document.createElement("span");
            boothSpan.className = "booth-applied-badge";
            boothSpan.textContent = rsvp.boothType || "Standard";
            tdBooth.appendChild(boothSpan);
        } else {
            tdBooth.textContent = "—";
            tdBooth.className = "text-muted";
        }

        const tdNotes = document.createElement("td");

        if (isMobileRSVP) {
            const guestCount = (typeof rsvp.plusOne === 'number') ? rsvp.plusOne : 0;
            if (guestCount > 0) {
                tdNotes.textContent = `+${guestCount} guest`;
            } else {
                tdNotes.textContent = "No plus one";
            }
        } else {
            if (rsvp.plusOne && typeof rsvp.plusOne === 'string' && rsvp.plusOne.trim()) {
                tdNotes.textContent = `+1: ${rsvp.plusOne}`;
            } else {
                tdNotes.textContent = "No plus one";
            }

            if (rsvp.boothPreferences) {
                const br = document.createElement("br");
                const small = document.createElement("small");
                small.className = "text-muted";
                small.textContent = `Pref: ${rsvp.boothPreferences}`;
                tdNotes.append(br, small);
            }
        }

        const tdAction = document.createElement("td");
        const btn = document.createElement("button");
        btn.className = "btn btn-sm btn-outline-danger";
        btn.title = isMobileRSVP ? "Remove from web tracker (mobile attendance record remains)" : "Delete RSVP";

        const trashIcon = document.createElement("i");
        trashIcon.className = "fas fa-trash";
        btn.appendChild(trashIcon);

        btn.addEventListener("click", () => {
            deleteAttendee(rsvp.id, "rsvp");
        });

        tdAction.appendChild(btn);

        tr.append(tdName, tdEmail, tdType, tdBooth, tdNotes, tdAction);
        tableBody.appendChild(tr);
    });

    // WALK-IN ROWS (unchanged)
    currentWalkins.forEach(walkin => {
        const tr = document.createElement("tr");

        const tdName = document.createElement("td");
        const icon = document.createElement("i");
        icon.className = "fas fa-person-walking-arrow-right text-warning me-2";
        tdName.append(icon, document.createTextNode(walkin.name || "Unnamed"));

        const tdContact = document.createElement("td");
        tdContact.textContent = walkin.contact || "-";

        const tdType = document.createElement("td");
        const badge = document.createElement("span");
        badge.className = "walkin-badge";
        badge.textContent = "🚶 Walk-in";
        tdType.appendChild(badge);

        const tdBooth = document.createElement("td");
        tdBooth.textContent = "—";

        const tdNotes = document.createElement("td");
        tdNotes.textContent = walkin.notes || "No notes";

        const tdAction = document.createElement("td");
        const btn = document.createElement("button");
        btn.className = "btn btn-sm btn-outline-danger";

        const trashIcon = document.createElement("i");
        trashIcon.className = "fas fa-trash";
        btn.appendChild(trashIcon);

        btn.addEventListener("click", () => {
            deleteAttendee(walkin.id, "walkin");
        });

        tdAction.appendChild(btn);

        tr.append(tdName, tdContact, tdType, tdBooth, tdNotes, tdAction);
        tableBody.appendChild(tr);
    });
}

function deleteAttendee(id, type) {
    if (!confirm(`Are you sure you want to delete this ${type === 'rsvp' ? 'RSVP' : 'walk-in'}?`)) return;
    
    try {
        const collectionName = type === 'rsvp' ? 'rsvp' : 'walkins';
        deleteDoc(doc(db, "events", selectedEventId, collectionName, id));
        
        updateDoc(doc(db, "events", selectedEventId), {
            updatedAt: serverTimestamp()
        });
        
        showNotification(`${type === 'rsvp' ? 'RSVP' : 'Walk-in'} deleted successfully`, 'success');
        loadRSVPsAndWalkins(selectedEventId);
    } catch (error) {
        console.error("Error deleting:", error);
        showNotification('Error deleting attendee', 'error');
    }
};

function selectEventForRSVP(eventId) {
    selectedEventId = eventId;
    const event = upcomingEvents.find(ev => ev.id === eventId);
    
    if (event) {
        document.getElementById('eventTitleDisplay').innerText = event.title || 'CBOC Event';
        const dateStr = event.parsedDate ? event.parsedDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : 'Date TBD';
        document.getElementById('eventDateDisplay').innerText = dateStr;
        document.getElementById('venueDisplay').innerText = event.venue || 'TBD';
    }
    
    document.getElementById('eventContent').style.display = 'block';
    loadRSVPsAndWalkins(selectedEventId);
    loadUpcomingEvents();
};

function addRSVPBtn() {
    if (!selectedEventId) {
        showNotification('Please select an event first', 'warning');
        return;
    }
    document.getElementById('rsvpForm').reset();
    document.getElementById('boothApplicationDetails').style.display = 'none';
    document.getElementById('applyForBooth').checked = false;
    new bootstrap.Modal(document.getElementById('rsvpModal')).show();
};

function addWalkinBtn() {
    if (!selectedEventId) {
        showNotification('Please select an event first', 'warning');
        return;
    }
    document.getElementById('walkinForm').reset();
    new bootstrap.Modal(document.getElementById('walkinModal')).show();
};

function openBoothModal() {
    if (!selectedEventId) {
        showNotification('Please select an event first', 'warning');
        return;
    }
    document.getElementById('totalBoothsInput').value = currentBoothData.totalBooths;
    document.getElementById('reservedBoothsInput').value = currentBoothData.reservedBooths;
    updateBoothPreview();
    new bootstrap.Modal(document.getElementById('boothModal')).show();
};

function updateBoothPreview() {
    const total = parseInt(document.getElementById('totalBoothsInput').value) || 0;
    const reserved = parseInt(document.getElementById('reservedBoothsInput').value) || 0;
    const available = Math.max(0, total - reserved);
    document.getElementById('previewAvailable').innerText = available;
}

document.getElementById('totalBoothsInput')?.addEventListener('input', updateBoothPreview);
document.getElementById('reservedBoothsInput')?.addEventListener('input', updateBoothPreview);

document.getElementById('boothForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    if (!selectedEventId) return;
    
    const totalBooths = parseInt(document.getElementById('totalBoothsInput').value) || 0;
    const reservedBooths = parseInt(document.getElementById('reservedBoothsInput').value) || 0;
    
    if (reservedBooths > totalBooths) {
        showNotification('Reserved booths cannot exceed total booths', 'error');
        return;
    }
    
    try {
        await updateDoc(doc(db, "events", selectedEventId), {
            totalBooths: totalBooths,
            reservedBooths: reservedBooths,
            updatedAt: serverTimestamp()
        });
        
        currentBoothData = { totalBooths, reservedBooths };
        updateBoothDisplay();
        bootstrap.Modal.getInstance(document.getElementById('boothModal')).hide();
        showNotification(`Booth settings updated! ${totalBooths - reservedBooths} booths available`, 'success');
        loadUpcomingEvents();
    } catch (error) {
        console.error("Error saving booth data:", error);
        showNotification('Error saving booth settings', 'error');
    }
});

document.getElementById('rsvpForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    if (!selectedEventId) return;

    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const plusOne = document.getElementById('plusOneName').value.trim();
    const applyForBooth = document.getElementById('applyForBooth').checked;

    if (!name || !email) {
        showNotification('Please fill in name and email', 'warning');
        return;
    }

    const rsvpData = {
        name: name,
        email: email,
        plusOne: plusOne || null,
        source: 'web',   // tag web-added RSVPs for clarity
        createdAt: new Date()
    };

    if (applyForBooth) {
        rsvpData.appliedForBooth = true;
        rsvpData.boothType = document.getElementById('boothType').value;
        rsvpData.boothPreferences = document.getElementById('boothPreferences').value.trim() || null;
        rsvpData.boothStatus = 'pending';
        
        if (currentBoothData.reservedBooths < currentBoothData.totalBooths) {
            const newReserved = currentBoothData.reservedBooths + 1;
            await updateDoc(doc(db, "events", selectedEventId), {
                reservedBooths: newReserved,
                updatedAt: serverTimestamp()
            });
            currentBoothData.reservedBooths = newReserved;
            updateBoothDisplay();
            showNotification('Booth application submitted! Booth reserved pending approval.', 'info');
        } else {
            showNotification('Warning: No booths available! Application submitted but no booth reserved.', 'warning');
        }
    }

    try {
        await addDoc(collection(db, "events", selectedEventId, "rsvp"), rsvpData);
        await updateDoc(doc(db, "events", selectedEventId), {
            updatedAt: serverTimestamp()
        });

        bootstrap.Modal.getInstance(document.getElementById('rsvpModal')).hide();
        showNotification(applyForBooth ? 'RSVP with booth application added!' : 'RSVP added successfully!', 'success');
        await loadRSVPsAndWalkins(selectedEventId);
    } catch (error) {
        console.error("Error saving RSVP:", error);
        showNotification('Error saving RSVP', 'error');
    }
});

document.getElementById('walkinForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    if (!selectedEventId) return;

    const name = document.getElementById('walkinName').value.trim();
    const contact = document.getElementById('walkinContact').value.trim();
    const notes = document.getElementById('walkinNotes').value.trim();

    if (!name) {
        showNotification('Please enter walk-in name', 'warning');
        return;
    }

    try {
        await addDoc(collection(db, "events", selectedEventId, "walkins"), {
            name: name,
            contact: contact || null,
            notes: notes || null,
            createdAt: new Date()
        });

        await updateDoc(doc(db, "events", selectedEventId), {
            updatedAt: serverTimestamp()
        });

        bootstrap.Modal.getInstance(document.getElementById('walkinModal')).hide();
        showNotification('Walk-in added successfully!', 'success');
        await loadRSVPsAndWalkins(selectedEventId);
    } catch (error) {
        console.error("Error saving walk-in:", error);
        showNotification('Error saving walk-in', 'error');
    }
});

function exportCSV() {
    if (!selectedEventId) {
        showNotification('Select an event first', 'warning');
        return;
    }

    let csv = "Attendee Name,Email/Contact,Type,Source,Booth Applied,Plus One/Notes\n";
    
    currentRSVPs.forEach(r => {
        const isMobile = r.source === 'mobile_app';
        const plusOneDisplay = isMobile
            ? (r.plusOne > 0 ? `+${r.plusOne} guest` : '')
            : (r.plusOne || '');
        csv += `"${r.name || ''}","${r.email || ''}","RSVP","${isMobile ? 'Mobile App' : 'Web'}","${r.appliedForBooth ? `Yes (${r.boothType || ''})` : 'No'}","${plusOneDisplay} ${r.boothPreferences || ''}"\n`;
    });
    
    currentWalkins.forEach(w => {
        csv += `"${w.name || ''}","${w.contact || ''}","Walk-in","Web","No","${w.notes || ''}"\n`;
    });

    const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
    const url = URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = `attendees_${selectedEventId}.csv`;
    a.click();
    URL.revokeObjectURL(url);
    showNotification('CSV exported!', 'success');
};

function exportPDF() {
    if (!selectedEventId) {
        showNotification('Select an event first', 'warning');
        return;
    }

    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF();
    
    const event = upcomingEvents.find(ev => ev.id === selectedEventId);
    const eventTitle = event ? event.title : 'Event';
    const availableBooths = currentBoothData.totalBooths - currentBoothData.reservedBooths;
    const totalRSVPs = currentRSVPs.length;
    const actualAttendees = currentRSVPs.length + currentWalkins.length;
    const hitRate = totalRSVPs > 0 ? ((actualAttendees / totalRSVPs) * 100).toFixed(1) : 0;
    const boothApplicants = currentRSVPs.filter(r => r.appliedForBooth).length;
    const mobileRSVPs = currentRSVPs.filter(r => r.source === 'mobile_app').length;
    
    pdf.setFontSize(18);
    pdf.text(`Event: ${eventTitle}`, 14, 20);
    pdf.setFontSize(12);
    pdf.text(`Attendance Hit Rate: ${hitRate}% (${actualAttendees} / ${totalRSVPs} RSVPs attended)`, 14, 30);
    pdf.text(`Booth Availability: ${availableBooths} / ${currentBoothData.totalBooths} booths available`, 14, 40);
    pdf.text(`Booth Applications: ${boothApplicants}`, 14, 50);
    pdf.text(`Total Attendance: ${actualAttendees} (RSVP: ${totalRSVPs} | Walk-ins: ${currentWalkins.length})`, 14, 60);
    pdf.text(`Mobile App RSVPs: ${mobileRSVPs}`, 14, 70);
    
    const tableBody = [];
    currentRSVPs.forEach(r => {
        const isMobile = r.source === 'mobile_app';
        const plusOneDisplay = isMobile
            ? (r.plusOne > 0 ? `+${r.plusOne} guest` : '')
            : (r.plusOne || '');
        tableBody.push([
            r.name || '',
            r.email || '',
            'RSVP',
            isMobile ? '📱 App' : '🌐 Web',
            r.appliedForBooth ? `Yes (${r.boothType || ''})` : 'No',
            plusOneDisplay
        ]);
    });
    currentWalkins.forEach(w => {
        tableBody.push([w.name || '', w.contact || '', 'Walk-in', '🌐 Web', 'No', w.notes || '']);
    });
    
    pdf.autoTable({
        head: [['Name', 'Contact', 'Type', 'Source', 'Booth?', 'Details']],
        body: tableBody,
        startY: 80,
        headStyles: { fillColor: [102, 126, 234] }
    });
    
    pdf.save(`attendees_${selectedEventId}.pdf`);
    showNotification('PDF exported!', 'success');
};

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

function showNotification(message, type = 'success') {
    const existing = document.querySelector('.notification');
    if (existing) existing.remove();
    
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i><span>${message}</span>`;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

async function updateLastUpdated() {
    if (!selectedEventId) return;
    try {
        const eventRef = doc(db, "events", selectedEventId);
        const snapshot = await getDoc(eventRef);
        if (snapshot.exists() && snapshot.data().updatedAt?.toDate) {
            const date = snapshot.data().updatedAt.toDate();
            document.getElementById('lastUpdated').innerText = date.toLocaleString();
        } else {
            document.getElementById('lastUpdated').innerText = new Date().toLocaleString();
        }
    } catch (error) {
        document.getElementById('lastUpdated').innerText = '-';
    }
}

document.getElementById('editEventDate').addEventListener('click', () => {
    document.getElementById('eventDateInput').value = new Date().toISOString().slice(0,10);
    new bootstrap.Modal(document.getElementById('eventDateModal')).show();
});

document.getElementById('editVenue').addEventListener('click', () => {
    document.getElementById('venueInput').value = document.getElementById('venueDisplay').innerText;
    new bootstrap.Modal(document.getElementById('venueModal')).show();
});

loadUpcomingEvents();
setInterval(() => updateLastUpdated(), 5000);

window.selectEventForRSVP = selectEventForRSVP;
window.addRSVPBtn = addRSVPBtn;
window.addWalkinBtn = addWalkinBtn;
window.exportCSV = exportCSV;
window.exportPDF = exportPDF;
window.openBoothModal = openBoothModal;

document.getElementById("exportRSVPs").addEventListener("click", exportRSVPs);
document.getElementById("printRSVPList").addEventListener("click", printRSVPList);
document.getElementById("refreshRSVPs").addEventListener("click", refreshRSVPs);
document.getElementById("showRSVPHelp").addEventListener("click", showRSVPHelp);
document.getElementById("openAddEventModal").addEventListener("click", openAddEventModal);
document.getElementById("addRSVPBtn").addEventListener("click", addRSVPBtn);
document.getElementById("addWalkinBtn").addEventListener("click", addWalkinBtn);
document.getElementById("openBoothModal").addEventListener("click", openBoothModal);
document.getElementById("exportCSV").addEventListener("click", exportCSV);
document.getElementById("exportPDF").addEventListener("click", exportPDF);