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
  
</head>
<style>
    /* Add these styles to your existing style.css */

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
            <h3><i class="fas fa-tachometer-alt"></i> CBOC NFC Cards</h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
            <li><a href="members.php"><i class="fas fa-users"></i> <span>Members</span></a></li>
            <li><a href="calendar.php"><i class="fas fa-calendar"></i> <span>Calendar</span></a></li>
            <li><a href="location.php"><i class="fas fa-map-marked-alt"></i><span>Location</span></a></li>
            <li><a href="request.php"><i class="fas fa-clipboard-list"></i> <span>Requests</span></a></li>
            <li><a href="ordercard.php"><i class="fas fa-shopping-cart"></i> <span>Order</span></a></li>
            <li><a href="archive.php"><i class="fas fa-archive"></i> <span>Archive</span></a></li>
            <li><a href="logs.php"><i class="fas fa-history"></i> <span>Activity Logs</span></a></li>
            <li><a href="e-portfolio.php"><i class="fas fa-id-card"></i> <span>E-Portfolio</span></a></li>
            <li><a href="rsvptracker.php"><i class="fas fa-calendar-check"></i> <span>RSVP Tracker</span></a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <h1><i class="fas fa-credit-card me-2"></i>NFC Card Orders Dashboard</h1>
            <div class="user-info">
                <div class="user-avatar">AD</div>
                <div>
                    <div class="fw-bold">Admin User</div>
                    <small class="text-muted">Card Administrator</small>
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
                    <div class="stat-number">156</div>
                    <div class="stat-label">Cards Processed</div>
                </div>
            </div>
            
            <div class="stat-card inprocess-card">
                <div class="stat-icon">
                    <i class="fas fa-sync-alt fa-spin"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">23</div>
                    <div class="stat-label">Cards in Process</div>
                </div>
            </div>
            
            <div class="stat-card ready-pickup-card">
                <div class="stat-icon">
                    <i class="fas fa-box-open"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">42</div>
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
                    <div class="pickup-items-container">
                        <div class="pickup-item">
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
                        </div>
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
                    <h3 class="section-title"><i class="fas fa-check-circle text-success"></i> Recently Processed NFC Cards</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Card ID</th>
                                <th>Processed Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
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
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- NFC Cards In Process -->
                <div class="dashboard-section">
                    <h3 class="section-title"><i class="fas fa-sync-alt fa-spin text-primary"></i> NFC Cards in Process</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Card ID</th>
                                <th>Started</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
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
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right Column - NFC Card Notifications -->
            <div class="col-lg-4">
                <!-- Quick Pick Up Notifications -->
                <div class="dashboard-section pickup-quick-section">
                    <h3 class="section-title"><i class="fas fa-bell"></i> Card Pick Up Notifications</h3>
                    
                    <div class="notification-item">
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
                    </div>
                    
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
                            <span class="fw-bold">166</span>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span>Ready for Pickup:</span>
                            <span class="fw-bold text-warning">42</span>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span>In Process:</span>
                            <span class="fw-bold text-primary">23</span>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span>Processed:</span>
                            <span class="fw-bold text-success">101</span>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript for Interactive Elements -->
    <script>
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
        });
    </script>
</body>
</html>