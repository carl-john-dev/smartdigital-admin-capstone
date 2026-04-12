/**
 * Dahil iba yung structure, di to pwede ilagay sa backend.js
 * -Arjon
 */
import { db } from '../Firebase/firebase_conn.js';
import { collection, 
            collectionGroup,
            query, 
            where, 
            onSnapshot, 
            orderBy, 
            getDoc, 
            addDoc, 
            updateDoc, 
            setDoc,
            getDocs, 
            serverTimestamp,
            doc 
} from "https://www.gstatic.com/firebasejs/12.9.0/firebase-firestore.js";

document.addEventListener('DOMContentLoaded', function() {
    // Three Dots Menu Functions
    function exportCardOrders() {
        // Sample data - in real app, this would come from your database
        const orders = [
            { member: 'John Doe', cardId: 'NFC-2024-001', status: 'Ready for Pickup', readySince: 'Today, 10:30 AM' },
            { member: 'Jane Smith', cardId: 'NFC-2024-045', status: 'Ready for Pickup', readySince: 'Yesterday, 3:15 PM' },
            { member: 'Mike Johnson', cardId: 'NFC-2024-089', status: 'Ready for Pickup', readySince: 'Mar 5, 2024' },
            { member: 'Jonatan', cardId: 'NFC-2024-234', status: 'Processed', processedDate: 'Mar 6, 2024' },
            { member: 'Halley', cardId: 'NFC-2024-235', status: 'Processed', processedDate: 'Mar 6, 2024' }
        ];
        
        const dataStr = JSON.stringify(orders, null, 2);
        const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
        const exportFileDefaultName = 'cboc-nfc-orders-export.json';
        
        const linkElement = document.createElement('a');
        linkElement.setAttribute('href', dataUri);
        linkElement.setAttribute('download', exportFileDefaultName);
        linkElement.click();
        
        showNotification('NFC card orders exported successfully!', 'success');
    };

    function printOrders() {
        window.print();
    };

    function refreshDashboard() {
        location.reload();
    };

    function showCardHelp() {
        alert(`
NFC Card Dashboard Help:
- Cards Processed: Completed NFC cards
- Cards in Process: Currently being manufactured
- Cards Ready for Pickup: Available for collection
- Click on pickup items to view details
- Use Export to download orders list
- Notifications show recent pickup alerts
        `);
    };

    document.getElementById("exportCardOrders").addEventListener("click", exportCardOrders);
    document.getElementById("printOrders").addEventListener("click", printOrders);
    document.getElementById("refreshDashboard").addEventListener("click", refreshDashboard);
    document.getElementById("showCardHelp").addEventListener("click", showCardHelp);

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

    // Add subtle animation to stat cards on page load
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.add('animate__animated', 'animate__fadeInUp');
    });
    
    // Add hover animation for pickup items
    const pickupItems = document.querySelectorAll('.pickup-item');
    pickupItems.forEach((item, index) => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(5px)';
            this.style.transition = 'all 0.3s ease';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
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
        notification.innerHTML = `<i class="fas ${icons[type]}"></i><span>${message}</span>`;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Add notification styles if not already present
    if (!document.querySelector('#notification-styles')) {
        const style = document.createElement('style');
        style.id = 'notification-styles';
        style.textContent = `
            .notification {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                min-width: 300px;
                animation: slideIn 0.3s ease;
                border-radius: 8px;
                padding: 15px 20px;
                color: white;
                display: flex;
                align-items: center;
                gap: 10px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            }
            .notification.success { background: linear-gradient(135deg, #10b981, #059669); }
            .notification.error { background: linear-gradient(135deg, #ef4444, #dc2626); }
            .notification.info { background: linear-gradient(135deg, #3b82f6, #2563eb); }
            .notification.warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
            
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

    // Pulls data from DB about NFC Card Ready to pick up section
    function loadReadyPickupCards() {
        const container = document.getElementById("pickupItemsContainer");

        if (!container) return;

        const q = query(
            collectionGroup(db, "nfc_card"),
            where("status", "==", "Ready for Pickup"),
            orderBy("date_ready_pickup", "desc")
        );

        onSnapshot(q, (snapshot) => {
            container.innerHTML = "";

            snapshot.forEach(docSnap => {
                const data = docSnap.data();

                const name = data.member_name || "Unknown Member";
                const cardId = data.card_id || "N/A";
                const readyDate = data.date_ready_pickup;

                let formattedDate = "Unknown";

                if (readyDate) {
                    const date = readyDate.toDate();
                    formattedDate = date.toLocaleString("en-US", {
                        month: "short",
                        day: "numeric",
                        year: "numeric",
                        hour: "numeric",
                        minute: "2-digit"
                    });
                }

                const cardHTML = `
                    <div class="pickup-item">
                        <div class="pickup-item-icon">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div class="pickup-item-details">
                            <h5>${name} - NFC Card</h5>
                            <p>
                                <i class="fas fa-hashtag"></i> Card ID: ${cardId} |
                                <i class="fas fa-clock"></i> Ready since: ${formattedDate}
                            </p>
                            <span class="badge pickup-badge">Ready for Pickup</span>
                        </div>
                    </div>
                `;

                container.innerHTML += cardHTML;
            });

            if (container.innerHTML === "") {
                container.innerHTML = `
                    <div class="text-center text-muted">
                        No NFC cards ready for pickup
                    </div>
                `;
            }
        });
    }

    // Pulls data from DB about NFC Card Processed section
    function loadProcessedCards() {
        const tableBody = document.getElementById("processedCardsTable");
        if (!tableBody) return;

        const q = query(
            collectionGroup(db, "nfc_card"),
            where("status", "==", "Processed"),
            orderBy("date_processed", "desc")
        );

        onSnapshot(q, (snapshot) => {
            tableBody.innerHTML = "";

            let hasData = false;

            snapshot.forEach(docSnap => {
                hasData = true;

                const data = docSnap.data();

                const name = data.member_name || "Unknown";
                const cardId = data.card_id || "N/A";
                const status = data.status || "Processed";

                let processedDate = "Unknown";

                if (data.date_processed) {
                    const date = data.date_processed.toDate();
                    processedDate = date.toLocaleString("en-US", {
                        month: "short",
                        day: "numeric",
                        year: "numeric",
                        hour: "numeric",
                        minute: "2-digit"
                    });
                }

                const tr = document.createElement("tr");

                // NAME
                const tdName = document.createElement("td");
                tdName.textContent = name;

                // CARD ID
                const tdCard = document.createElement("td");
                const code = document.createElement("code");
                code.textContent = cardId;
                tdCard.appendChild(code);

                // DATE
                const tdDate = document.createElement("td");
                tdDate.textContent = processedDate;

                // STATUS
                const tdStatus = document.createElement("td");
                const span = document.createElement("span");
                span.className = "status status-resolve";
                span.textContent = status;
                tdStatus.appendChild(span);

                // ACTION
                const tdAction = document.createElement("td");
                const btn = document.createElement("button");
                btn.className = "btn btn-sm btn-success";

                const icon = document.createElement("i");
                icon.className = "fas fa-check";

                btn.append(icon, " Mark as Ready");

                btn.addEventListener("click", () => {
                    markCardReady(docSnap.ref.path, btn);
                });

                tdAction.appendChild(btn);

                tr.append(tdName, tdCard, tdDate, tdStatus, tdAction);
                tableBody.appendChild(tr);
            });

            if (!hasData) {
                const tr = document.createElement("tr");
                const td = document.createElement("td");

                td.colSpan = 5;
                td.className = "text-center text-muted";
                td.textContent = "No processed NFC cards";

                tr.appendChild(td);
                tableBody.appendChild(tr);
            }
        });
    }

    // Pulls data from DB about NFC Card In Process section
    function loadActiveCards() {
        const tableBody = document.getElementById("activeCardsTable");
        if (!tableBody) return;

        const q = query(
            collectionGroup(db, "nfc_card"),
            where("status", "in", ["Pending", "In Processing"]),
            orderBy("date_started", "desc")
        );

        onSnapshot(q, (snapshot) => {
            tableBody.innerHTML = "";

            let hasData = false;

            snapshot.forEach(docSnap => {
                const data = docSnap.data();

                if (data.status === "Ready for Pickup") return;

                hasData = true;

                const name = data.member_name || "Unknown";
                const cardId = data.card_id || "N/A";
                const status = data.status || "Pending";

                let startedDate = "Unknown";

                if (data.date_started) {
                    const date = data.date_started.toDate();
                    startedDate = date.toLocaleString("en-US", {
                        month: "short",
                        day: "numeric",
                        year: "numeric",
                        hour: "numeric",
                        minute: "2-digit"
                    });
                }

                const tr = document.createElement("tr");

                // NAME
                const tdName = document.createElement("td");
                tdName.textContent = name;

                // CARD ID
                const tdCard = document.createElement("td");
                const code = document.createElement("code");
                code.textContent = cardId;
                tdCard.appendChild(code);

                // DATE
                const tdDate = document.createElement("td");
                tdDate.textContent = startedDate;

                // STATUS
                const tdStatus = document.createElement("td");
                const span = document.createElement("span");
                span.className = "status status-pending";
                span.textContent = status;
                tdStatus.appendChild(span);

                // ACTION
                const tdAction = document.createElement("td");
                const btn = document.createElement("button");
                btn.className = "btn btn-sm btn-success";

                const icon = document.createElement("i");
                icon.className = "fas fa-check";

                btn.append(icon, " Process");

                btn.addEventListener("click", () => {
                    markCardProcessed(docSnap.ref.path, btn);
                });

                tdAction.appendChild(btn);

                tr.append(tdName, tdCard, tdDate, tdStatus, tdAction);
                tableBody.appendChild(tr);
            });

            if (!hasData) {
                const tr = document.createElement("tr");
                const td = document.createElement("td");

                td.colSpan = 4;
                td.className = "text-center text-muted";
                td.textContent = "No active NFC card processes";

                tr.appendChild(td);
                tableBody.appendChild(tr);
            }
        });
    }

    // Update Card Counts based on Firebase DB
    function loadNFCCardCounts() {
        const cardsRef = collectionGroup(db, "nfc_card");

        onSnapshot(cardsRef, (snapshot) => {
            let total = 0;
            let ready = 0;
            let processed = 0;
            let active = 0;

            snapshot.forEach(doc => {
                total++;
                const data = doc.data();
                const status = data.status;

                if (status === "Ready for Pickup") {
                    ready++;
                }
                else if (status === "Processed") {
                    processed++;
                }
                else {
                    active++;
                }
            });

            // Update dashboard UI
            document.querySelectorAll(".totalCards").forEach(el => {
                el.textContent = total;
            });
            document.querySelectorAll(".readyCards").forEach(el => {
                el.textContent = ready;
            });
            document.querySelectorAll(".processedCards").forEach(el => {
                el.textContent = processed;
            });
            document.querySelectorAll(".activeCards").forEach(el => {
                el.textContent = active;
            });
        });
    }

    // Open Modal for Create NFC
    window.openCreateNFCModal = function () {
        const modal = new bootstrap.Modal(document.getElementById("createNFCModal"));
        modal.show();
    }

    // Mark a card as Processed
    async function markCardProcessed(cardPath, btn) {
        btn.disabled = true;
        btn.innerHTML = "Processing...";

        try {

            const cardRef = doc(db, cardPath);

            await updateDoc(cardRef, {
                status: "Processed",
                date_processed: serverTimestamp()
            });

            btn.innerHTML = "Processed";

        } catch(e) {
            console.error(e);
            btn.disabled = false;
            btn.innerHTML = "Process";
        }
    }

    // Mark a card as Ready for Pickup
    async function markCardReady(cardPath, btn) {
        btn.disabled = true;
        btn.innerHTML = "Processing...";

        try {

            const cardRef = doc(db, cardPath);

            await updateDoc(cardRef, {
                status: "Ready for Pickup",
                date_ready_pickup: serverTimestamp()
            });

            // Get card data after update
            const cardSnap = await getDoc(cardRef);
            const cardData = cardSnap.data();

            // Get the user's email
            const userId = cardPath.split('/')[1]; // users/{userId}/nfc_card/{cardDoc}
            const userSnap = await getDoc(doc(db, "users", userId));
            const userData = userSnap.data();
            const email = userData?.email;

            if (email) {
                // Send email via EmailJS (variable names must match template exactly)
                emailjs.send("service_1s1jyud", "template_pz1xyg8", {
                    name: userData.name || "Member",
                    email: email,
                    card_id: cardData.card_id
                })
                .then(() => {
                    console.log(`Email sent to ${email}`);
                }, (err) => {
                    console.error("EmailJS error:", err);
                });
            }

            // 5️⃣ Add notification to user_notifications collection
            await addDoc(collection(db, "user_notifications"), {
                user_id: userId,
                name: userData.name,
                type: "nfc_ready_to_pickup",
                createdAt: serverTimestamp(),
                card_id: cardData.card_id
            });

            btn.innerHTML = "Ready";

        } catch(e) {
            console.error(e);
            btn.disabled = false;
            btn.innerHTML = "Mark as Ready";
        }
    }

    // Create an NFC Card
    async function createNFCCardsForUsers() {
        const usersSnapshot = await getDocs(collection(db, "users"));

        for (const userDoc of usersSnapshot.docs) {
            const userId = userDoc.id;
            const userData = userDoc.data();

            const cardRef = doc(db, "users", userId, "nfc_card", "card");
            const cardSnap = await getDoc(cardRef);

            // Skip if card already exists
            if (cardSnap.exists()) {
                // console.log(`User ${userId} already has NFC card`);
                continue;
            }

            // Generate card ID
            const cardId = await generateUniqueCardId();

            await setDoc(cardRef, {
                card_id: cardId,
                member_name: userData.name || "Unknown",
                status: "In Processing",
                date_started: serverTimestamp()
            });

            // console.log(`Created NFC card for ${userData.name}`);
        }
    }

    // Generates unique ID for each NFC Card
    async function generateUniqueCardId() {
        const year = new Date().getFullYear();

        const random16 = Math.floor(
            1000000000000000 + Math.random() * 9000000000000000
        );

        return `NFC-${year}-${random16}`;
    }

    function loadUserNotifications() {
        const container = document.getElementById("notificationsContainer");
        if (!container) return;

        // Query: user_notifications where type == "nfc_ready_to_pickup", newest first
        const q = query(
            collection(db, "user_notifications"),
            where("type", "==", "nfc_ready_to_pickup"),
            orderBy("createdAt", "desc")
        );

        onSnapshot(q, (snapshot) => {
            container.innerHTML = ""; // clear old notifications

            if (snapshot.empty) {
                container.innerHTML = `<p class="text-muted">No new notifications</p>`;
                return;
            }

            snapshot.forEach(docSnap => {
                const data = docSnap.data();
                const name = data.name || "Member";
                const createdAt = data.createdAt ? data.createdAt.toDate() : null;
                const timeString = createdAt ? timeAgo(createdAt) : "";

                // Notification item HTML
                const notifHTML = `
                    <div class="notification-item">
                        <div class="notification-icon bg-warning">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div class="notification-content">
                            <h6>${name}</h6>
                            <p>NFC card ready for pickup</p>
                            <small class="text-muted">${timeString}</small>
                        </div>
                    </div>
                `;

                container.innerHTML += notifHTML;
            });
        });
    }

    function timeAgo(date) {
        const now = new Date();
        const diff = now - date; // milliseconds

        const seconds = Math.floor(diff / 1000);
        const minutes = Math.floor(seconds / 60);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);

        if (days > 1) return `${days} days ago`;
        if (days === 1) return "Yesterday";
        if (hours > 1) return `${hours} hours ago`;
        if (hours === 1) return "1 hour ago";
        if (minutes > 1) return `${minutes} minutes ago`;
        if (minutes === 1) return "1 minute ago";
        return "Just now";
    }

    // Initialize
    createNFCCardsForUsers();
    loadActiveCards();
    loadNFCCardCounts();
    loadProcessedCards();
    loadReadyPickupCards();
    loadUserNotifications();
});
