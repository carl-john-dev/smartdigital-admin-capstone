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
window.exportRSVPs = function() {
    showNotification('Export feature available in the export buttons below', 'info');
};

window.printRSVPList = function() {
    window.print();
};

window.refreshRSVPs = function() {
    location.reload();
};

window.showRSVPHelp = function() {
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
- NEW: Event conflict detection - cannot create two events on the same day
- NEW: Apply for booth when adding RSVP
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

// NEW: Check for event conflicts on a specific date
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

// NEW: Real-time conflict detection when date is selected
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

// NEW: Open Add Event Modal
window.openAddEventModal = function() {
    document.getElementById('addEventForm').reset();
    document.getElementById('conflictWarning').style.display = 'none';
    document.getElementById('submitEventBtn').disabled = false;
    document.getElementById('submitEventBtn').classList.remove('btn-secondary');
    document.getElementById('submitEventBtn').classList.add('btn-primary');
    new bootstrap.Modal(document.getElementById('addEventModal')).show();
};

// NEW: Add Event with Conflict Validation (replaces addSampleEvent)
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

// Load events from Firestore
async function loadUpcomingEvents() {
    const container = document.getElementById("eventsListContainer");
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
            container.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-calendar-times fa-2x mb-2"></i>
                    <div>No upcoming events found.</div>
                    <small>Click "Add New Event" to create your first event.</small>
                </div>
            `;
            return;
        }

        let html = `<div class="list-group">`;
        upcomingEvents.forEach(event => {
            const dateStr = event.parsedDate ? event.parsedDate.toLocaleDateString() : 'Date TBD';
            const availableBooths = (event.totalBooths || 0) - (event.reservedBooths || 0);
            html += `
            <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center event-item ${selectedEventId === event.id ? 'active' : ''}" 
                    onclick="selectEventForRSVP('${event.id}')" style="cursor:pointer;">
                <div>
                    <strong>${event.title || "Untitled Event"}</strong>
                    <div class="text-muted small">
                        <i class="fas fa-calendar-alt"></i> ${dateStr}
                        ${event.venue ? `<span class="mx-2">|</span> <i class="fas fa-map-marker-alt"></i> ${event.venue}` : ""}
                        <span class="mx-2">|</span> <i class="fas fa-booth-curtain"></i> Booths: ${availableBooths} available
                    </div>
                </div>
                <button class="btn btn-sm btn-primary" onclick="event.stopPropagation(); selectEventForRSVP('${event.id}')">
                    View Attendance
                </button>
            </div>
            `;
        });
        html += `</div>`;
        container.innerHTML = html;
    } catch (error) {
        console.error("Error loading events:", error);
        container.innerHTML = `<div class="text-center text-danger py-3">Error loading events</div>`;
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

// Load RSVPs and Walk-ins for selected event
async function loadRSVPsAndWalkins(eventId) {
    if (!eventId) return;

    const tableBody = document.getElementById("tableBody");
    tableBody.innerHTML = `<tr><td colspan="6" class="text-center text-muted">Loading attendees...</td></tr>`;

    try {
        // Load RSVPs
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
    
    if (currentRSVPs.length === 0 && currentWalkins.length === 0) {
        tableBody.innerHTML = `<tr><td colspan="6" class="text-center text-muted">No attendees yet. Add RSVP or walk-in guests.</td></tr>`;
        return;
    }

    let html = '';
    
    currentRSVPs.forEach(rsvp => {
        const boothBadge = rsvp.appliedForBooth ? 
            `<span class="booth-applied-badge"><i class="fas fa-booth-curtain"></i> ${rsvp.boothType || 'Standard'}</span>` : 
            `<span class="text-muted">—</span>`;
        html += `
            <tr>
                <td><i class="fas fa-envelope text-primary me-2"></i> ${escapeHtml(rsvp.name || 'Unnamed')}</td>
                <td>${escapeHtml(rsvp.email || '-')}</td>
                <td><span class="rsvp-badge">📧 RSVP</span></td>
                <td>${boothBadge}</td>
                <td>${rsvp.plusOne ? `+1: ${escapeHtml(rsvp.plusOne)}` : 'No plus one'}${rsvp.boothPreferences ? `<br><small class="text-muted">Pref: ${escapeHtml(rsvp.boothPreferences)}</small>` : ''}</td>
                <td>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteAttendee('${rsvp.id}', 'rsvp')">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    currentWalkins.forEach(walkin => {
        html += `
            <tr>
                <td><i class="fas fa-person-walking-arrow-right text-warning me-2"></i> ${escapeHtml(walkin.name || 'Unnamed')}</td>
                <td>${escapeHtml(walkin.contact || '-')}</td>
                <td><span class="walkin-badge">🚶 Walk-in</span></td>
                <td>—</td>
                <td>${escapeHtml(walkin.notes || 'No notes')}</td>
                <td>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteAttendee('${walkin.id}', 'walkin')">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    tableBody.innerHTML = html;
}

window.deleteAttendee = async function(id, type) {
    if (!confirm(`Are you sure you want to delete this ${type === 'rsvp' ? 'RSVP' : 'walk-in'}?`)) return;
    
    try {
        const collectionName = type === 'rsvp' ? 'rsvp' : 'walkins';
        await deleteDoc(doc(db, "events", selectedEventId, collectionName, id));
        
        await updateDoc(doc(db, "events", selectedEventId), {
            updatedAt: serverTimestamp()
        });
        
        showNotification(`${type === 'rsvp' ? 'RSVP' : 'Walk-in'} deleted successfully`, 'success');
        await loadRSVPsAndWalkins(selectedEventId);
    } catch (error) {
        console.error("Error deleting:", error);
        showNotification('Error deleting attendee', 'error');
    }
};

window.selectEventForRSVP = async function(eventId) {
    selectedEventId = eventId;
    const event = upcomingEvents.find(ev => ev.id === eventId);
    
    if (event) {
        document.getElementById('eventTitleDisplay').innerText = event.title || 'CBOC Event';
        const dateStr = event.parsedDate ? event.parsedDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : 'Date TBD';
        document.getElementById('eventDateDisplay').innerText = dateStr;
        document.getElementById('venueDisplay').innerText = event.venue || 'TBD';
    }
    
    document.getElementById('eventContent').style.display = 'block';
    await loadRSVPsAndWalkins(selectedEventId);
    loadUpcomingEvents();
};

window.addRSVPBtn = function() {
    if (!selectedEventId) {
        showNotification('Please select an event first', 'warning');
        return;
    }
    document.getElementById('rsvpForm').reset();
    document.getElementById('boothApplicationDetails').style.display = 'none';
    document.getElementById('applyForBooth').checked = false;
    new bootstrap.Modal(document.getElementById('rsvpModal')).show();
};

window.addWalkinBtn = function() {
    if (!selectedEventId) {
        showNotification('Please select an event first', 'warning');
        return;
    }
    document.getElementById('walkinForm').reset();
    new bootstrap.Modal(document.getElementById('walkinModal')).show();
};

window.openBoothModal = function() {
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

window.exportCSV = function() {
    if (!selectedEventId) {
        showNotification('Select an event first', 'warning');
        return;
    }

    let csv = "Attendee Name,Email/Contact,Type,Booth Applied,Plus One/Notes\n";
    
    currentRSVPs.forEach(r => {
        csv += `"${r.name || ''}","${r.email || ''}","RSVP","${r.appliedForBooth ? `Yes (${r.boothType || ''})` : 'No'}","${r.plusOne || ''} ${r.boothPreferences || ''}"\n`;
    });
    
    currentWalkins.forEach(w => {
        csv += `"${w.name || ''}","${w.contact || ''}","Walk-in","No","${w.notes || ''}"\n`;
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

window.exportPDF = function() {
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
    
    pdf.setFontSize(18);
    pdf.text(`Event: ${eventTitle}`, 14, 20);
    pdf.setFontSize(12);
    pdf.text(`Attendance Hit Rate: ${hitRate}% (${actualAttendees} / ${totalRSVPs} RSVPs attended)`, 14, 30);
    pdf.text(`Booth Availability: ${availableBooths} / ${currentBoothData.totalBooths} booths available`, 14, 40);
    pdf.text(`Booth Applications: ${boothApplicants}`, 14, 50);
    pdf.text(`Total Attendance: ${actualAttendees} (RSVP: ${totalRSVPs} | Walk-ins: ${currentWalkins.length})`, 14, 60);
    
    const tableBody = [];
    currentRSVPs.forEach(r => {
        tableBody.push([r.name || '', r.email || '', 'RSVP', r.appliedForBooth ? `Yes (${r.boothType || ''})` : 'No', r.plusOne || '']);
    });
    currentWalkins.forEach(w => {
        tableBody.push([w.name || '', w.contact || '', 'Walk-in', 'No', w.notes || '']);
    });
    
    pdf.autoTable({
        head: [['Name', 'Contact', 'Type', 'Booth?', 'Details']],
        body: tableBody,
        startY: 70,
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
