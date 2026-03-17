<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSVP Event Tracker - CBOC Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Link to your existing dashboard CSS - VERY IMPORTANT -->
    <link rel="stylesheet" href="style.css">
    <!-- RSVP Tracker Custom CSS (minimal) -->
    <link rel="icon" type="icon" href="rsvp.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <style>
        /* Use the same CSS variables from your dashboard */
        :root {
            --sidebar-width: 80px;
            --sidebar-expanded-width: 250px;
            --sidebar-bg: #1a1f36;
            --sidebar-color: #a0a7c2;
            --sidebar-hover-bg: rgba(255,255,255,0.1);
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #17a2b8;
            --light-bg: #f8f9fa;
            --dark-bg: #343a40;
            --text-dark: #212529;
            --text-light: #f8f9fa;
        }

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
        
        /* Make sure the main-content matches your dashboard */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            min-height: 100vh;
            background-color: var(--light-bg);
            transition: margin-left 0.3s;
        }
        
        /* Top bar should match your dashboard */
        .top-bar {
            background: white;
            padding: 15px 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        /* Dashboard sections styling to match your existing design */
        .dashboard-section {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .section-title {
            color: var(--text-dark);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        /* Status badges to match your dashboard */
        .status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-declined {
            background: #f8d7da;
            color: #721c24;
        }
        
        /* Table styling to match your dashboard */
        .rsvp-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .rsvp-table th {
            background: var(--primary-color);
            color: white;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
        }
        
        .rsvp-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .rsvp-table tr:hover {
            background-color: rgba(0,0,0,0.02);
        }
        
        /* Stats cards to match your dashboard */
        .rsvp-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 25px 0;
        }
        
        .stat-card-mini {
            background: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .stat-card-mini:hover {
            transform: translateY(-5px);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        /* Controls styling */
        .rsvp-controls {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .search-box {
            flex: 1;
            min-width: 300px;
            position: relative;
        }
        
        .search-box input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            font-size: 0.9rem;
        }
        
        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        
        /* Action buttons */
        .action-btns {
            display: flex;
            gap: 8px;
        }
        
        .btn-sm {
            padding: 5px 10px;
            font-size: 0.85rem;
        }
        
        /* Event details */
        .event-details-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
        }
        
        .editable-event {
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background 0.3s;
            display: inline-block;
        }
        
        .editable-event:hover {
            background: rgba(255,255,255,0.2);
        }
        
        /* Notification styles */
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
        
        /* Dark mode styles */
        .dark-mode .main-content {
            background-color: #1a1a1a;
        }
        
        .dark-mode .top-bar,
        .dark-mode .dashboard-section,
        .dark-mode .stat-card-mini {
            background: #2d2d2d;
            color: white;
        }
        
        .dark-mode .stat-label {
            color: #adb5bd;
        }
        
        .dark-mode .search-box input {
            background: #2d2d2d;
            border-color: #495057;
            color: white;
        }
        
        .dark-mode .rsvp-table th {
            background: #495057;
        }
        
        .dark-mode .rsvp-table td {
            border-color: #495057;
            color: white;
        }
        
        .dark-mode .rsvp-table tr:hover {
            background-color: rgba(255,255,255,0.05);
        }

        .dark-mode .dropdown-menu-custom {
            background: #2d2d2d;
            border-color: #495057;
        }

        .dark-mode .dropdown-item {
            color: #e9ecef;
        }

        .dark-mode .dropdown-item:hover {
            background: rgba(102, 126, 234, 0.2);
        }

        .dark-mode .dropdown-divider {
            background: #495057;
        }
        
        /* Make sure it's responsive */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
            
            .top-bar {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .rsvp-stats {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .search-box {
                min-width: 100%;
            }
            
            .rsvp-controls {
                flex-direction: column;
            }
        }
        
        /* Ensure sidebar is visible */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: var(--dark-bg);
            color: white;
            z-index: 1000;
            overflow-y: auto;
        }
        
        /* Dark mode toggle button */
        .dark-mode-toggle {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            z-index: 1001;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <!-- Sidebar (SAME AS YOUR DASHBOARD) -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-tachometer-alt"> </i>CBOC</h3>
        </div>
        <ul class="sidebar-menu">

            <div class="menu-category">Overview</div>
            <li><a href="dashboard.php"><i class="fas fa-home"></i><span>Dashboard</span></a></li>

            <div class="menu-category">Members</div>
            <li><a href="members.php"><i class="fas fa-users"></i><span>Users</span></a></li>
            <li><a href="location.php"><i class="fas fa-map-marked-alt"></i><span>Location</span></a></li>
            <li><a href="e-portfolio.php"><i class="fas fa-id-card"></i><span>E-Portfolio</span></a></li>

            <div class="menu-category">Events</div>
            <li><a href="calendar.php"><i class="fas fa-calendar"></i><span>Calendar</span></a></li>
            <li><a href="#" class="active"><i class="fas fa-calendar-check"></i><span>RSVP Tracker</span></a></li>

            <div class="menu-category">Operations</div>
            <li><a href="request.php"><i class="fas fa-clipboard-list"></i><span>Approvals</span></a></li>
            <li><a href="ordercard.php"><i class="fas fa-shopping-cart"></i><span>NFC Card</span></a></li>

            <div class="menu-category">Account</div>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>

        </ul>
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Top Bar with Three Dots Menu -->
        <div class="top-bar">
            <div>
                <h1><i class="fas fa-calendar-check"></i> RSVP Event Tracker</h1>
                <p class="text-muted mb-0">Manage event invitations and guest responses</p>
            </div>
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
                        <button class="dropdown-item" onclick="exportRSVPs()">
                            <i class="fas fa-download"></i> Export RSVPs
                        </button>
                        <button class="dropdown-item" onclick="printRSVPList()">
                            <i class="fas fa-print"></i> Print List
                        </button>
                        <div class="dropdown-divider"></div>
                        <button class="dropdown-item" onclick="refreshRSVPs()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                        <button class="dropdown-item" onclick="showRSVPHelp()">
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

        <!-- Upcoming Events Section -->
        <div class="card mt-3 mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-calendar"></i> Upcoming Events</h5>
            </div>

            <div class="card-body" id="eventsListContainer">
                <div class="text-muted text-center py-3">
                    Loading upcoming events...
                </div>
            </div>
        </div>

        <div id="eventContent" style="display:none;">
            <!-- Event Details Card -->
            <div class="event-details-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-2">CBOC Event</h4>
                        <p class="mb-0">
                            <i class="fas fa-calendar-alt"></i> 
                            <span class="editable-event" id="editEventDate" title="Click to edit">
                                Date: <strong id="eventDateDisplay">June 15, 2023</strong>
                            </span>
                            <span class="mx-3">|</span>
                            <i class="fas fa-map-marker-alt"></i> 
                            <span class="editable-event" id="editVenue" title="Click to edit">
                                Venue: <strong id="venueDisplay">Grand Ballroom</strong>
                            </span>
                        </p>
                    </div>
                    <button class="btn btn-light" onclick="addRSVPBtn()">
                        <i class="fas fa-plus"></i> Add RSVP
                    </button>
                </div>
            </div>

            <!-- Stats Section -->
            <!-- <div class="rsvp-stats">
                <div class="stat-card-mini">
                    <div class="stat-number text-success" id="confirmedCount">0</div>
                    <div class="stat-label">Confirmed</div>
                    <i class="fas fa-check-circle mt-2 text-success"></i>
                </div>
                <div class="stat-card-mini">
                    <div class="stat-number text-warning" id="pendingCount">0</div>
                    <div class="stat-label">Pending</div>
                    <i class="fas fa-clock mt-2 text-warning"></i>
                </div>
                <div class="stat-card-mini">
                    <div class="stat-number text-danger" id="declinedCount">0</div>
                    <div class="stat-label">Declined</div>
                    <i class="fas fa-times-circle mt-2 text-danger"></i>
                </div>
                <div class="stat-card-mini">
                    <div class="stat-number text-info" id="plusOneCount">0</div>
                    <div class="stat-label">Plus One</div>
                    <i class="fas fa-user-plus mt-2 text-info"></i>
                </div>
            </div> -->

            <!-- Search and Controls -->
            <div class="dashboard-section">
                <!-- <div class="rsvp-controls">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchInput" placeholder="Search by name or email...">
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary filter-btn active" data-filter="all">All</button>
                        <button class="btn btn-outline-success filter-btn" data-filter="confirmed">Confirmed</button>
                        <button class="btn btn-outline-warning filter-btn" data-filter="pending">Pending</button>
                        <button class="btn btn-outline-danger filter-btn" data-filter="declined">Declined</button>
                        <button class="btn btn-outline-info filter-btn" data-filter="plusOne">Plus One</button>
                    </div>
                </div> -->

                <h2>List of Attendees</h2>
                <!-- RSVP Table -->
                <div class="table-responsive mt-4">
                    <table class="rsvp-table">
                        <thead>
                            <tr>
                                <th>Attendees</th>
                                <th>Email</th>
                                <!-- <th>Status</th> -->
                                <th>Plus One</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <!-- RSVP data will be loaded here -->
                        </tbody>
                    </table>
                </div>

                <!-- Export Controls -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button class="btn btn-outline-secondary" onclick="exportCSV()">
                        <i class="fas fa-file-csv"></i> Export CSV
                    </button>
                    <button class="btn btn-outline-secondary" onclick="exportPDF()">
                        <i class="fas fa-file-code"></i> Export PDF
                    </button>
                    <!-- <button class="btn btn-outline-danger" id="resetDataBtn">
                        <i class="fas fa-trash"></i> Reset Data
                    </button> -->
                </div>

                <!-- Footer Info -->
                <div class="text-center mt-4 pt-3 border-top">
                    <p class="text-muted mb-0">
                        Total Guests: <span class="fw-bold" id="totalGuests">0</span> | 
                        Last updated: <span id="lastUpdated">-</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals (using Bootstrap 5) -->
    <!-- RSVP Modal -->
    <div class="modal fade" id="rsvpModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add New RSVP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="rsvpForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <!-- <div class="mb-3">
                            <label for="confirmation" class="form-label">Confirmation Status *</label>
                            <select class="form-select" id="confirmation" required>
                                <option value="">Select Status</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="pending">Pending</option>
                                <option value="declined">Declined</option>
                            </select>
                        </div> -->
                        <div class="mb-3">
                            <label for="plusOne" class="form-label">Plus One</label>
                            <select class="form-select" id="plusOne">
                                <option value="no">No</option>
                                <option value="yes">Yes</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="plusOneName" class="form-label">Plus One Name (if applicable)</label>
                            <input type="text" class="form-control" id="plusOneName" placeholder="Enter name of plus one" disabled>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save RSVP</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Event Date Modal -->
    <div class="modal fade" id="eventDateModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Event Date</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="eventDateForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="eventDateInput" class="form-label">Event Date *</label>
                            <input type="date" class="form-control" id="eventDateInput" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Date</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Venue Modal -->
    <div class="modal fade" id="venueModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Event Venue</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="venueForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="venueInput" class="form-label">Venue *</label>
                            <input type="text" class="form-control" id="venueInput" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Venue</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Dark Mode Toggle Button -->
    <button class="dark-mode-toggle" id="darkModeToggle">
        <i class="fas fa-moon" id="darkModeIcon"></i>
    </button>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- RSVP Tracker JavaScript -->
    <script type="module">
        import { db, storage } from './Firebase/firebase_conn.js';
        import { collection, query, where, doc, getDocs, getFirestore, getDoc, addDoc, updateDoc, deleteDoc, serverTimestamp, Timestamp, and, or, orderBy } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-firestore.js";

        // Initialization
        let selectedEventId = null;
        let upcomingEvents = []; 

        // RSVP Tracker Implementation
        document.addEventListener('DOMContentLoaded', function() {
            // Three Dots Menu Functions
            window.exportRSVPs = function() {
                if (rsvpData.length === 0) {
                    showNotification('No RSVPs to export', 'warning');
                    return;
                }
                
                const dataStr = JSON.stringify(rsvpData, null, 2);
                const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
                const exportFileDefaultName = 'cboc-rsvp-export.json';
                
                const linkElement = document.createElement('a');
                linkElement.setAttribute('href', dataUri);
                linkElement.setAttribute('download', exportFileDefaultName);
                linkElement.click();
                
                showNotification('RSVPs exported successfully!', 'success');
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
- Edit or delete RSVPs using action buttons
- Click on event date/venue to edit
- Use search bar to find guests
- Filter by status using the filter buttons
- Export data as CSV or JSON
- Statistics show guest counts in real-time
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

            // DARK MODE TOGGLE (MUST MATCH YOUR DASHBOARD)
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

            // Key for localStorage
            const STORAGE_KEY = 'cboc_rsvp_data';
            const EVENT_STORAGE_KEY = 'cboc_event_details';
            
            // Sample initial RSVP data
            const sampleData = [
                { id: 1, name: "Maria Santos", email: "maria.santos@example.com", confirmation: "confirmed", plusOne: "yes", plusOneName: "Juan Santos" },
                { id: 2, name: "John Cruz", email: "john.cruz@example.com", confirmation: "pending", plusOne: "no", plusOneName: "" },
                { id: 3, name: "Andrea Reyes", email: "andrea.reyes@example.com", confirmation: "confirmed", plusOne: "yes", plusOneName: "Michael Reyes" },
                { id: 4, name: "Robert Lim", email: "robert.lim@example.com", confirmation: "declined", plusOne: "no", plusOneName: "" },
                { id: 5, name: "Sofia Tan", email: "sofia.tan@example.com", confirmation: "confirmed", plusOne: "no", plusOneName: "" }
            ];

            // Default event details
            const defaultEventDetails = {
                date: "June 15, 2023",
                venue: "Grand Ballroom",
                rawDate: "2023-06-15"
            };

            // Load data from localStorage or use sample data
            function loadData() {
                const storedData = localStorage.getItem(STORAGE_KEY);
                if (storedData) {
                    return JSON.parse(storedData);
                } else {
                    // Save sample data to localStorage for first time use
                    saveData(sampleData);
                    return sampleData;
                }
            }

            // Load RSVP data from Firebase
            async function loadRSVPsForEvent(eventId) {
                const tableBody = document.getElementById("tableBody");
                const totalGuestsEl = document.getElementById("totalGuests");

                tableBody.innerHTML = `<tr><td colspan="4" class="text-center text-muted">Loading RSVPs...</td></tr>`;
                totalGuestsEl.textContent = "0";

                if (!eventId) {
                    tableBody.innerHTML = `<tr><td colspan="4" class="text-center text-muted">Select an event to see RSVPs.</td></tr>`;
                    return;
                }

                try {
                    const rsvpRef = collection(db, "events", eventId, "rsvp");
                    const snapshot = await getDocs(rsvpRef);

                    if (snapshot.empty) {
                        tableBody.innerHTML = `<tr><td colspan="4" class="text-center text-muted">No RSVPs yet for this event.</td></tr>`;
                        return;
                    }

                    let rowsHtml = "";
                    let totalGuests = 0;

                    snapshot.forEach(doc => {
                        const rsvp = doc.data();
                        const name = rsvp.name || "Unnamed";
                        const email = rsvp.email || "-";
                        const plusOne = rsvp.plusOne ? "Yes" : "No";

                        let plusOneDisplay = "No";

                        if (rsvp.plusOne) {
                            plusOneDisplay = `Yes - (${rsvp.plusOne || "Unnamed"})`;
                        }

                        rowsHtml += `
                            <tr>
                                <td>${name}</td>
                                <td>${email}</td>
                                <td>${plusOneDisplay}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary edit-btn" onclick="editRSVP('${doc.id}')">Edit</button>
                                    <button class="btn btn-sm btn-outline-danger delete-btn" onclick="deleteRSVP('${doc.id}')">Delete</button>
                                </td>
                            </tr>
                        `;

                        totalGuests += 1 + (rsvp.plusOne ? 1 : 0); // count plus ones
                    });

                    tableBody.innerHTML = rowsHtml;
                    totalGuestsEl.textContent = totalGuests;

                } catch (error) {
                    console.error("Error loading RSVPs:", error);
                    tableBody.innerHTML = `<tr><td colspan="4" class="text-center text-danger">Error loading RSVPs</td></tr>`;
                }
            }

            // Load event details from localStorage or use default
            function loadEventDetails() {
                const storedEvent = localStorage.getItem(EVENT_STORAGE_KEY);
                if (storedEvent) {
                    return JSON.parse(storedEvent);
                } else {
                    // Save default event details to localStorage for first time use
                    saveEventDetails(defaultEventDetails);
                    return defaultEventDetails;
                }
            }

            // Save data to localStorage
            function saveData(data) {
                localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
            }

            // Save event details to localStorage
            function saveEventDetails(eventDetails) {
                localStorage.setItem(EVENT_STORAGE_KEY, JSON.stringify(eventDetails));
            }

            // Get next ID
            function getNextId(data) {
                if (data.length === 0) return 1;
                return Math.max(...data.map(item => item.id)) + 1;
            }

            // Format date for display
            function formatDateForDisplay(dateString) {
                const date = new Date(dateString);
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                return date.toLocaleDateString('en-US', options);
            }

            // Initialize data from localStorage
            // let rsvpData = loadData();
            let eventDetails = loadEventDetails();
            // let nextId = getNextId(rsvpData);

            // DOM Elements
            const tableBody = document.getElementById('tableBody');
            const searchInput = document.getElementById('searchInput');
            const filterButtons = document.querySelectorAll('.filter-btn');
            const addRSVPBtn = document.getElementById('addRSVPBtn');
            const rsvpForm = document.getElementById('rsvpForm');
            const modalTitle = document.getElementById('modalTitle');
            const exportCSVBtn = document.getElementById('exportCSVBtn');
            const exportJSONBtn = document.getElementById('exportJSONBtn');
            const resetDataBtn = document.getElementById('resetDataBtn');
            
            // Event elements
            const editEventDateElement = document.getElementById('editEventDate');
            const editVenueElement = document.getElementById('editVenue');
            const eventDateDisplayElement = document.getElementById('eventDateDisplay');
            const venueDisplayElement = document.getElementById('venueDisplay');
            
            // Event form elements
            const eventDateInput = document.getElementById('eventDateInput');
            const venueInput = document.getElementById('venueInput');
            const eventDateForm = document.getElementById('eventDateForm');
            const venueForm = document.getElementById('venueForm');
            
            // Count elements
            const confirmedCount = document.getElementById('confirmedCount');
            const pendingCount = document.getElementById('pendingCount');
            const declinedCount = document.getElementById('declinedCount');
            const plusOneCount = document.getElementById('plusOneCount');
            const totalGuests = document.getElementById('totalGuests');
            const lastUpdated = document.getElementById('lastUpdated');
            
            // RSVP form elements
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const confirmationInput = document.getElementById('confirmation');
            const plusOneInput = document.getElementById('plusOne');
            const plusOneNameInput = document.getElementById('plusOneName');
            
            // State variables
            let currentFilter = 'all';
            let currentSearch = '';
            let editingId = null;
            
            // Initialize the RSVP Tracker
            function init() {
                // Update event details display
                updateEventDisplay();
                
                updateTable();
                updateCounts();
                updateLastUpdated();
                
                // Event listener for search
                searchInput.addEventListener('input', () => {
                    currentSearch = searchInput.value.toLowerCase();
                    updateTable();
                });
                
                // Event listeners for filtering
                filterButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        // Remove active class from all buttons
                        filterButtons.forEach(btn => btn.classList.remove('active'));
                        // Add active class to clicked button
                        button.classList.add('active');
                        // Set current filter
                        currentFilter = button.getAttribute('data-filter');
                        updateTable();
                    });
                });
                
                // Event listeners for modals
                addRSVPBtn.addEventListener('click', openAddModal);
                
                // Event listeners for editable event details
                editEventDateElement.addEventListener('click', () => {
                    eventDateInput.value = eventDetails.rawDate;
                    new bootstrap.Modal(document.getElementById('eventDateModal')).show();
                });
                
                editVenueElement.addEventListener('click', () => {
                    venueInput.value = eventDetails.venue;
                    new bootstrap.Modal(document.getElementById('venueModal')).show();
                });
                
                // Event listener for form submissions
                rsvpForm.addEventListener('submit', saveRSVP);
                eventDateForm.addEventListener('submit', saveEventDate);
                venueForm.addEventListener('submit', saveVenue);
                
                // Event listener for plus one toggle
                plusOneInput.addEventListener('change', function() {
                    plusOneNameInput.disabled = this.value !== 'yes';
                });
                
                // Event listeners for export buttons
                exportCSVBtn.addEventListener('click', exportToCSV);
                exportJSONBtn.addEventListener('click', exportToJSON);
                
                // Event listener for reset button
                resetDataBtn.addEventListener('click', resetData);
                
                // Initialize plus one name field
                plusOneNameInput.disabled = true;
            }
            
            // Update event details display
            function updateEventDisplay() {
                eventDateDisplayElement.textContent = eventDetails.date;
                venueDisplayElement.textContent = eventDetails.venue;
            }
            
            // Update the RSVP table with filtered data
            function updateTable() {
                // Clear the table body
                tableBody.innerHTML = '';
                
                // Filter data based on current filter and search
                let filteredData = rsvpData.filter(item => {
                    // Apply search filter
                    const matchesSearch = currentSearch === '' || 
                        item.name.toLowerCase().includes(currentSearch) || 
                        item.email.toLowerCase().includes(currentSearch);
                    
                    // Apply status filter
                    let matchesFilter = true;
                    if (currentFilter !== 'all') {
                        if (currentFilter === 'plusOne') {
                            matchesFilter = item.plusOne === 'yes';
                        } else {
                            matchesFilter = item.confirmation === currentFilter;
                        }
                    }
                    
                    return matchesSearch && matchesFilter;
                });
                
                // If no results, show a message
                if (filteredData.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                <p class="text-muted">No RSVPs found. Try adjusting your search or filter.</p>
                            </td>
                        </tr>
                    `;
                    return;
                }
                
                // Populate table with filtered data
                filteredData.forEach(item => {
                    const row = document.createElement('tr');
                    
                    // Determine status class and display text
                    let statusClass = item.confirmation;
                    let statusText = item.confirmation.charAt(0).toUpperCase() + item.confirmation.slice(1);
                    
                    row.innerHTML = `
                        <td>${item.name}</td>
                        <td>${item.email}</td>
                        <td><span class="status status-${statusClass}">${statusText}</span></td>
                        <td>${item.plusOne === 'yes' ? 'Yes' + (item.plusOneName ? ` (${item.plusOneName})` : '') : 'No'}</td>
                        <td>
                            <div class="action-btns">
                                <button class="btn btn-sm btn-outline-primary edit-btn" data-id="${item.id}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${item.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    `;
                    
                    tableBody.appendChild(row);
                });
                
                // Add event listeners to action buttons
                document.querySelectorAll('.edit-btn').forEach(button => {
                    button.addEventListener('click', () => {
                        const id = parseInt(button.getAttribute('data-id'));
                        openEditModal(id);
                    });
                });
                
                document.querySelectorAll('.delete-btn').forEach(button => {
                    button.addEventListener('click', () => {
                        const id = parseInt(button.getAttribute('data-id'));
                        deleteRSVP(id);
                    });
                });
            }
            
            // Update the counts in the dashboard
            // function updateCounts() {
            //     const confirmed = rsvpData.filter(item => item.confirmation === 'confirmed').length;
            //     const pending = rsvpData.filter(item => item.confirmation === 'pending').length;
            //     const declined = rsvpData.filter(item => item.confirmation === 'declined').length;
            //     const plusOne = rsvpData.filter(item => item.plusOne === 'yes').length;
            //     const total = rsvpData.length;
                
            //     confirmedCount.textContent = confirmed;
            //     pendingCount.textContent = pending;
            //     declinedCount.textContent = declined;
            //     plusOneCount.textContent = plusOne;
            //     totalGuests.textContent = total + plusOne; // Include plus ones in total guests
            // }
            
            // Update last updated timestamp
            async function updateLastUpdated() {
                if (!selectedEventId) return;
                const lastUpdatedEl = document.getElementById("lastUpdated");

                try {
                    const eventRef = doc(db, "events", selectedEventId);
                    const snapshot = await getDoc(eventRef);
                    if (!snapshot.exists()) {
                        lastUpdatedEl.textContent = "-";
                        return;
                    }

                    const data = snapshot.data();
                    if (!data.updatedAt) {
                        lastUpdatedEl.textContent = "-";
                        return;
                    }

                    const updatedDate = data.updatedAt.toDate();
                    const options = { 
                        year: 'numeric', 
                        month: 'short', 
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    };
                    lastUpdatedEl.textContent = updatedDate.toLocaleDateString('en-US', options);

                } catch (error) {
                    console.error("Error getting updatedAt:", error);
                    lastUpdatedEl.textContent = "-";
                }
            }
            
            // Open modal for adding a new RSVP
            function openAddModal() {
                editingId = null;
                modalTitle.textContent = 'Add New RSVP';
                rsvpForm.reset();
                plusOneNameInput.disabled = true;
                new bootstrap.Modal(document.getElementById('rsvpModal')).show();
            }
            
            // Open modal for editing an existing RSVP
            function openEditModal(id) {
                const rsvp = rsvpData.find(item => item.id === id);
                if (!rsvp) return;
                
                editingId = id;
                modalTitle.textContent = 'Edit RSVP';
                
                // Fill form with RSVP data
                nameInput.value = rsvp.name;
                emailInput.value = rsvp.email;
                confirmationInput.value = rsvp.confirmation;
                plusOneInput.value = rsvp.plusOne;
                plusOneNameInput.value = rsvp.plusOneName || '';
                plusOneNameInput.disabled = rsvp.plusOne !== 'yes';
                
                new bootstrap.Modal(document.getElementById('rsvpModal')).show();
            }
            
            // Save RSVP (both new and edit)
            function saveRSVP(e) {
                e.preventDefault();
                
                // Validate required fields
                if (!nameInput.value.trim() || !emailInput.value.trim() || !confirmationInput.value) {
                    alert('Please fill in all required fields (Name, Email, and Confirmation Status)');
                    return;
                }
                
                // Create RSVP object
                const rsvp = {
                    id: editingId || nextId,
                    name: nameInput.value.trim(),
                    email: emailInput.value.trim(),
                    confirmation: confirmationInput.value,
                    plusOne: plusOneInput.value,
                    plusOneName: plusOneInput.value === 'yes' ? plusOneNameInput.value.trim() : ''
                };
                
                if (editingId) {
                    // Update existing RSVP
                    const index = rsvpData.findIndex(item => item.id === editingId);
                    if (index !== -1) {
                        rsvpData[index] = rsvp;
                    }
                } else {
                    // Add new RSVP
                    rsvpData.push(rsvp);
                    nextId++;
                }
                
                // Save to localStorage
                saveData(rsvpData);
                
                // Update UI
                updateTable();
                updateCounts();
                updateLastUpdated();
                
                // Close modal
                bootstrap.Modal.getInstance(document.getElementById('rsvpModal')).hide();
                
                // Show confirmation message
                showNotification(`RSVP ${editingId ? 'updated' : 'added'} successfully!`);
            }
            
            // Save event date
            function saveEventDate(e) {
                e.preventDefault();
                
                // Validate required field
                if (!eventDateInput.value) {
                    alert('Please select an event date');
                    return;
                }
                
                // Format date for display
                const formattedDate = formatDateForDisplay(eventDateInput.value);
                
                // Update event details
                eventDetails.date = formattedDate;
                eventDetails.rawDate = eventDateInput.value;
                
                // Save to localStorage
                saveEventDetails(eventDetails);
                
                // Update display
                updateEventDisplay();
                
                // Close modal
                bootstrap.Modal.getInstance(document.getElementById('eventDateModal')).hide();
                
                // Show confirmation message
                showNotification('Event date updated successfully!');
            }
            
            // Save venue
            function saveVenue(e) {
                e.preventDefault();
                
                // Validate required field
                if (!venueInput.value.trim()) {
                    alert('Please enter a venue');
                    return;
                }
                
                // Update event details
                eventDetails.venue = venueInput.value.trim();
                
                // Save to localStorage
                saveEventDetails(eventDetails);
                
                // Update display
                updateEventDisplay();
                
                // Close modal
                bootstrap.Modal.getInstance(document.getElementById('venueModal')).hide();
                
                // Show confirmation message
                showNotification('Venue updated successfully!');
            }
            
            // Delete an RSVP
            function deleteRSVP(id) {
                if (!confirm('Are you sure you want to delete this RSVP?')) {
                    return;
                }
                
                const index = rsvpData.findIndex(item => item.id === id);
                if (index !== -1) {
                    const name = rsvpData[index].name;
                    rsvpData.splice(index, 1);
                    
                    // Save to localStorage
                    saveData(rsvpData);
                    
                    // Update nextId
                    nextId = getNextId(rsvpData);
                    
                    // Update UI
                    updateTable();
                    updateCounts();
                    updateLastUpdated();
                    
                    // Show notification
                    showNotification(`RSVP for ${name} deleted successfully!`);
                }
            }
            
            // Reset data to sample data
            function resetData() {
                if (!confirm('Are you sure you want to reset all data? This will delete all your current RSVPs and restore the sample data.')) {
                    return;
                }
                
                rsvpData = [...sampleData];
                nextId = getNextId(rsvpData);
                eventDetails = { ...defaultEventDetails };
                
                // Save to localStorage
                saveData(rsvpData);
                saveEventDetails(eventDetails);
                
                // Reset search and filter
                searchInput.value = '';
                currentSearch = '';
                
                // Reset filter buttons
                filterButtons.forEach(btn => {
                    if (btn.getAttribute('data-filter') === 'all') {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });
                currentFilter = 'all';
                
                // Update UI
                updateEventDisplay();
                updateTable();
                updateCounts();
                updateLastUpdated();
                
                showNotification('Data reset to sample data successfully!');
            }
            
            // Export data to CSV
            function exportToCSV() {
                if (rsvpData.length === 0) {
                    alert('No data to export.');
                    return;
                }
                
                // Include event details in CSV
                const eventInfo = `Event Date: ${eventDetails.date}\nVenue: ${eventDetails.venue}\nExported from CBOC Admin\n\n`;
                
                // Define CSV headers
                const headers = ['Name', 'Email', 'Confirmation', 'Plus One', 'Plus One Name'];
                
                // Convert data to CSV rows
                const csvRows = [
                    eventInfo,
                    headers.join(','), // Header row
                    ...rsvpData.map(item => [
                        `"${item.name}"`,
                        `"${item.email}"`,
                        `"${item.confirmation}"`,
                        `"${item.plusOne}"`,
                        `"${item.plusOneName || ''}"`
                    ].join(','))
                ];
                
                // Create CSV string
                const csvString = csvRows.join('\n');
                
                // Create download link
                const blob = new Blob([csvString], { type: 'text/csv' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `cboc-rsvp-data-${new Date().toISOString().slice(0, 10)}.csv`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
                
                showNotification('CSV exported successfully!');
            }
            
            // Export data to JSON
            function exportToJSON() {
                if (rsvpData.length === 0) {
                    alert('No data to export.');
                    return;
                }
                
                // Create data object with event details and RSVPs
                const exportData = {
                    event: eventDetails,
                    rsvps: rsvpData,
                    exportedAt: new Date().toISOString()
                };
                
                // Create JSON string
                const jsonString = JSON.stringify(exportData, null, 2);
                
                // Create download link
                const blob = new Blob([jsonString], { type: 'application/json' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `cboc-rsvp-data-${new Date().toISOString().slice(0, 10)}.json`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
                
                showNotification('JSON exported successfully!');
            }
            
            // Show a temporary notification
            function showNotification(message, type = 'success') {
                const icons = { 
                    success: 'fa-check-circle', 
                    error: 'fa-exclamation-circle', 
                    warning: 'fa-exclamation-triangle', 
                    info: 'fa-info-circle' 
                };
                
                // Remove existing notification if present
                const existingNotification = document.querySelector('.notification');
                if (existingNotification) {
                    existingNotification.remove();
                }
                
                // Create notification element
                const notification = document.createElement('div');
                notification.className = `notification ${type}`;
                notification.innerHTML = `<i class="fas ${icons[type]}"></i><span>${message}</span>`;
                
                document.body.appendChild(notification);
                
                // Remove after 3 seconds
                setTimeout(() => {
                    notification.style.animation = 'slideOut 0.3s ease';
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.remove();
                        }
                    }, 300);
                }, 3000);
            }

            // Add notification styles if not already present
            if (!document.querySelector('#notification-styles')) {
                const notificationStyle = document.createElement('style');
                notificationStyle.id = 'notification-styles';
                notificationStyle.textContent = `
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
                document.head.appendChild(notificationStyle);
            }

            // Load events from DB
            async function loadUpcomingEvents() {
                console.log("loadUpcomingEvents");
                const container = document.getElementById("eventsListContainer");
                const today = new Date();
                today.setHours(0,0,0,0);

                // ❗ Get ALL events (no where filter)
                const q = query(collection(db, "events"));
                const snapshot = await getDocs(q);

                snapshot.forEach(doc => {
                    const event = doc.data();
                    let eventDate;

                    // Handle Timestamp
                    if (event.date?.toDate) {
                        eventDate = event.date.toDate();
                    }

                    // Handle YYYY-MM-DD string
                    else if (typeof event.date === "string") {
                        eventDate = new Date(event.date);
                    }

                    if (!eventDate) return;

                    eventDate.setHours(0,0,0,0);

                    if (eventDate >= today) {
                        upcomingEvents.push({
                            id: doc.id,
                            ...event,
                            parsedDate: eventDate
                        });
                    }

                });

                // Sort events by date
                upcomingEvents.sort((a,b) => a.parsedDate - b.parsedDate);

                if (upcomingEvents.length === 0) {
                    container.innerHTML = `
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-calendar-times fa-2x mb-2"></i>
                            <div>No upcoming events found.</div>
                            <small>Create an event first to start tracking RSVPs.</small>
                        </div>
                    `;
                    return;
                }

                let html = `<div class="list-group">`;
                upcomingEvents.forEach(event => {
                    const eventId = event.id;
                    const date = event.parsedDate.toLocaleDateString();
                    html += `
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${event.title || "Untitled Event"}</strong>
                            <div class="text-muted small">
                                <i class="fas fa-calendar-alt"></i> ${date}
                                ${event.venue ? `<span class="mx-2">|</span> <i class="fas fa-map-marker-alt"></i> ${event.venue}` : ""}
                            </div>
                        </div>

                        <button class="btn btn-sm btn-primary"
                            onclick="selectEventForRSVP('${eventId}')">
                            Check RSVP
                        </button>
                    </div>
                    `;
                });
                html += `</div>`;
                container.innerHTML = html;
            }

            // Display RSVP for an event
            window.selectEventForRSVP = function (eventId) {
                selectedEventId = eventId;
                updateEventVisibility();
                updateEventDetailsCard();
                console.log("Selected Event:", selectedEventId);
                loadRSVPsForEvent(selectedEventId);
            }

            // Display table when Checking for RSVP
            window.updateEventVisibility = function () {
                const eventContent = document.getElementById("eventContent");

                if (selectedEventId === null) {
                    eventContent.style.display = "none";
                } else {
                    eventContent.style.display = "block";
                }
            }

            // Update contents of Event Details Card
            window.updateEventDetailsCard = function () {
                if (!selectedEventId) return;

                const event = upcomingEvents.find(ev => ev.id === selectedEventId);
                if (!event) return;

                // DOM elements
                const titleEl = document.querySelector(".event-details-card h4");
                const dateEl = document.getElementById("eventDateDisplay");
                const venueEl = document.getElementById("venueDisplay");

                // Fill them
                titleEl.textContent = event.title || "Untitled Event";

                // Format date as YYYY-MM-DD or nicer
                const dateStr = event.parsedDate
                    ? event.parsedDate.toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' })
                    : "Unknown Date";
                dateEl.textContent = dateStr;

                venueEl.textContent = event.venue || "Unknown Venue";
            }

            // Export CSV Function
            window.exportCSV = function () {
                const table = document.querySelector(".rsvp-table tbody");
                if (!table) return;

                let csv = "Attendees,Email,Plus One\n";

                table.querySelectorAll("tr").forEach(row => {
                    const cells = row.querySelectorAll("td");
                    if (cells.length < 3) return; // skip placeholders
                    const rowData = [
                        cells[0].textContent.trim(),
                        cells[1].textContent.trim(),
                        cells[2].textContent.trim()
                    ];
                    csv += rowData.join(",") + "\n";
                });

                const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
                const url = URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.href = url;
                a.download = "RSVP_List.csv";
                a.click();
                URL.revokeObjectURL(url);
            };

            //Export PDF Function
            window.exportPDF = function () {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                const table = document.querySelector(".rsvp-table tbody");
                if (!table) return;

                const rows = [];
                table.querySelectorAll("tr").forEach(row => {
                    const cells = row.querySelectorAll("td");
                    if (cells.length < 3) return; // skip placeholders
                    rows.push([
                        cells[0].textContent.trim(),
                        cells[1].textContent.trim(),
                        cells[2].textContent.trim()
                    ]);
                });

                doc.setFontSize(16);
                doc.text("RSVP List", 14, 20);
                doc.setFontSize(12);
                doc.autoTable({
                    head: [["Attendees", "Email", "Plus One"]],
                    body: rows,
                    startY: 30
                });

                doc.save("RSVP_List.pdf");
            };

            // Show Add RSVP Modal
            window.addRSVPBtn = function () {
                if (!selectedEventId) {
                    alert("Please select an event first.");
                    return;
                }

                document.getElementById("rsvpForm").reset();

                const modal = new bootstrap.Modal(document.getElementById("rsvpModal"));
                modal.show();
            };

            // Add RSVP to Database
            document.getElementById("rsvpForm").addEventListener("submit", async function(e) {
                e.preventDefault();
                if (!selectedEventId) return;

                const name = document.getElementById("name").value.trim();
                const email = document.getElementById("email").value.trim();
                const plusOne = document.getElementById("plusOneName").value.trim();

                try {
                    await addDoc(
                        collection(db, "events", selectedEventId, "rsvp"),
                        {
                            name: name,
                            email: email,
                            plusOne: plusOne,
                            createdAt: new Date()
                        }
                    );

                    await updateDoc(
                        doc(db, "events", selectedEventId),
                        {
                            updatedAt: serverTimestamp()
                        }
                    )

                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById("rsvpModal")).hide();

                    // Reload RSVP table
                    loadRSVPsForEvent(selectedEventId);

                } catch (error) {
                    console.error("Error saving RSVP:", error);
                    alert("Error saving RSVP");
                }

            });

            // Enable Plus One name input if chosen Yes
            document.getElementById("plusOne").addEventListener("change", function(){
                const plusOneName = document.getElementById("plusOneName");

                if (this.value === "yes") {
                    plusOneName.disabled = false;
                } else {
                    plusOneName.disabled = true;
                    plusOneName.value = "";
                }
            });
            
            // Initialize the RSVP tracker
            loadUpcomingEvents();
            updateEventVisibility();
            init();
        });
    </script>
</body>
</html>