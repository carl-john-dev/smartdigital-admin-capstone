<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSVP Event Tracker - CBOC Admin with Aggregate Attendance & Booths</title>
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
        
        /* Aggregate Attendance Card - NEW STYLES */
        .aggregate-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            color: white;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .aggregate-stats {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 15px;
        }
        
        .aggregate-stat-item {
            text-align: center;
            flex: 1;
            min-width: 120px;
            padding: 15px;
            background: rgba(255,255,255,0.15);
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }
        
        .aggregate-stat-number {
            font-size: 2.8rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .aggregate-stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .aggregate-total {
            background: rgba(255,255,255,0.25);
            transform: scale(1.05);
        }
        
        /* NEW: Booth Card Styles */
        .booth-card {
            background: linear-gradient(135deg, #2d8a4e 0%, #1a5d34 100%);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            color: white;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .booth-stats {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 15px;
        }
        
        .booth-stat-item {
            text-align: center;
            flex: 1;
            min-width: 120px;
            padding: 15px;
            background: rgba(255,255,255,0.15);
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }
        
        .booth-stat-number {
            font-size: 2.8rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .booth-stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .booth-available {
            background: rgba(255,255,255,0.25);
            transform: scale(1.05);
        }
        
        .booth-progress {
            margin-top: 15px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            height: 12px;
            overflow: hidden;
        }
        
        .booth-progress-fill {
            background: #ffd700;
            height: 100%;
            border-radius: 10px;
            transition: width 0.3s ease;
        }
        
        .btn-booth {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            margin-top: 15px;
        }
        
        .btn-booth:hover {
            background: rgba(255,255,255,0.3);
            color: white;
        }
        
        .walkin-badge {
            background: #ffc107;
            color: #856404;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .rsvp-badge {
            background: #28a745;
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }
        
        /* Walk-in Modal Styles */
        .walkin-section {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 10px 15px;
            border-radius: 8px;
            margin-top: 15px;
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
        
        /* NEW: Attendance Hit Rate Card Styles */
        .hitrate-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            color: white;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .hitrate-stats {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 15px;
        }
        
        .hitrate-stat-item {
            text-align: center;
            flex: 1;
            min-width: 140px;
            padding: 15px;
            background: rgba(255,255,255,0.2);
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }
        
        .hitrate-stat-number {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .hitrate-stat-label {
            font-size: 0.9rem;
            opacity: 0.95;
        }
        
        .hitrate-badge {
            background: rgba(255,255,255,0.3);
            border-radius: 30px;
            padding: 8px 18px;
            font-weight: bold;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        /* NEW: Booth Application Styles */
        .booth-applied-badge {
            background: linear-gradient(135deg, #2d8a4e, #1a5d34);
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-block;
            margin-left: 8px;
        }
        
        .booth-application-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-top: 15px;
            border: 1px solid #dee2e6;
        }
        
        /* NEW: Event Conflict Detection Styles */
        .conflict-warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px 15px;
            border-radius: 8px;
            margin-top: 10px;
            color: #856404;
        }
        
        .conflict-error {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 12px 15px;
            border-radius: 8px;
            margin-top: 10px;
            color: #721c24;
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
        .dark-mode .stat-card-mini,
        .dark-mode .aggregate-card,
        .dark-mode .booth-card,
        .dark-mode .hitrate-card {
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
        
        .dark-mode .booth-application-section {
            background: #3a3a3a;
            border-color: #555;
            color: white;
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
            
            .aggregate-stats {
                flex-direction: column;
            }
            
            .hitrate-stats {
                flex-direction: column;
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
        
        .btn-walkin {
            background: #ffc107;
            color: #856404;
            border: none;
        }
        
        .btn-walkin:hover {
            background: #e0a800;
            color: #856404;
        }
    </style>
</head>
<body>
    <!-- Sidebar (SAME AS YOUR DASHBOARD) -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-tachometer-alt"></i> CBOC</h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
            <li><a href="members.php"><i class="fas fa-users"></i><span>Users</span></a></li>
            <li><a href="location.php"><i class="fas fa-map-marked-alt"></i><span>Location</span></a></li>
            <li><a href="e-portfolio.php"><i class="fas fa-id-card"></i><span>E-Portfolio</span></a></li>
            <li><a href="calendar.php"><i class="fas fa-calendar"></i><span>Calendar</span></a></li>
            <li><a href="rsvp.php" class="active"><i class="fas fa-calendar-check"></i><span>RSVP Tracker</span></a></li>
            <li><a href="request.php"><i class="fas fa-clipboard-list"></i><span>Approvals</span></a></li>
            <li><a href="ordercard.php"><i class="fas fa-shopping-cart"></i><span>NFC Card</span></a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
        </ul>
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Top Bar with Three Dots Menu -->
        <div class="top-bar">
            <div>
                <h1><i class="fas fa-calendar-check"></i> RSVP Event Tracker</h1>
                <p class="text-muted mb-0">Manage event invitations, walk-ins, and track aggregate attendance</p>
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
                <button class="btn btn-sm btn-primary" onclick="openAddEventModal()">
                    <i class="fas fa-plus"></i> Add New Event
                </button>
            </div>
            <div class="card-body" id="eventsListContainer">
                <div class="text-muted text-center py-3">
                    Loading upcoming events...
                </div>
            </div>
        </div>

        <!-- NEW: Add Event Modal with Conflict Detection -->
        <div class="modal fade" id="addEventModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-calendar-plus"></i> Create New Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="addEventForm">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="eventTitle" class="form-label">Event Title *</label>
                                <input type="text" class="form-control" id="eventTitle" required placeholder="e.g., CBOC Annual Conference">
                            </div>
                            <div class="mb-3">
                                <label for="eventDate" class="form-label">Event Date *</label>
                                <input type="date" class="form-control" id="eventDate" required>
                                <div class="form-text">Select the date for this event</div>
                            </div>
                            <!-- NEW: Conflict warning message -->
                            <div id="conflictWarning" style="display: none;" class="conflict-warning">
                                <i class="fas fa-exclamation-triangle"></i> <span id="conflictMessage"></span>
                            </div>
                            <div class="mb-3">
                                <label for="eventVenue" class="form-label">Venue</label>
                                <input type="text" class="form-control" id="eventVenue" placeholder="e.g., Grand Ballroom">
                            </div>
                            <div class="mb-3">
                                <label for="eventTotalBooths" class="form-label">Total Booths</label>
                                <input type="number" class="form-control" id="eventTotalBooths" min="0" value="0">
                            </div>
                            <hr>
                            <div class="alert alert-info small">
                                <i class="fas fa-info-circle"></i> <strong>Note:</strong> Hindi pwedeng magkaroon ng dalawang event sa parehong araw. Magpapakita ng error kung may conflict.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="submitEventBtn">Create Event</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="eventContent" style="display:none;">
            <!-- Event Details Card -->
            <div class="event-details-card">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h4 class="mb-2" id="eventTitleDisplay">CBOC Event</h4>
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
                    <div class="d-flex gap-2">
                        <button class="btn btn-light" onclick="addRSVPBtn()">
                            <i class="fas fa-plus"></i> Add RSVP
                        </button>
                        <button class="btn btn-warning" onclick="addWalkinBtn()">
                            <i class="fas fa-person-walking-arrow-right"></i> Add Walk-in
                        </button>
                    </div>
                </div>
            </div>

            <!-- NEW: ATTENDANCE HIT RATE CARD -->
            <div class="hitrate-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><i class="fas fa-chart-line"></i> 📊 Attendance Hit Rate</h5>
                    <i class="fas fa-percent fa-2x"></i>
                </div>
                <div class="hitrate-stats">
                    <div class="hitrate-stat-item">
                        <div class="hitrate-stat-number" id="hitRatePercent">0%</div>
                        <div class="hitrate-stat-label"><i class="fas fa-trophy"></i> HIT RATE</div>
                        <small id="hitRateDetail" style="font-size:0.7rem; opacity:0.9;">(RSVP → Attendance)</small>
                    </div>
                    <div class="hitrate-stat-item">
                        <div class="hitrate-stat-number" id="totalRSVPsCount">0</div>
                        <div class="hitrate-stat-label"><i class="fas fa-envelope-open-text"></i> Total RSVPs</div>
                    </div>
                    <div class="hitrate-stat-item">
                        <div class="hitrate-stat-number" id="actualAttendeesCount">0</div>
                        <div class="hitrate-stat-label"><i class="fas fa-users"></i> Actual Attendees</div>
                        <small>(RSVP + Walk-ins)</small>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 12px; background: rgba(255,255,255,0.3); border-radius: 10px;">
                    <div id="hitRateProgressBar" class="progress-bar" style="width: 0%; transition: width 0.5s ease;"></div>
                </div>
                <div class="text-center mt-3">
                    <div class="hitrate-badge">
                        <i class="fas fa-chart-simple"></i> Conversion: <span id="hitRateConversionText">0/0</span> RSVPs attended
                    </div>
                </div>
                <div class="text-center mt-2 small">
                    <i class="fas fa-info-circle"></i> Hit Rate = (Actual Attendees ÷ Total RSVPs) × 100% — Measures RSVP to attendance conversion
                </div>
            </div>

            <!-- BOOTH AVAILABILITY CARD -->
            <div class="booth-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><i class="fas fa-booth-curtain"></i> Booth Availability</h5>
                    <button class="btn btn-booth btn-sm" onclick="openBoothModal()">
                        <i class="fas fa-edit"></i> Manage Booths
                    </button>
                </div>
                <div class="booth-stats">
                    <div class="booth-stat-item">
                        <div class="booth-stat-number" id="availableBoothsDisplay">0</div>
                        <div class="booth-stat-label"><i class="fas fa-door-open"></i> AVAILABLE BOOTHS</div>
                    </div>
                    <div class="booth-stat-item">
                        <div class="booth-stat-number" id="reservedBoothsDisplay">0</div>
                        <div class="booth-stat-label"><i class="fas fa-check-circle"></i> Reserved Booths</div>
                    </div>
                    <div class="booth-stat-item booth-available">
                        <div class="booth-stat-number" id="totalBoothsDisplay">0</div>
                        <div class="booth-stat-label"><i class="fas fa-building"></i> Total Booths</div>
                    </div>
                </div>
                <div class="booth-progress">
                    <div class="booth-progress-fill" id="boothProgressFill" style="width: 0%"></div>
                </div>
                <div class="text-center mt-2 small">
                    <i class="fas fa-info-circle"></i> Available = Total Booths - Reserved Booths
                </div>
            </div>

            <!-- AGGREGATE ATTENDANCE CARD -->
            <div class="aggregate-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><i class="fas fa-chart-line"></i> Aggregate Attendance Summary</h5>
                    <i class="fas fa-users fa-2x"></i>
                </div>
                <div class="aggregate-stats">
                    <div class="aggregate-stat-item">
                        <div class="aggregate-stat-number" id="aggregateRSVPCount">0</div>
                        <div class="aggregate-stat-label"><i class="fas fa-calendar-check"></i> RSVPs</div>
                    </div>
                    <div class="aggregate-stat-item">
                        <div class="aggregate-stat-number" id="aggregateWalkinCount">0</div>
                        <div class="aggregate-stat-label"><i class="fas fa-person-walking-arrow-right"></i> Walk-ins</div>
                    </div>
                    <div class="aggregate-stat-item aggregate-total">
                        <div class="aggregate-stat-number" id="aggregateTotalCount">0</div>
                        <div class="aggregate-stat-label"><i class="fas fa-users"></i> TOTAL ATTENDEES</div>
                    </div>
                </div>
                <div class="text-center mt-3 small">
                    <i class="fas fa-info-circle"></i> Total attendance = RSVP confirmed guests + Walk-in attendees
                </div>
            </div>

            <!-- List of Attendees Section -->
            <div class="dashboard-section">
                <h2><i class="fas fa-list"></i> List of Attendees</h2>
                <p class="text-muted mb-3">Showing all RSVP guests and walk-in attendees combined</p>
                
                <!-- Table -->
                <div class="table-responsive mt-4">
                    <table class="rsvp-table">
                        <thead>
                            <tr>
                                <th>Attendee Name</th>
                                <th>Email / Contact</th>
                                <th>Type</th>
                                <th>Booth Applied?</th>
                                <th>Plus One / Notes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <tr><td colspan="6" class="text-center text-muted">Select an event to view attendees</td></tr>
                        </tbody>
                    </table>
                </div>

                <!-- Export Controls -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button class="btn btn-outline-secondary" onclick="exportCSV()">
                        <i class="fas fa-file-csv"></i> Export CSV
                    </button>
                    <button class="btn btn-outline-secondary" onclick="exportPDF()">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </button>
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

    <!-- RSVP Modal with Booth Application -->
    <div class="modal fade" id="rsvpModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New RSVP</h5>
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
                        <div class="mb-3">
                            <label for="plusOneName" class="form-label">Plus One Name (if applicable)</label>
                            <input type="text" class="form-control" id="plusOneName" placeholder="Enter name of plus one">
                        </div>
                        
                        <!-- NEW: Booth Application Section in RSVP Flow -->
                        <div class="booth-application-section">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="applyForBooth">
                                <label class="form-check-label fw-bold" for="applyForBooth">
                                    <i class="fas fa-booth-curtain"></i> Apply for a Booth
                                </label>
                                <div class="form-text">Check this if you want to reserve a booth for this event</div>
                            </div>
                            
                            <div id="boothApplicationDetails" style="display: none;">
                                <div class="mb-3">
                                    <label for="boothType" class="form-label">Booth Type</label>
                                    <select class="form-select" id="boothType">
                                        <option value="Standard">Standard Booth (10x10)</option>
                                        <option value="Premium">Premium Booth (10x20)</option>
                                        <option value="Corner">Corner Booth</option>
                                        <option value="Food">Food/Beverage Booth</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="boothPreferences" class="form-label">Special Requests / Preferences</label>
                                    <textarea class="form-control" id="boothPreferences" rows="2" placeholder="e.g., near entrance, power outlet needed, etc."></textarea>
                                </div>
                                <div class="alert alert-info small">
                                    <i class="fas fa-info-circle"></i> Booth application is subject to approval. You will be notified once confirmed.
                                </div>
                            </div>
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

    <!-- Walk-in Modal -->
    <div class="modal fade" id="walkinModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-person-walking-arrow-right"></i> Add Walk-in Attendee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="walkinForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="walkinName" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="walkinName" required>
                        </div>
                        <div class="mb-3">
                            <label for="walkinContact" class="form-label">Contact / Email</label>
                            <input type="text" class="form-control" id="walkinContact" placeholder="Email or phone number">
                        </div>
                        <div class="mb-3">
                            <label for="walkinNotes" class="form-label">Notes</label>
                            <textarea class="form-control" id="walkinNotes" rows="2" placeholder="Optional notes about this walk-in"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Add Walk-in</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Booth Management Modal -->
    <div class="modal fade" id="boothModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-booth-curtain"></i> Manage Event Booths</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="boothForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="totalBoothsInput" class="form-label">Total Number of Booths *</label>
                            <input type="number" class="form-control" id="totalBoothsInput" min="0" required>
                            <div class="form-text">Maximum booths available for this event</div>
                        </div>
                        <div class="mb-3">
                            <label for="reservedBoothsInput" class="form-label">Reserved / Occupied Booths *</label>
                            <input type="number" class="form-control" id="reservedBoothsInput" min="0" required>
                            <div class="form-text">Number of booths already booked or assigned</div>
                        </div>
                        <div class="alert alert-info">
                            <strong>Available Booths:</strong> <span id="previewAvailable">0</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save Booth Settings</button>
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
    
    <!-- Firebase Import -->
    <script type="module">
        import { db } from './Firebase/firebase_conn.js';
        import { collection, query, where, doc, getDocs, getDoc, addDoc, updateDoc, deleteDoc, serverTimestamp, orderBy } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-firestore.js";

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
    </script>
</body>
</html>