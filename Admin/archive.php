<?php
    require_once 'auth_guard.php';
    requireAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archive & Logs - CBOC</title>
    <link rel="icon" type="icon" href="CBOC LOGO.jpg"/>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3a0ca3;
            --success: #4cc9f0;
            --warning: #f39c12;
            --danger: #e74c3c;
            --dark: #2c3e50;
            --light: #f8f9fa;
            --bg-color: #f5f7fb;
            --card-bg: #ffffff;
            --text-color: #212529;
            --border-color: #dee2e6;
            --sidebar-width: 80px;
            --sidebar-expanded-width: 250px;
            --sidebar-bg: #1a1f36;
            --sidebar-color: #a0a7c2;
            --sidebar-hover-bg: rgba(255,255,255,0.1);
        }

        .dark-mode {
            --bg-color: #121212;
            --card-bg: #1e1e1e;
            --text-color: #e9ecef;
            --border-color: #343a40;
            --sidebar-bg: #0d1117;
            --sidebar-color: #c9d1d9;
            --sidebar-hover-bg: rgba(255,255,255,0.05);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            transition: background-color 0.3s, color 0.3s;
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: var(--sidebar-color);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
            z-index: 1000;
            padding-top: 20px;
            overflow: hidden;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar:hover {
            width: var(--sidebar-expanded-width);
        }

        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
            white-space: nowrap;
            opacity: 0;
            transform: translateX(-20px);
            transition: all 0.4s ease;
        }

        .sidebar:hover .sidebar-header {
            opacity: 1;
            transform: translateX(0);
            transition-delay: 0.1s;
        }

        .sidebar-header h3 {
            color: white;
            font-size: 1.5rem;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
        }

        .sidebar-menu li {
            margin-bottom: 5px;
            position: relative;
            overflow: hidden;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--sidebar-color);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            white-space: nowrap;
        }

        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: var(--sidebar-hover-bg);
            color: white;
            border-left: 3px solid var(--primary);
            transform: translateX(5px);
        }

        .sidebar-menu i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .sidebar-menu a:hover i {
            transform: scale(1.1);
        }

        .sidebar-menu span {
            opacity: 0;
            transform: translateX(-10px);
            transition: all 0.4s ease;
        }

        .sidebar:hover .sidebar-menu span {
            opacity: 1;
            transform: translateX(0);
        }

        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
        }

        .sidebar:hover ~ .main-content {
            margin-left: var(--sidebar-expanded-width);
        }

        /* Top Bar */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 10px;
            transition: transform 0.3s ease;
        }

        .user-avatar:hover {
            transform: scale(1.1);
        }

        /* Three Dots Menu */
        .three-dots-menu {
            position: relative;
            display: inline-block;
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

        /* Content Sections */
        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Archive Container */
        .archive-container, .logs-container {
            background: var(--card-bg);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            border: 1px solid var(--border-color);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Filter Tabs */
        .filter-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
            flex-wrap: wrap;
        }

        .filter-tab {
            padding: 8px 20px;
            border: 1px solid var(--border-color);
            background: transparent;
            color: var(--text-color);
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .filter-tab:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .filter-tab.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary);
            margin-bottom: 5px;
        }

        .stat-label {
            color: var(--text-color);
            opacity: 0.8;
            font-size: 0.9rem;
        }

        /* Archive Items */
        .archive-items, .log-items {
            margin-top: 20px;
        }

        .archive-item, .log-item {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .archive-item:hover, .log-item:hover {
            border-color: var(--primary);
            transform: translateX(5px);
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .item-title {
            font-weight: 600;
            color: var(--text-color);
            font-size: 1.1rem;
        }

        .item-type {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-right: 10px;
        }

        .type-member, .type-create { background: rgba(67, 97, 238, 0.1); color: var(--primary); }
        .type-event, .type-update { background: rgba(76, 201, 240, 0.1); color: var(--success); }
        .type-request, .type-delete { background: rgba(231, 76, 60, 0.1); color: var(--danger); }
        .type-system { background: rgba(52, 152, 219, 0.1); color: #3498db; }
        .type-user { background: rgba(155, 89, 182, 0.1); color: #9b59b6; }

        .item-meta {
            color: #6c757d;
            font-size: 0.85rem;
            margin-bottom: 10px;
        }

        .item-reason {
            background: rgba(0, 0, 0, 0.03);
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            font-size: 0.9rem;
            color: #6c757d;
        }

        .item-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid var(--border-color);
        }

        .btn-restore, .btn-delete, .btn-view {
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .btn-restore {
            background: var(--success);
            color: white;
        }

        .btn-restore:hover {
            background: #3aa8c4;
            transform: scale(1.05);
        }

        .btn-delete {
            background: var(--danger);
            color: white;
        }

        .btn-delete:hover {
            background: #c0392b;
            transform: scale(1.05);
        }

        .btn-view {
            background: var(--primary);
            color: white;
        }

        .btn-view:hover {
            background: #3a0ca3;
            transform: scale(1.05);
        }

        /* Filter Controls for Logs */
        .filter-controls {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .filter-select {
            padding: 8px 15px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            background: var(--card-bg);
            color: var(--text-color);
            font-size: 0.9rem;
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--primary);
        }

        .date-range {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .date-input {
            padding: 8px 15px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            background: var(--card-bg);
            color: var(--text-color);
            font-size: 0.9rem;
        }

        /* Search Box */
        .search-box {
            margin-bottom: 20px;
        }

        .search-input {
            width: 100%;
            padding: 12px 20px;
            border: 1px solid var(--border-color);
            border-radius: 25px;
            background: var(--card-bg);
            color: var(--text-color);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #dee2e6;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-icon {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-icon:hover {
            transform: translateY(-2px);
            opacity: 0.9;
        }

        /* Dark Mode Toggle */
        .dark-mode-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dark-mode-toggle:hover {
            transform: scale(1.1) rotate(15deg);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }
            
            .sidebar:hover {
                width: 70px;
            }
            
            .sidebar:hover ~ .main-content {
                margin-left: 70px;
            }
            
            .main-content {
                margin-left: 70px;
            }
            
            .sidebar-header, .sidebar-menu span {
                display: none;
            }
            
            .sidebar-menu i {
                margin-right: 0;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .filter-controls {
                flex-direction: column;
                align-items: stretch;
            }
            
            .date-range {
                flex-direction: column;
            }
        }
        
        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .item-header {
                flex-direction: column;
                gap: 10px;
            }
            
            .item-actions {
                flex-direction: column;
            }
            
            .btn-restore, .btn-delete, .btn-view {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-tachometer-alt"></i> CBOC</h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
            <li><a href="members.php"><i class="fas fa-users"></i> <span>Users</span></a></li>
            <li><a href="calendar.php"><i class="fas fa-calendar"></i> <span>Calendar</span></a></li>
            <li><a href="location.php"><i class="fas fa-map-marked-alt"></i><span>Location</span></a></li>
            <li><a href="request.php"><i class="fas fa-clipboard-list"></i> <span>Requests</span></a></li>
            <li><a href="ordercard.php"><i class="fas fa-credit-card"></i> <span>NFC Card</span></a></li>
            <li><a href="e-portfolio.php"><i class="fas fa-id-card"></i> <span>E-Portfolio</span></a></li>
            <li><a href="rsvptracker.php"><i class="fas fa-calendar-check"></i> <span>RSVP Tracker</span></a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar with Three Dots Menu -->
        <div class="top-bar">
            <h1><i class="fas fa-archive"></i> Archive & Logs</h1>
            <div class="d-flex align-items-center gap-3">
                <div class="three-dots-menu">
                    <button class="dots-button" id="dotsMenuBtn">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>
                    <div class="dropdown-menu-custom" id="dotsDropdown">
                        <button class="dropdown-item" onclick="switchSection('archive')">
                            <i class="fas fa-archive"></i> Archive
                        </button>
                        <button class="dropdown-item" onclick="switchSection('logs')">
                            <i class="fas fa-history"></i> Activity Logs
                        </button>
                        <div class="dropdown-divider"></div>
                        <button class="dropdown-item" onclick="exportCurrentSection()">
                            <i class="fas fa-download"></i> Export
                        </button>
                        <button class="dropdown-item" onclick="clearCurrentSection()">
                            <i class="fas fa-trash"></i> Clear
                        </button>
                        <button class="dropdown-item" onclick="refreshCurrentSection()">
                            <i class="fas fa-sync-alt"></i> Refresh
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

        <!-- Search Box -->
        <div class="search-box">
            <input type="text" class="search-input" placeholder="Search..." id="searchInput">
        </div>

        <!-- Stats Section (Changes based on active section) -->
        <div class="stats-grid" id="statsSection"></div>

        <!-- Archive Section -->
        <div class="content-section active" id="archiveSection">
            <div class="archive-container">
                <!-- Filter Tabs for Archive -->
                <div class="filter-tabs">
                    <button class="filter-tab active" data-filter="all">All Items</button>
                    <button class="filter-tab" data-filter="member">Members</button>
                    <button class="filter-tab" data-filter="event">Events</button>
                    <button class="filter-tab" data-filter="request">Requests</button>
                    <button class="filter-tab" data-filter="recent">Recently Archived</button>
                </div>

                <!-- Archive Items -->
                <h3 class="section-title"><i class="fas fa-box"></i> Archived Items</h3>
                <div class="archive-items" id="archiveList"></div>
            </div>
        </div>

        <!-- Logs Section -->
        <div class="content-section" id="logsSection">
            <div class="logs-container">
                <!-- Filter Controls for Logs -->
                <div class="filter-controls">
                    <div class="filter-group">
                        <label>Type:</label>
                        <select class="filter-select" id="typeFilter">
                            <option value="all">All Types</option>
                            <option value="create">Create</option>
                            <option value="update">Update</option>
                            <option value="delete">Delete</option>
                            <option value="system">System</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label>Module:</label>
                        <select class="filter-select" id="moduleFilter">
                            <option value="all">All Modules</option>
                            <option value="members">Members</option>
                            <option value="events">Events</option>
                            <option value="requests">Requests</option>
                            <option value="archive">Archive</option>
                            <option value="system">System</option>
                        </select>
                    </div>
                    
                    <div class="date-range">
                        <label>Date:</label>
                        <input type="date" class="date-input" id="startDate">
                        <span>to</span>
                        <input type="date" class="date-input" id="endDate">
                    </div>
                </div>

                <!-- Log Items -->
                <h3 class="section-title"><i class="fas fa-stream"></i> Activity History</h3>
                <div class="log-items" id="logsList"></div>
            </div>
        </div>
    </div>

    <!-- Dark Mode Toggle -->
    <button class="dark-mode-toggle" id="darkModeToggle">
        <i class="fas fa-moon" id="darkModeIcon"></i>
    </button>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize data
            let archiveData = JSON.parse(localStorage.getItem('cbocArchive')) || [];
            let activityLogs = JSON.parse(localStorage.getItem('cbocLogs')) || [];
            let currentSection = 'archive';
            
            // Load initial data
            loadArchiveData();
            loadLogs();
            updateStats('archive');
            
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
            
            // Search functionality
            document.getElementById('searchInput').addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                if (currentSection === 'archive') {
                    filterArchiveItems(searchTerm);
                } else {
                    filterLogs(searchTerm);
                }
            });
            
            // Archive filter tabs
            document.querySelectorAll('#archiveSection .filter-tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('#archiveSection .filter-tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    loadArchiveData(this.dataset.filter);
                });
            });
            
            // Logs filters
            document.getElementById('typeFilter').addEventListener('change', loadLogs);
            document.getElementById('moduleFilter').addEventListener('change', loadLogs);
            document.getElementById('startDate').addEventListener('change', loadLogs);
            document.getElementById('endDate').addEventListener('change', loadLogs);
            
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
            
            // Functions
            window.switchSection = function(section) {
                currentSection = section;
                
                // Hide all sections
                document.getElementById('archiveSection').classList.remove('active');
                document.getElementById('logsSection').classList.remove('active');
                
                // Show selected section
                document.getElementById(section + 'Section').classList.add('active');
                
                // Update stats
                updateStats(section);
                
                // Clear search
                document.getElementById('searchInput').value = '';
                
                // Reload data
                if (section === 'archive') {
                    loadArchiveData();
                } else {
                    loadLogs();
                }
            };
            
            window.exportCurrentSection = function() {
                if (currentSection === 'archive') {
                    exportArchiveData();
                } else {
                    exportLogs();
                }
            };
            
            window.clearCurrentSection = function() {
                if (currentSection === 'archive') {
                    if (confirm('Clear all archived items? This action cannot be undone.')) {
                        archiveData = [];
                        localStorage.setItem('cbocArchive', JSON.stringify(archiveData));
                        loadArchiveData();
                        updateStats('archive');
                        showNotification('Archive cleared');
                    }
                } else {
                    if (confirm('Clear all activity logs? This action cannot be undone.')) {
                        activityLogs = [];
                        localStorage.setItem('cbocLogs', JSON.stringify(activityLogs));
                        loadLogs();
                        updateStats('logs');
                        showNotification('Logs cleared');
                    }
                }
            };
            
            window.refreshCurrentSection = function() {
                if (currentSection === 'archive') {
                    loadArchiveData();
                    updateStats('archive');
                } else {
                    loadLogs();
                    updateStats('logs');
                }
                showNotification('Refreshed');
            };
            
            function loadArchiveData(filter = 'all') {
                const archiveList = document.getElementById('archiveList');
                
                // Initialize sample data if empty
                if (archiveData.length === 0) {
                    initializeSampleArchiveData();
                }
                
                let filteredData = archiveData;
                
                // Apply filters
                switch(filter) {
                    case 'member':
                        filteredData = archiveData.filter(item => item.type === 'member');
                        break;
                    case 'event':
                        filteredData = archiveData.filter(item => item.type === 'event');
                        break;
                    case 'request':
                        filteredData = archiveData.filter(item => item.type === 'request');
                        break;
                    case 'recent':
                        filteredData = archiveData
                            .sort((a, b) => new Date(b.archivedAt) - new Date(a.archivedAt))
                            .slice(0, 10);
                        break;
                }
                
                if (filteredData.length === 0) {
                    archiveList.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-box-open"></i>
                            <h4>No Archived Items</h4>
                            <p>No items found for this filter</p>
                        </div>
                    `;
                    return;
                }
                
                filteredData.sort((a, b) => new Date(b.archivedAt) - new Date(a.archivedAt));
                
                archiveList.innerHTML = filteredData.map(item => `
                    <div class="archive-item">
                        <div class="item-header">
                            <div>
                                <span class="item-type type-${item.type}">${item.type.toUpperCase()}</span>
                                <span class="item-title">${item.name}</span>
                            </div>
                            <small class="text-muted">${formatDate(item.archivedAt)}</small>
                        </div>
                        <div class="item-meta">
                            Archived by: <strong>${item.archivedBy}</strong> | 
                            Reason: <em>${item.reason}</em>
                        </div>
                        ${item.description ? `<div class="item-reason">${item.description}</div>` : ''}
                        <div class="item-actions">
                            <button class="btn-restore" onclick="restoreItem(${item.id})">
                                <i class="fas fa-undo me-1"></i> Restore
                            </button>
                            <button class="btn-delete" onclick="deleteItem(${item.id})">
                                <i class="fas fa-trash me-1"></i> Delete
                            </button>
                        </div>
                    </div>
                `).join('');
            }
            
            function loadLogs() {
                const logsList = document.getElementById('logsList');
                
                // Initialize sample data if empty
                if (activityLogs.length === 0) {
                    initializeSampleLogs();
                }
                
                // Get filter values
                const typeFilter = document.getElementById('typeFilter').value;
                const moduleFilter = document.getElementById('moduleFilter').value;
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;
                
                // Filter logs
                let filteredLogs = activityLogs;
                
                if (typeFilter !== 'all') {
                    filteredLogs = filteredLogs.filter(log => log.type === typeFilter);
                }
                
                if (moduleFilter !== 'all') {
                    filteredLogs = filteredLogs.filter(log => log.module === moduleFilter);
                }
                
                if (startDate) {
                    filteredLogs = filteredLogs.filter(log => new Date(log.timestamp) >= new Date(startDate));
                }
                
                if (endDate) {
                    const end = new Date(endDate);
                    end.setHours(23, 59, 59, 999);
                    filteredLogs = filteredLogs.filter(log => new Date(log.timestamp) <= end);
                }
                
                filteredLogs.sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp));
                
                if (filteredLogs.length === 0) {
                    logsList.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-clipboard-list"></i>
                            <h4>No Activity Logs</h4>
                            <p>No logs found for the selected filters</p>
                        </div>
                    `;
                    return;
                }
                
                logsList.innerHTML = filteredLogs.map(log => `
                    <div class="log-item">
                        <div class="item-header">
                            <span class="log-timestamp">${formatDateTime(log.timestamp)}</span>
                            <span class="item-type type-${log.type}">${log.type.toUpperCase()}</span>
                        </div>
                        <div class="item-title">${log.action}</div>
                        <div class="item-meta">
                            User: <strong>${log.user}</strong> | Module: ${log.module || 'General'}
                        </div>
                        ${log.details ? `<div class="item-reason">${log.details}</div>` : ''}
                        <div class="item-actions">
                            <button class="btn-view" onclick="viewLogDetails(${log.id})">
                                <i class="fas fa-eye me-1"></i> View Details
                            </button>
                        </div>
                    </div>
                `).join('');
            }
            
            function updateStats(section) {
                const statsSection = document.getElementById('statsSection');
                
                if (section === 'archive') {
                    const members = archiveData.filter(item => item.type === 'member').length;
                    const events = archiveData.filter(item => item.type === 'event').length;
                    const requests = archiveData.filter(item => item.type === 'request').length;
                    
                    statsSection.innerHTML = `
                        <div class="stat-card">
                            <div class="stat-number">${archiveData.length}</div>
                            <div class="stat-label">Total Archived</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">${members}</div>
                            <div class="stat-label">Archived Members</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">${events}</div>
                            <div class="stat-label">Archived Events</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">${requests}</div>
                            <div class="stat-label">Archived Requests</div>
                        </div>
                    `;
                } else {
                    const today = new Date().toDateString();
                    const todayLogs = activityLogs.filter(log => 
                        new Date(log.timestamp).toDateString() === today
                    ).length;
                    
                    const userLogs = activityLogs.filter(log => log.type === 'user').length;
                    const systemLogs = activityLogs.filter(log => log.type === 'system').length;
                    
                    statsSection.innerHTML = `
                        <div class="stat-card">
                            <div class="stat-number">${activityLogs.length}</div>
                            <div class="stat-label">Total Logs</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">${todayLogs}</div>
                            <div class="stat-label">Today's Activities</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">${userLogs}</div>
                            <div class="stat-label">User Actions</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">${systemLogs}</div>
                            <div class="stat-label">System Events</div>
                        </div>
                    `;
                }
            }
            
            function filterArchiveItems(searchTerm) {
                const items = document.querySelectorAll('#archiveList .archive-item');
                items.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    item.style.display = text.includes(searchTerm) ? 'block' : 'none';
                });
            }
            
            function filterLogs(searchTerm) {
                const items = document.querySelectorAll('#logsList .log-item');
                items.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    item.style.display = text.includes(searchTerm) ? 'block' : 'none';
                });
            }
            
            function initializeSampleArchiveData() {
                archiveData = [
                    {
                        id: 1,
                        type: 'member',
                        name: 'John Doe',
                        reason: 'Inactive for 6 months',
                        description: 'Member has not logged in or participated in any activities for over 6 months.',
                        archivedBy: 'Admin User',
                        archivedAt: '2024-01-15T10:30:00Z'
                    },
                    {
                        id: 2,
                        type: 'event',
                        name: 'Annual Meeting 2023',
                        reason: 'Completed',
                        description: 'Annual general meeting held on December 15, 2023. All minutes and documents archived.',
                        archivedBy: 'Admin User',
                        archivedAt: '2023-12-20T14:00:00Z'
                    },
                    {
                        id: 3,
                        type: 'request',
                        name: 'Equipment Request #45',
                        reason: 'Denied - Budget constraints',
                        description: 'Request for new laptops denied due to budget limitations.',
                        archivedBy: 'Admin User',
                        archivedAt: '2024-01-10T09:15:00Z'
                    }
                ];
                localStorage.setItem('cbocArchive', JSON.stringify(archiveData));
            }
            
            function initializeSampleLogs() {
                activityLogs = [
                    {
                        id: 1,
                        timestamp: new Date().toISOString(),
                        action: 'User logged in to the system',
                        user: 'Admin User',
                        type: 'user',
                        module: 'system',
                        details: 'Successful login from Chrome browser'
                    },
                    {
                        id: 2,
                        timestamp: new Date(Date.now() - 3600000).toISOString(),
                        action: 'New member added: John Doe',
                        user: 'Admin User',
                        type: 'create',
                        module: 'members',
                        details: 'Added new member with ID: MEM001'
                    },
                    {
                        id: 3,
                        timestamp: new Date(Date.now() - 7200000).toISOString(),
                        action: 'Event created: Annual Meeting 2024',
                        user: 'Admin User',
                        type: 'create',
                        module: 'events',
                        details: 'Scheduled for February 15, 2024'
                    }
                ];
                localStorage.setItem('cbocLogs', JSON.stringify(activityLogs));
            }
            
            window.restoreItem = function(id) {
                if (confirm('Restore this item?')) {
                    const index = archiveData.findIndex(item => item.id === id);
                    if (index > -1) {
                        archiveData.splice(index, 1);
                        localStorage.setItem('cbocArchive', JSON.stringify(archiveData));
                        loadArchiveData();
                        updateStats('archive');
                        showNotification('Item restored successfully!');
                    }
                }
            };
            
            window.deleteItem = function(id) {
                if (confirm('Permanently delete this item?')) {
                    const index = archiveData.findIndex(item => item.id === id);
                    if (index > -1) {
                        archiveData.splice(index, 1);
                        localStorage.setItem('cbocArchive', JSON.stringify(archiveData));
                        loadArchiveData();
                        updateStats('archive');
                        showNotification('Item deleted permanently!');
                    }
                }
            };
            
            window.viewLogDetails = function(id) {
                const log = activityLogs.find(l => l.id === id);
                if (log) {
                    alert(JSON.stringify(log, null, 2));
                }
            };
            
            function exportArchiveData() {
                const dataStr = JSON.stringify(archiveData, null, 2);
                downloadFile(dataStr, 'cboc-archive-export.json');
            }
            
            function exportLogs() {
                const dataStr = JSON.stringify(activityLogs, null, 2);
                downloadFile(dataStr, 'cboc-logs-export.json');
            }
            
            function downloadFile(data, filename) {
                const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(data);
                const linkElement = document.createElement('a');
                linkElement.setAttribute('href', dataUri);
                linkElement.setAttribute('download', filename);
                linkElement.click();
                showNotification('Export completed');
            }
            
            function formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
            
            function formatDateTime(dateString) {
                const date = new Date(dateString);
                return date.toLocaleString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
            }
            
            function showNotification(message) {
                const notification = document.createElement('div');
                notification.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: var(--primary);
                    color: white;
                    padding: 15px 20px;
                    border-radius: 5px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    z-index: 9999;
                    animation: slideIn 0.3s ease;
                `;
                notification.textContent = message;
                
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.style.animation = 'slideOut 0.3s ease';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }
            
            // Add CSS for animations
            const style = document.createElement('style');
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
        });
    </script>
</body>
</html>