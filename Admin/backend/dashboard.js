/**
 * Dahil iba yung structure, di to pwede ilagay sa backend.js
 * -Arjon
 */
import { db } from '../Firebase/firebase_conn.js';
import {
    collection,
    query,
    where, 
    doc, 
    getDocs, 
    getDoc, 
    setDoc, 
    Timestamp, 
    and, 
    or, 
    orderBy, 
    onSnapshot 
} from "https://www.gstatic.com/firebasejs/12.9.0/firebase-firestore.js";
import { showToast } from './backend.js';

document.addEventListener('DOMContentLoaded', function() {
    // Dark Mode Toggle
    const darkModeToggle = document.getElementById('darkModeToggle');
    const darkModeIcon = document.getElementById('darkModeIcon');
    const body = document.body;
    
    // Check for saved dark mode preference
    const isDarkMode = localStorage.getItem('darkMode') === 'enabled';
    
    // Apply dark mode if previously enabled
    if (isDarkMode) {
        body.classList.add('dark-mode');
        darkModeIcon.classList.remove('fa-moon');
        darkModeIcon.classList.add('fa-sun');
    }
    
    // Toggle dark mode
    darkModeToggle.addEventListener('click', function() {
        body.classList.toggle('dark-mode');
        
        // Update icon
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

    // Add subtle animation to stats cards on page load
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, index) => {
        card.style.animation = `fadeInUp 0.5s ease ${index * 0.1}s forwards`;
        card.style.opacity = '0';
    });
});

// Edit About Us Function
export async function openAboutEditor() {
    const docRef = doc(db, "siteContent", "aboutCBOC");
    const docSnap = await getDoc(docRef);

    if (docSnap.exists()) {
        const data = docSnap.data();

        document.getElementById("editWelcomeHeader").value = data.welcomeHeader || "";
        document.getElementById("editWelcomeText").value = data.welcomeText || "";
        document.getElementById("editBackground1").value = data.backgroundText1 || "";
        document.getElementById("editBackground2").value = data.backgroundText2 || "";
        document.getElementById("editAbout1").value = data.aboutUsText1 || "";
        document.getElementById("editAbout2").value = data.aboutUsText2 || "";
        document.getElementById("editMission").value = data.missionText || "";
        document.getElementById("editVision").value = data.visionText || "";
        document.getElementById("editValues").value = data.valuesText || "";
        document.getElementById("editYear").value = data.foundedYear || "";
        document.getElementById("editLabel").value = data.foundedLabel || "";
        document.getElementById("editFooterText").value = data.footerText || "";
        document.getElementById("editFooterCopyright1").value = data.footerCopyright1 || "";
        document.getElementById("editFooterCopyright2").value = data.footerCopyright2 || "";
    }

    const modal = new bootstrap.Modal(document.getElementById("aboutEditorModal"));
    modal.show();
}

// Save About Us Function
export async function saveAboutContent() {
    try {
        await setDoc(doc(db, "siteContent", "aboutCBOC"), {
            welcomeHeader: document.getElementById("editWelcomeHeader").value,
            welcomeText: document.getElementById("editWelcomeText").value,
            backgroundText1: document.getElementById("editBackground1").value,
            backgroundText2: document.getElementById("editBackground2").value,
            aboutUsText1: document.getElementById("editAbout1").value,
            aboutUsText2: document.getElementById("editAbout2").value,
            missionText: document.getElementById("editMission").value,
            visionText: document.getElementById("editVision").value,
            valuesText: document.getElementById("editValues").value,
            foundedYear: document.getElementById("editYear").value,
            foundedLabel: document.getElementById("editLabel").value,
            footerText: document.getElementById("editFooterText").value,
            footerCopyright1: document.getElementById("editFooterCopyright1").value,
            footerCopyright2: document.getElementById("editFooterCopyright2").value
        });

        showToast("About section updated successfully!", "success");
        bootstrap.Modal.getInstance(document.getElementById("aboutEditorModal")).hide();

    } catch (error) {
        console.error(error);
        showToast("Error saving content", "warning");
    }
}

// Add CSS for animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(style);

// Fetch Firebase DB for data and load
async function loadUpcomingEvents() {
    const calendarList = document.querySelector(".calendar-list");
    calendarList.innerHTML = "";

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    const eventsQuery = query(
        collection(db, "events"),
        and(
            where("date", ">=", Timestamp.fromDate(today)),
            or(
                where("approved", "==", true),
                where("createdBy", "==", "Admin")
            )
        )
    );

    try {
        const snapshot = await getDocs(eventsQuery);

        if (snapshot.empty) {
            const li = document.createElement("li");
            li.classList.add("text-muted");
            li.innerHTML = `<i class="fas fa-calendar-times"></i> No upcoming events`;
            calendarList.appendChild(li);
            return;
        }

        snapshot.forEach(doc => {
            const event = doc.data();

            const eventDate = event.date.toDate().toLocaleDateString();

            const li = document.createElement("li");
            li.innerHTML = `
                <strong>${event.title}</strong> - 
                ${event.title} at ${eventDate} 
                from ${formatTime(event.startHour, event.startMinute)} 
                to ${formatTime(event.endHour, event.endMinute)}    
            `;

            calendarList.appendChild(li);
        });

    } catch (error) {
        console.error("Error loading events:", error);
    }
}

function formatTime(hour, minute) {
    // Ensure hour and minute are numbers
    hour = Number(hour);
    minute = Number(minute);

    // Pad minutes with leading zero
    const paddedMinute = minute.toString().padStart(2, "0");

    // Convert to 12-hour format
    let period = "AM";
    let standardHour = hour;

    if (hour === 0) {
        standardHour = 12; // midnight
    } else if (hour === 12) {
        period = "PM"; // noon
    } else if (hour > 12) {
        standardHour = hour - 12;
        period = "PM";
    }

    return `${standardHour}:${paddedMinute} ${period}`;
}

function getInitials(name) {
    return name.split(' ')
            .filter(n => n)
            .map(n => n[0].toUpperCase())
            .join('')
            .slice(0, 2); // Only first 2 letters
}

// Fetch approved users and render them
async function renderNewMembers() {
    const container = document.getElementById('newMembersContainer');
    container.innerHTML = ''; // Clear existing content

    const usersRef = collection(db, "users");
    const q = query(
        usersRef,
        where("approved", "==", true),
        orderBy("createdAt", "desc")
    );

    const snapshot = await getDocs(q);
    snapshot.forEach(doc => {
        const user = doc.data();
        const initials = getInitials(user.name || 'Unnamed');
        const title = user.professionalTitle || '???';
        const business = user.businessName || 'Unknown Business';

        const card = document.createElement('div');
        card.className = 'member-card';
        card.innerHTML = `<span>  </span>
            <div class="member-avatar">${initials}</div>
            <div>
                <h6 class="mb-1">${user.name || 'Unnamed'}</h6>
                <p class="mb-0 text-muted small">${title}, ${business}.</p>
            </div>
        `;
        container.appendChild(card);
    });
}

// Load Member Approval Data
async function loadRecentUsers() {
    const tableBody = document.getElementById("recentUsersTable");
    tableBody.innerHTML = "";

    try {
        const q = query(
            collection(db, "users"),
            orderBy("createdAt", "desc")
        );
        const snapshot = await getDocs(q);

        if (snapshot.empty) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="3" class="text-center text-muted">
                        No recent requests
                    </td>
                </tr>
            `;
            return;
        }

        snapshot.forEach(doc => {
            const user = doc.data();

            const approved = user.approved === true;

            const row = document.createElement("tr");

            row.innerHTML = `
                <td>${user.name || "Unknown"}</td>
                <td>Account Approval</td>
                <td>
                    <span class="status ${approved ? "status-resolve" : "status-pending"}">
                        ${approved ? "Resolved" : "Pending"}
                    </span>
                </td>
            `;

            tableBody.appendChild(row);
        });

    } catch (error) {
        console.error("Error loading users:", error);
    }
}

// MEMBERS (approved users)
async function loadMemberCount() {
    const membersQuery = query(
        collection(db, "users"),
        where("approved", "==", true)
    );

    onSnapshot(membersQuery, (snapshot) => {
        document.getElementById("memberCount").textContent = snapshot.size;
    });
}

// CALENDAR (approved OR admin + future date)
async function loadCalendarCount() {
    onSnapshot(collection(db, "events"), (snapshot) => {
        const now = new Date();
        let count = 0;

        snapshot.forEach(doc => {
            const data = doc.data();

            let eventDate = data.date;

            // Handle Timestamp or string
            if (eventDate?.toDate) {
                eventDate = eventDate.toDate();
            } else {
                eventDate = new Date(eventDate);
            }

            const isFuture = eventDate >= now;
            const isApproved = data.approved === true;
            const isAdmin = data.createdBy === "Admin";

            if ((isApproved || isAdmin) && isFuture) {
                count++;
            }
        });

        document.getElementById("calendarCount").textContent = count;
    });
}

// APPROVAL REQUESTS
async function loadApprovalCount() {
    let pendingUsers = 0;
    let pendingEvents = 0;
    let pendingBusinesses = 0;

    function updateApprovalUI() {
        const total = pendingUsers + pendingEvents + pendingBusinesses;
        document.getElementById("approvalCount").textContent = total;
    }

    // USERS (approved: false)
    onSnapshot(
        query(collection(db, "users"), where("approved", "==", false)),
        (snapshot) => {
            pendingUsers = snapshot.size;
            updateApprovalUI();
        }
    );

    // EVENTS (approved: false)
    onSnapshot(
        query(collection(db, "events"), where("approved", "==", false)),
        (snapshot) => {
            pendingEvents = snapshot.size;
            updateApprovalUI();
        }
    );

    // BUSINESSES (status: pending)
    onSnapshot(
        query(collection(db, "businesses"), where("status", "==", "pending")),
        (snapshot) => {
            pendingBusinesses = snapshot.size;
            updateApprovalUI();
        }
    );
}

async function loadStats() {
    await Promise.all([
        loadMemberCount(),
        loadCalendarCount(),
        loadApprovalCount()
    ]);
}

const membersOnlineContainer = document.getElementById('membersOnlineContainer');
const showOnlineBtn = document.getElementById('showOnlineBtn');
const showAllBtn = document.getElementById('showAllBtn');

function setActiveButton(mode) {
    if (mode === 'online') {
        showOnlineBtn.classList.add('btn-success');
        showOnlineBtn.classList.remove('btn-secondary');

        showAllBtn.classList.add('btn-secondary');
        showAllBtn.classList.remove('btn-success');
    } else if (mode === 'all') {
        showAllBtn.classList.add('btn-success');
        showAllBtn.classList.remove('btn-secondary');

        showOnlineBtn.classList.add('btn-secondary');
        showOnlineBtn.classList.remove('btn-success');
    }
}

let allUsers = []; // cache all users
let currentMode = 'online'; // default mode

// Real-time listener
function listenUsers() {
    const usersRef = collection(db, 'users');

    onSnapshot(usersRef, (snapshot) => {
        allUsers = snapshot.docs.map(doc => ({ id: doc.id, ...doc.data() }));
        renderMembers();
    }, (error) => {
        console.error("Error listening to users:", error);
        membersOnlineContainer.innerHTML = `<p class="text-danger">Failed to load users.</p>`;
    });
}

// Render members based on currentMode
function renderMembers() {
    membersOnlineContainer.innerHTML = '';

    let usersToShow = [];
    if (currentMode === 'online') {
        usersToShow = allUsers.filter(u => u.isOnline === true);
    } else {
        usersToShow = allUsers;
    }

    if (usersToShow.length === 0) {
        membersOnlineContainer.innerHTML = `<p class="text-muted">No users to show</p>`;
        return;
    }

    usersToShow.forEach(user => {
        const avatarClass = user.isOnline ? 'online' : (currentMode === 'all' ? 'offline' : 'online');
        const initials = getInitials(user.username || 'U');

        // Calculate last online text
        let lastOnlineText = '';
        let lastOnlineClass = 'text-danger'; // default red
        if (user.isOnline) {
            lastOnlineText = 'Currently Online';
            lastOnlineClass = 'text-success';
        } else if (user.lastOnline) {
            let lastOnlineDate;
            if (typeof user.lastOnline.toDate === 'function') {
                // Firestore Timestamp
                lastOnlineDate = user.lastOnline.toDate();
            } else {
                // String or JS Date
                lastOnlineDate = new Date(user.lastOnline);
            }

            const now = new Date();
            const diffMs = now - lastOnlineDate; // difference in milliseconds
            const diffSec = Math.floor(diffMs / 1000);
            const diffMin = Math.floor(diffSec / 60);
            const diffHrs = Math.floor(diffMin / 60);
            const diffDays = Math.floor(diffHrs / 24);

            if (diffSec < 60) {
                lastOnlineText = 'Last Online: Just now';
            } else if (diffMin < 60) {
                lastOnlineText = `Last Online: ${diffMin} minute${diffMin > 1 ? 's' : ''} ago`;
            } else if (diffHrs < 24) {
                lastOnlineText = `Last Online: ${diffHrs} hour${diffHrs > 1 ? 's' : ''} ago`;
            } else if (diffDays <= 7) {
                lastOnlineText = `Last Online: ${diffDays} day${diffDays > 1 ? 's' : ''} ago`;
            } else {
                lastOnlineText = `Last Online: ${lastOnlineDate.toLocaleString()}`;
            }
        } else {
            lastOnlineText = 'Last Online: Unknown';
        }

        const memberCard = document.createElement("div");
        memberCard.className = `member-card`;
        memberCard.innerHTML = `<span>  </span>
            <div class="member-avatar ${avatarClass}">${initials}</div>
            <div>
                <h6 class="text-muted">${user.name ? user.name : user.username}</h6>
                <p class="mb-0 text-muted small">${user.email || ''}</p>
                <p class="mb-0 small ${lastOnlineClass}">${lastOnlineText}</p>
            </div>
        `;
        membersOnlineContainer.appendChild(memberCard);
    });
}

// Button events
showOnlineBtn.addEventListener('click', () => {
    currentMode = 'online';
    setActiveButton('online');
    renderMembers();
});

showAllBtn.addEventListener('click', () => {
    currentMode = 'all';
    setActiveButton('all');
    renderMembers();
});

// Set default active button on page load
setActiveButton(currentMode);

// Start listening on page load
listenUsers();

// Run on page load
loadUpcomingEvents();
loadRecentUsers();
renderNewMembers();
loadStats();

document.getElementById("openAboutEditor").addEventListener("click", openAboutEditor);
document.getElementById("saveAboutContent").addEventListener("click", saveAboutContent);