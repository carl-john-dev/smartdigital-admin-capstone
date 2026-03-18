<?php
    // require_once 'auth_guard.php';
    // requireAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - NFC Card Orders</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Animate.css for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="icon" href="CBOC LOGO.jpg"/>
    <script type="text/javascript"
            src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js">
    </script>
    <script type="text/javascript">
    (function(){
        emailjs.init({
            publicKey: "z8UU6_zG2JRpqhc-m",
        });
    })();
    </script>
    <script>
        console.log("The auto-email sender only works if the receiver E-mail is a valid existing E-mail.\nOtherwise, it returns an error.\n\n  -Arjon");
    </script>
</head>
<style>
    /* Add these styles to your existing style.css */

    /* Three Dots Menu Styles */
    .three-dots-menu {
        position: relative;
        display: inline-block;
        margin-right: 15px;
    }

    .dots-button {
        background: transparent;
        border: none;
        color: var(--text-color);
        font-size: 1.5rem;
        cursor: pointer;
        padding: 5px 10px;
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    .dots-button:hover {
        background: rgba(67, 97, 238, 0.1);
        color: var(--primary);
    }

    .dropdown-menu-custom {
        position: absolute;
        top: 100%;
        right: 0;
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        min-width: 200px;
        z-index: 1000;
        display: none;
        margin-top: 5px;
    }

    .dropdown-menu-custom.show {
        display: block;
        animation: fadeIn 0.2s ease;
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 15px;
        color: var(--text-color);
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
        border: none;
        background: transparent;
        width: 100%;
        text-align: left;
        font-size: 0.95rem;
    }

    .dropdown-item:hover {
        background: rgba(67, 97, 238, 0.1);
        color: var(--primary);
    }

    .dropdown-item i {
        width: 20px;
        color: var(--primary);
    }

    .dropdown-divider {
        height: 1px;
        background: var(--border-color);
        margin: 5px 0;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Enhanced Stat Cards */
    .stat-card {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .stat-content {
        flex: 1;
    }

    /* Card-specific colors */
    .processed-card .stat-icon {
        background: rgba(40, 167, 69, 0.15);
        color: #28a745;
    }

    .inprocess-card .stat-icon {
        background: rgba(0, 123, 255, 0.15);
        color: #007bff;
    }

    .ready-pickup-card .stat-icon {
        background: rgba(255, 193, 7, 0.15);
        color: #ffc107;
    }

    /* NFC Card Styles */
    .nfc-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 0.7rem;
        margin-left: 8px;
    }

    /* Pick Up Detail Section */
    .pickup-detail-section {
        margin-top: 20px;
    }

    .pickup-items-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .pickup-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: var(--card-bg, #f8f9fa);
        border-radius: 10px;
        transition: all 0.3s ease;
        border-left: 4px solid #ffc107;
    }

    .dark-mode .pickup-item {
        background: #2d2d2d;
    }

    .pickup-item:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .pickup-item-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        background: rgba(255, 193, 7, 0.15);
        color: #ffc107;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .pickup-item-details {
        flex: 1;
    }

    .pickup-item-details h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
    }

    .pickup-item-details p {
        margin: 5px 0 0;
        font-size: 0.85rem;
        color: var(--text-muted, #6c757d);
    }

    .pickup-badge {
        background: #ffc107;
        color: #000;
        font-weight: 500;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
    }

    /* Notification Items */
    .notification-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        border-bottom: 1px solid var(--border-color, #dee2e6);
    }

    .dark-mode .notification-item {
        border-bottom-color: #404040;
    }

    .notification-item:last-child {
        border-bottom: none;
    }

    .notification-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
    }

    .notification-content {
        flex: 1;
    }

    .notification-content h6 {
        margin: 0;
        font-size: 0.95rem;
        font-weight: 600;
    }

    .notification-content p {
        margin: 2px 0;
        font-size: 0.85rem;
        color: var(--text-muted, #6c757d);
    }

    /* Pickup Quick Section */
    .pickup-quick-section {
        background: var(--section-bg, white);
    }

    /* Table enhancements */
    .table {
        margin-bottom: 0;
    }

    .table th {
        border-top: none;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Notification helper */
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

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .stats-container {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .pickup-item {
            flex-direction: column;
            text-align: center;
        }
        
        .pickup-item-details {
            text-align: center;
        }
    }
</style>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-tachometer-alt"> </i>CBOC</h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
            <li><a href="members.php"><i class="fas fa-users"></i><span>Users</span></a></li>
            <li><a href="location.php"><i class="fas fa-map-marked-alt"></i><span>Location</span></a></li>
            <li><a href="e-portfolio.php"><i class="fas fa-id-card"></i><span>E-Portfolio</span></a></li>
            <li><a href="calendar.php"><i class="fas fa-calendar"></i><span>Calendar</span></a></li>
            <li><a href="rsvptracker.php"><i class="fas fa-calendar-check"></i><span>RSVP Tracker</span></a></li>
            <li><a href="request.php"><i class="fas fa-clipboard-list"></i><span>Approvals</span></a></li>
            <li><a href="#" class="active"><i class="fas fa-shopping-cart"></i><span>NFC Card</span></a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar with Three Dots Menu -->
        <div class="top-bar">
            <h1><i class="fas fa-credit-card me-2"></i>NFC Card Orders Dashboard</h1>
            <div class="d-flex align-items-center">
                <!-- Three Dots Menu -->
                <div class="three-dots-menu">
                    <button class="dots-button" id="dotsMenuBtn">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>
                    <div class="dropdown-menu-custom" id="dotsDropdown">
                        <a href="archive.php" class="dropdown-item">
                            <i class="fas fa-archive"></i> Archive
                        </a>
                        <a href="logs.php" class="dropdown-item">
                            <i class="fas fa-history"></i> Activity Logs
                        </a>
                        <div class="dropdown-divider"></div>
                        <button class="dropdown-item" onclick="exportCardOrders()">
                            <i class="fas fa-download"></i> Export Orders
                        </button>
                        <button class="dropdown-item" onclick="printOrders()">
                            <i class="fas fa-print"></i> Print Summary
                        </button>
                        <div class="dropdown-divider"></div>
                        <button class="dropdown-item" onclick="refreshDashboard()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                        <button class="dropdown-item" onclick="showCardHelp()">
                            <i class="fas fa-question-circle"></i> Help
                        </button>
                    </div>
                </div>
                <div class="user-info">
                    <div class="user-avatar">AD</div>
                    <div>
                        <div class="fw-bold">Admin User</div>
                        <small class="text-muted">Administrator</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section for NFC Cards -->
        <div class="stats-container">
            <div class="stat-card processed-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number processedCards">???</div>
                    <div class="stat-label">Cards Processed</div>
                </div>
            </div>
            
            <div class="stat-card inprocess-card">
                <div class="stat-icon">
                    <i class="fas fa-sync-alt fa-spin"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number activeCards">???</div>
                    <div class="stat-label">Cards in Process</div>
                </div>
            </div>
            
            <div class="stat-card ready-pickup-card">
                <div class="stat-icon">
                    <i class="fas fa-box-open"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number readyCards">???</div>
                    <div class="stat-label">Cards Ready for Pick Up</div>
                </div>
            </div>
        </div>

        <!-- Ready for Pick Up Detailed Section - NFC Cards -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="dashboard-section pickup-detail-section">
                    <h3 class="section-title">
                        <i class="fas fa-box-open text-warning"></i> NFC Cards Ready for Pick Up
                    </h3>
                    <div class="pickup-items-container" id="pickupItemsContainer">
                        <!-- <div class="pickup-item">
                            <div class="pickup-item-icon">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div class="pickup-item-details">
                                <h5>John Doe - NFC Card</h5>
                                <p><i class="fas fa-hashtag"></i> Card ID: NFC-2024-001 | <i class="fas fa-clock"></i> Ready since: Today, 10:30 AM</p>
                                <span class="badge pickup-badge">Ready for Pickup</span>
                            </div>
                        </div>
                        
                        <div class="pickup-item">
                            <div class="pickup-item-icon">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div class="pickup-item-details">
                                <h5>Jane Smith - NFC Card</h5>
                                <p><i class="fas fa-hashtag"></i> Card ID: NFC-2024-045 | <i class="fas fa-clock"></i> Ready since: Yesterday, 3:15 PM</p>
                                <span class="badge pickup-badge">Ready for Pickup</span>
                            </div>
                        </div>
                        
                        <div class="pickup-item">
                            <div class="pickup-item-icon">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div class="pickup-item-details">
                                <h5>Mike Johnson - NFC Card</h5>
                                <p><i class="fas fa-hashtag"></i> Card ID: NFC-2024-089 | <i class="fas fa-clock"></i> Ready since: Mar 5, 2024</p>
                                <span class="badge pickup-badge">Ready for Pickup</span>
                            </div>
                        </div>
                        
                        <div class="pickup-item">
                            <div class="pickup-item-icon">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div class="pickup-item-details">
                                <h5>Sarah Williams - NFC Card</h5>
                                <p><i class="fas fa-hashtag"></i> Card ID: NFC-2024-156 | <i class="fas fa-clock"></i> Ready since: Mar 4, 2024</p>
                                <span class="badge pickup-badge">Ready for Pickup</span>
                            </div>
                        </div> -->
                    </div>
                    <div class="see-all">
                        <a href="nfc-orders.php?filter=ready">View All Ready NFC Cards <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Recently Processed NFC Cards -->
                <div class="dashboard-section">
                    <h3 class="section-title mb-0">
                        <i class="fas fa-check-circle text-success"></i> Recently Processed NFC Cards
                    </h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Card ID</th>
                                <th>Processed Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="processedCardsTable">
                            <!-- <tr>
                                <td>Jonatan</td>
                                <td><code>NFC-2024-234</code></td>
                                <td>Mar 6, 2024</td>
                                <td><span class="status status-resolve">Processed</span></td>
                            </tr>
                            <tr>
                                <td>Halley</td>
                                <td><code>NFC-2024-235</code></td>
                                <td>Mar 6, 2024</td>
                                <td><span class="status status-resolve">Processed</span></td>
                            </tr>
                            <tr>
                                <td>Nari</td>
                                <td><code>NFC-2024-236</code></td>
                                <td>Mar 5, 2024</td>
                                <td><span class="status status-resolve">Processed</span></td>
                            </tr>
                            <tr>
                                <td>Austin</td>
                                <td><code>NFC-2024-237</code></td>
                                <td>Mar 5, 2024</td>
                                <td><span class="status status-resolve">Processed</span></td>
                            </tr>
                            <tr>
                                <td>Isabelle</td>
                                <td><code>NFC-2024-238</code></td>
                                <td>Mar 4, 2024</td>
                                <td><span class="status status-resolve">Processed</span></td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>

                <!-- NFC Cards In Process -->
                <div class="dashboard-section">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h3 class="section-title mb-0">
                            <i class="fas fa-sync-alt fa-spin text-primary"></i> NFC Cards in Process
                        </h3>
                        <!-- <button class="btn btn-primary btn-sm" onclick="openCreateNFCModal()">
                            <i class="fas fa-plus"></i> Create NFC
                        </button> -->
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Card ID</th>
                                <th>Started</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="activeCardsTable">
                            <!-- <tr>
                                <td>Austin</td>
                                <td><code>NFC-2024-239</code></td>
                                <td>Mar 6, 2024</td>
                                <td><span class="status status-pending">Encoding</span></td>
                            </tr>
                            <tr>
                                <td>Isabelle</td>
                                <td><code>NFC-2024-240</code></td>
                                <td>Mar 5, 2024</td>
                                <td><span class="status status-pending">Printing</span></td>
                            </tr>
                            <tr>
                                <td>David</td>
                                <td><code>NFC-2024-241</code></td>
                                <td>Mar 4, 2024</td>
                                <td><span class="status status-pending">Quality Check</span></td>
                            </tr>
                            <tr>
                                <td>Robert Chen</td>
                                <td><code>NFC-2024-242</code></td>
                                <td>Mar 4, 2024</td>
                                <td><span class="status status-pending">Encoding</span></td>
                            </tr>
                            <tr>
                                <td>Emily Brown</td>
                                <td><code>NFC-2024-243</code></td>
                                <td>Mar 3, 2024</td>
                                <td><span class="status status-pending">Printing</span></td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right Column - NFC Card Notifications -->
            <div class="col-lg-4">
                <!-- Quick Pick Up Notifications -->
                <div class="dashboard-section pickup-quick-section">
                    <h3 class="section-title"><i class="fas fa-bell"></i> Card Pick Up Notifications</h3>

                    <div id="notificationsContainer">
                        <!-- Notifications will be inserted here -->
                    </div>

                    <!-- <div class="notification-item">
                        <div class="notification-icon bg-warning">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div class="notification-content">
                            <h6>Robert Chen</h6>
                            <p>NFC card ready for pickup</p>
                            <small class="text-muted">2 hours ago</small>
                        </div>
                    </div>
                    
                    <div class="notification-item">
                        <div class="notification-icon bg-info">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div class="notification-content">
                            <h6>Emily Brown</h6>
                            <p>NFC card ready for pickup</p>
                            <small class="text-muted">5 hours ago</small>
                        </div>
                    </div>
                    
                    <div class="notification-item">
                        <div class="notification-icon bg-success">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div class="notification-content">
                            <h6>Michael Lee</h6>
                            <p>NFC card ready for pickup</p>
                            <small class="text-muted">Yesterday</small>
                        </div>
                    </div> -->
                    
                    <div class="see-all">
                        <a href="nfc-orders.php?filter=notifications">View All Notifications <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Card Production Summary -->
                <div class="dashboard-section">
                    <h3 class="section-title"><i class="fas fa-chart-pie"></i> Card Production Summary</h3>
                    
                    <div class="mt-3 p-3 bg-light rounded">
                        <div class="d-flex justify-content-between">
                            <span>Total Cards Ordered:</span>
                            <span class="fw-bold totalCards">???</span>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span>Ready for Pickup:</span>
                            <span class="fw-bold text-warning readyCards">???</span>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span>In Process:</span>
                            <span class="fw-bold text-primary activeCards">???</span>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span>Processed:</span>
                            <span class="fw-bold text-success processedCards">???</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dark Mode Toggle Button -->
    <button class="dark-mode-toggle" id="darkModeToggle">
        <i class="fas fa-moon" id="darkModeIcon"></i>
    </button>

    <!-- Create NFC Modal -->
    <div class="modal fade" id="createNFCModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Create NFC Card</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label class="form-label">Member Name</label>
                    <input type="text" id="nfcMemberName" class="form-control" placeholder="Enter member name">
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" onclick="createNFCCard()">Create Card</button>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript for Interactive Elements -->
    <script type="module">
        import { db } from './Firebase/firebase_conn.js';
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
                 limit,
                 doc 
        } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-firestore.js";

        document.addEventListener('DOMContentLoaded', function() {
            // Three Dots Menu Functions
            window.exportCardOrders = function() {
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

            window.printOrders = function() {
                window.print();
            };

            window.refreshDashboard = function() {
                location.reload();
            };

            window.showCardHelp = function() {
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

                    snapshot.forEach(docSnap => {
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

                        const row = `
                            <tr>
                                <td>${name}</td>
                                <td><code>${cardId}</code></td>
                                <td>${processedDate}</td>
                                <td><span class="status status-resolve">${status}</span></td>
                                <td>
                                    <button class="btn btn-sm btn-success"
                                        onclick="markCardReady('${docSnap.ref.path}', this)">
                                        <i class="fas fa-check"></i> Mark as Ready
                                    </button>
                                </td>
                            </tr>
                        `;

                        tableBody.innerHTML += row;
                    });

                    if (tableBody.innerHTML === "") {
                        tableBody.innerHTML = `
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    No processed NFC cards
                                </td>
                            </tr>
                        `;
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

                    snapshot.forEach(docSnap => {
                        const data = docSnap.data();

                        if (data.status === "Ready for Pickup") return;

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

                        const row = `
                            <tr>
                                <td>${name}</td>
                                <td><code>${cardId}</code></td>
                                <td>${startedDate}</td>
                                <td><span class="status status-pending">${status}</span></td>
                                <td>
                                    <button class="btn btn-sm btn-success"
                                        onclick="markCardProcessed('${docSnap.ref.path}', this)">
                                        <i class="fas fa-check"></i> Process
                                    </button>
                                </td>
                            </tr>
                        `;

                        tableBody.innerHTML += row;
                    });

                    if (tableBody.innerHTML === "") {
                        tableBody.innerHTML = `
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    No active NFC card processes
                                </td>
                            </tr>
                        `;
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
            window.markCardProcessed = async function(cardPath, btn) {
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
            window.markCardReady = async function(cardPath, btn) {
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
    </script>
</body>
</html>