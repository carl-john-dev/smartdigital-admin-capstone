/**
 * Dahil iba yung structure, di to pwede ilagay sa backend.js
 * -Arjon
 */
import { db } from "../Firebase/firebase_conn.js";
import {
    collection,
    query,
    where,
    updateDoc,
    deleteDoc,
    doc,
    getDoc,
    onSnapshot
} from "https://www.gstatic.com/firebasejs/12.9.0/firebase-firestore.js";

// async function testFirestore() {
//     try {
//         const snapshot = await getDocs(collection(db, "users"));
//         console.log("Number of documents in 'users':", snapshot.size);
//         snapshot.forEach(docSnap => {
//             console.log(docSnap.id, docSnap.data());
//         });
//     } catch (error) {
//         console.error("Firestore error:", error);
//     }
// }

// testFirestore();

document.addEventListener('DOMContentLoaded', function() {
    // Three Dots Menu Functions
    window.exportRequests = function() {
        const requests = window.requests || [];
        if (requests.length === 0) {
            showNotification('No requests to export', 'warning');
            return;
        }
        
        const dataStr = JSON.stringify(requests, null, 2);
        const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
        const exportFileDefaultName = 'cboc-requests-export.json';
        
        const linkElement = document.createElement('a');
        linkElement.setAttribute('href', dataUri);
        linkElement.setAttribute('download', exportFileDefaultName);
        linkElement.click();
        
        showNotification('Approvals exported successfully!', 'success');
    };

    window.printRequests = function() {
        window.print();
    };

    window.refreshRequests = function() {
        location.reload();
    };

    window.showRequestHelp = function() {
        alert(`
Approval Management Help:
- Click "New Approval" to create a Approval
- Use Edit button to modify Approval details
- Use Accept button to approve pending Approvals
- Use Delete button to remove Approvals
- Filter by status using the Filter button
- Stats cards show real-time Approval counts
        `);
    };

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

    // Initialize requests data
    let requests = JSON.parse(localStorage.getItem('cbocRequests')) || [
        {
            id: 1,
            name: 'Lucia Merry',
            type: 'Membership Application',
            email: 'mistprod@gmail.com',
            operation: 'Create',
            status: 'Pending',
            description: 'New membership application for THE MIST COP.'
        },
        {
            id: 2,
            name: 'Sabrina Tan',
            type: 'Event Registration',
            email: 'sabrina@realtyvale.com',
            operation: 'Process',
            status: 'Approved',
            description: 'Registration for annual business conference.'
        },
        {
            id: 3,
            name: 'Andy Sewer',
            type: 'Payment Issue',
            email: 'andy@fawcettor.com',
            operation: 'Review',
            status: 'Pending',
            description: 'Invoice payment discrepancy inquiry.'
        },
        {
            id: 4,
            name: 'Shanon Matilda',
            type: 'Account Update',
            email: 'shanon@goldenfruit.com',
            operation: 'Update',
            status: 'Completed',
            description: 'Update company information and contact details.'
        },
        {
            id: 5,
            name: 'Ethan Cravejal',
            type: 'Technical Support',
            email: 'ethan@newcastle.com',
            operation: 'Review',
            status: 'Rejected',
            description: 'Website access issues and password reset.'
        }
    ];

    // Make requests available globally
    window.requests = requests;

    // Render requests table
    let membershipRequests = [];
    let businessRequests = [];
    let eventRequests = [];

    function renderRequests() {
        const tableBody = document.getElementById('requestsTableBody');
        if (!tableBody) return;

        // 🔹 USERS (Membership Approval)
        const usersQuery = query(
            collection(db, "users"),
            where("approved", "==", false)
        );

        onSnapshot(usersQuery, (snapshot) => {
            membershipRequests = snapshot.docs.map(docSnap => ({
                id: docSnap.id,
                type: "Membership Approval",
                username: docSnap.data().username,
                email: docSnap.data().email,
                raw: docSnap.data()
            }));

            updateTable();
        });

        // 🔹 BUSINESSES (Business Approval)
        const businessQuery = query(
            collection(db, "businesses"),
            where("status", "==", "pending") // ⚠️ adjust if needed
        );

        onSnapshot(businessQuery, (snapshot) => {
            businessRequests = snapshot.docs.map(docSnap => ({
                id: docSnap.id,
                type: "Business Approval",
                username: docSnap.data().business_name || "Business",
                email: docSnap.data().email,
                raw: docSnap.data()
            }));

            updateTable();
        });

        // 🔹 EVENTS (Event Approval)
        const eventsQuery = query(
            collection(db, "events"),
            where("approved", "==", false)
        );

        onSnapshot(eventsQuery, (snapshot) => {
            eventRequests = snapshot.docs.map(docSnap => ({
                id: docSnap.id,
                type: "Event Approval",
                username: docSnap.data().title || "Event",
                email: docSnap.data().createdBy || "-",
                raw: docSnap.data()
            }));

            updateTable();
        });

        // 🔁 Combine + Render
        function updateTable() {
            tableBody.innerHTML = "";

            const allRequests = [
                ...membershipRequests,
                ...businessRequests,
                ...eventRequests
            ];

            if (allRequests.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            No pending approvals
                        </td>
                    </tr>
                `;
                return;
            }

            allRequests.forEach(req => {
                const row = document.createElement('tr');

                row.innerHTML = `
                    <td>${req.username ?? '-'}</td>
                    <td>${req.type}</td>
                    <td>${req.raw.createdBy ?? '-'}</td>
                    <td>Create</td>
                    <td><span class="request-status status-pending">Pending</span></td>
                    <td>
                        <!-- <button class="request-action-btn edit" data-id="${req.id}" data-type="${req.type}">
                            <i class="fas fa-edit"></i>
                        </button> -->
                        ${req.type === "Event Approval" ? `
                            <button class="request-action-btn review" data-id="${req.id}" data-type="${req.type}">
                                <i class="fas fa-eye"></i>
                            </button>
                        ` : `
                            <button class="request-action-btn accept" data-id="${req.id}" data-type="${req.type}">
                                <i class="fas fa-check"></i>
                            </button>
                        `}
                        <!-- <button class="request-action-btn delete" data-id="${req.id}" data-type="${req.type}">
                            <i class="fas fa-trash"></i>
                        </button> -->
                    </td>
                `;

                tableBody.appendChild(row);
            });

            attachRequestHandlers();
            updateStats();
        }
    }

    function attachRequestHandlers() {
        document.querySelectorAll('.request-action-btn.edit').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                editRequest(id); // keep your existing logic
            });
        });

        document.querySelectorAll('.request-action-btn.accept').forEach(btn => {
            btn.addEventListener('click', async () => {

                const id = btn.dataset.id;
                const type = btn.dataset.type;

                try {
                    // 🚨 If Event Approval → open review modal first
                    if (type === "Event Approval") {
                        const docRef = doc(db, "events", id);
                        const snap = await getDoc(docRef);

                        if (!snap.exists()) return;

                        const data = snap.data();

                        document.getElementById("reviewTitle").textContent = data.title || "Untitled Event";
                        document.getElementById("reviewDescription").textContent = data.description || "";
                        document.getElementById("reviewDate").textContent = data.date || "";
                        document.getElementById("reviewLocation").textContent = data.location || "";
                        document.getElementById("reviewEmail").textContent = data.email || "";

                        document.getElementById("approveEventBtn").dataset.id = id;
                        document.getElementById("rejectEventBtn").dataset.id = id;

                        new bootstrap.Modal(document.getElementById("eventReviewModal")).show();
                        return; // ⛔ stop normal approval
                    }

                    // 🔀 Normal approvals
                    let ref;
                    let updateData = {};

                    if (type === "Membership Approval") {
                        ref = doc(db, "users", id);
                        updateData = { approved: true };

                    } else if (type === "Business Approval") {
                        ref = doc(db, "businesses", id);
                        updateData = { status: "approved" };
                    }

                    if (!ref) return;
                    await updateDoc(ref, updateData);
                    showNotification('Request approved successfully!', 'success');

                } catch (error) {
                    console.error("Approval error:", error);
                    showNotification('Failed to approve request', 'error');
                }
            });
        });

        document.querySelectorAll('.request-action-btn.review').forEach(btn => {
            btn.addEventListener('click', async () => {
                const id = btn.dataset.id;

                try {
                    const snap = await getDoc(doc(db,"events",id));
                    if(!snap.exists()) return;
                    const data = snap.data();
                    const eventDate = data.date?.toDate().toLocaleDateString() ?? "-";
                    const startTime = formatTime(data.startHour, data.startMinute);
                    const endTime = formatTime(data.endHour, data.endMinute);

                    document.getElementById("reviewTitle").textContent = data.title || "Untitled Event";
                    document.getElementById("reviewDescription").textContent = data.description || "-";
                    document.getElementById("reviewDate").textContent = eventDate;
                    document.getElementById("reviewVenue").textContent = data.venue || "-";
                    document.getElementById("reviewSlots").textContent = data.availableSlots ?? "-";
                    document.getElementById("reviewOrganizer").textContent = data.createdBy ?? "-";
                    document.getElementById("reviewTime").textContent = `${startTime} - ${endTime}`;

                    document.getElementById("approveEventBtn").dataset.id = id;
                    document.getElementById("rejectEventBtn").dataset.id = id;

                    new bootstrap.Modal(
                        document.getElementById("eventReviewModal")
                    ).show();

                } catch(error){
                    console.error(error);
                    showNotification("Failed to load event","error");
                }
            });
        });

        document.querySelectorAll('.request-action-btn.delete').forEach(btn => {
            btn.addEventListener('click', async () => {
                const id = btn.dataset.id;

                if (!confirm("Delete this request?")) return;

                await deleteDoc(
                    doc(db, "users", id)
                );

                showNotification('Request deleted successfully!', 'success');
                renderRequests(); // refresh table
            });
        });
    }

    document.getElementById("approveEventBtn").addEventListener("click", async function(){
        const id = this.dataset.id;

        await updateDoc(doc(db,"events",id),{
            approved:true
        });
        showNotification("Event approved","success");

        bootstrap.Modal.getInstance(
            document.getElementById("eventReviewModal")
        ).hide();
    });

    document.getElementById("approveEventBtn").addEventListener("click", async function(){
        const id = this.dataset.id;

        await updateDoc(doc(db,"events",id),{
            approved:true
        });
        showNotification("Event approved","success");

        bootstrap.Modal.getInstance(
            document.getElementById("eventReviewModal")
        ).hide();
    });

    // Update statistics
    let stats = {
        membershipPending: 0,
        membershipApproved: 0,
        businessPending: 0,
        businessApproved: 0,
        eventPending: 0,
        eventApproved: 0
    };

    function loadRequestStats() {
        // 🔹 USERS (Membership)
        onSnapshot(collection(db, "users"), (snapshot) => {
            let pending = 0;
            let approved = 0;

            snapshot.forEach(doc => {
                const data = doc.data();
                if (data.approved === true) approved++;
                else pending++;
            });

            stats.membershipPending = pending;
            stats.membershipApproved = approved;

            updateStats();
        });

        // 🔹 BUSINESSES
        onSnapshot(collection(db, "businesses"), (snapshot) => {
            let pending = 0;
            let approved = 0;

            snapshot.forEach(doc => {
                const data = doc.data();

                if (data.status === "approved") {
                    approved++;
                } else {
                    pending++; // anything not approved = pending
                }
            });

            stats.businessPending = pending;
            stats.businessApproved = approved;

            updateStats();
        });

        // 🔹 EVENTS
        onSnapshot(collection(db, "events"), (snapshot) => {
            let pending = 0;
            let approved = 0;

            snapshot.forEach(doc => {
                const data = doc.data();

                if (data.approved === true) {
                    approved++;
                } else {
                    pending++;
                }
            });

            stats.eventPending = pending;
            stats.eventApproved = approved;

            updateStats();
        });
    }

    function updateStats() {
        const total =
            stats.membershipPending + stats.membershipApproved +
            stats.businessPending + stats.businessApproved +
            stats.eventPending + stats.eventApproved;

        const pending =
            stats.membershipPending +
            stats.businessPending +
            stats.eventPending;

        const approved =
            stats.membershipApproved +
            stats.businessApproved +
            stats.eventApproved;

        const statEls = document.querySelectorAll('.stat-number');

        if (statEls[0]) statEls[0].textContent = total;
        if (statEls[1]) statEls[1].textContent = pending;
        if (statEls[2]) statEls[2].textContent = approved;
    }
    loadRequestStats();

    // 12 hr format time
    function formatTime(hour = 0, minute = 0) {
        const suffix = hour >= 12 ? "PM" : "AM";
        const hour12 = hour % 12 === 0 ? 12 : hour % 12; // convert 0 or 12 -> 12, others mod 12
        const minuteStr = String(minute).padStart(2, "0");
        return `${hour12}:${minuteStr} ${suffix}`;
    }

    // Edit request
    function editRequest(id) {
        const request = requests.find(r => r.id === id);
        if (request) {
            document.getElementById('requestId').value = request.id;
            document.getElementById('requestName').value = request.name;
            document.getElementById('requestType').value = request.type;
            document.getElementById('requestEmail').value = request.email;
            document.getElementById('requestOperation').value = request.operation;
            document.getElementById('requestStatus').value = request.status;
            document.getElementById('requestDescription').value = request.description || '';

            document.getElementById('requestModalLabel').textContent = 'Edit Request';
            new bootstrap.Modal(document.getElementById('requestModal')).show();
        }
    }

    // Save request (Create/Update)
    document.getElementById('saveRequest').addEventListener('click', function() {
        const id = document.getElementById('requestId').value;
        const name = document.getElementById('requestName').value;
        const type = document.getElementById('requestType').value;
        const email = document.getElementById('requestEmail').value;
        const operation = document.getElementById('requestOperation').value;
        const status = document.getElementById('requestStatus').value;
        const description = document.getElementById('requestDescription').value;

        if (name && type && email && operation && status) {
            if (id) {
                // Update existing request
                const index = requests.findIndex(r => r.id === parseInt(id));
                if (index !== -1) {
                    requests[index] = {
                        ...requests[index],
                        name,
                        type,
                        email,
                        operation,
                        status,
                        description
                    };
                    showNotification('Request updated successfully!', 'success');
                }
            } else {
                // Create new request
                const newId = requests.length > 0 ? Math.max(...requests.map(r => r.id)) + 1 : 1;
                requests.push({
                    id: newId,
                    name,
                    type,
                    email,
                    operation,
                    status,
                    description
                });
                showNotification('Request created successfully!', 'success');
            }

            localStorage.setItem('cbocRequests', JSON.stringify(requests));
            
            // Reset form and close modal
            document.getElementById('requestForm').reset();
            bootstrap.Modal.getInstance(document.getElementById('requestModal')).hide();
            
            // Re-render requests
            renderRequests();
        } else {
            showNotification('Please fill in all required fields.', 'warning');
        }
    });

    // New Request button
    document.getElementById('newRequestBtn').addEventListener('click', function() {
        document.getElementById('requestForm').reset();
        document.getElementById('requestId').value = '';
        document.getElementById('requestModalLabel').textContent = 'Add New Request';
    });

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

    // Filter functionality
    document.getElementById('filterRequests').addEventListener('click', function() {
        alert('Filter functionality would be implemented here!');
        // In a real application, this would open a filter modal or sidebar
    });

    // Notification helper
    function showNotification(message, type) {
        const icons = { 
            success: 'fa-check-circle', 
            error: 'fa-exclamation-circle', 
            warning: 'fa-exclamation-triangle', 
            info: 'fa-info-circle' 
        };
        
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--${type === 'success' ? 'success' : type === 'warning' ? 'warning' : type === 'error' ? 'danger' : 'primary'});
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.3s ease;
        `;
        notification.innerHTML = `<i class="fas ${icons[type]}"></i><span>${message}</span>`;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Add CSS for animations if not already present
    if (!document.querySelector('#notification-styles')) {
        const style = document.createElement('style');
        style.id = 'notification-styles';
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    }

    // Initialize the page
    renderRequests();

    // Add subtle animation to stats cards on page load
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
});
