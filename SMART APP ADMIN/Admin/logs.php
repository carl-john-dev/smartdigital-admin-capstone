<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs - CBOC</title>
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

        /* Sidebar Styles (Same as your design) */
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

        /* Logs Container */
        .logs-container {
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

        /* Log Stats */
        .log-stats {
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

        /* Filter Controls */
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

        /* Log Items */
        .log-items {
            max-height: 500px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .log-item {
            background: var(--card-bg);
            border-left: 4px solid var(--primary);
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 0 5px 5px 0;
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
        }

        .log-item:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .log-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
        }

        .log-timestamp {
            color: #6c757d;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .log-type {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .type-create { background: rgba(76, 201, 240, 0.1); color: var(--success); }
        .type-update { background: rgba(67, 97, 238, 0.1); color: var(--primary); }
        .type-delete { background: rgba(231, 76, 60, 0.1); color: var(--danger); }
        .type-system { background: rgba(52, 152, 219, 0.1); color: #3498db; }
        .type-user { background: rgba(155, 89, 182, 0.1); color: #9b59b6; }

        .log-action {
            font-weight: 500;
            margin-bottom: 5px;
            color: var(--text-color);
        }

        .log-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            font-size: 0.85rem;
        }

        .log-user {
            color: var(--primary);
            font-weight: 600;
        }

        .log-module {
            color: #6c757d;
            font-style: italic;
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

        /* Date Range Picker */
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

        /* Search Bar */
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

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
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
            
            .log-stats {
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
            .log-stats {
                grid-template-columns: 1fr;
            }
            
            .log-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .log-details {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
            
            .action-buttons {
                flex-direction: column;
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
            <li><a href="admin_profile.php"><i class="fas fa-id-card"></i> <span>Profile</span></a></li>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
            <li><a href="signup.php"><i class="fas fa-user-plus"></i> <span>Create Account</span></a></li>
            <li><a href="members.php"><i class="fas fa-users"></i> <span>Members</span></a></li>
            <li><a href="calendar.php"><i class="fas fa-calendar"></i> <span>Calendar</span></a></li>
            <li><a href="location.php"><i class="fas fa-map-marked-alt"></i><span>Location</span></a></li>
            <li><a href="request.php"><i class="fas fa-clipboard-list"></i> <span>Requests</span></a></li>
            <li><a href="archive.php" class=""><i class="fas fa-archive"></i> <span>Archive</span></a></li>
            <li><a href="#" class="active"><i class="fas fa-history"></i> <span>Activity Logs</span></a></li>
            <li><a href="e-portfolio.php"><i class="fas fa-id-card"></i> <span>E-Portfolio</span></a></li>
            <li><a href="rsvptracker.php"><i class="fas fa-calendar-check"></i> <span>RSVP Tracker</span></a></li>
            <li><a href="login.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <h1><i class="fas fa-history"></i> Activity Logs</h1>
            <div class="user-info">
                <div class="user-avatar">AD</div>
                <div>
                    <div class="fw-bold">Admin User</div>
                    <small class="text-muted">Auditor</small>
                </div>
            </div>
        </div>

        <!-- Search Box -->
        <div class="search-box">
            <input type="text" class="search-input" placeholder="Search activity logs..." id="searchInput">
        </div>

        <!-- Log Stats -->
        <div class="log-stats">
            <div class="stat-card">
                <div class="stat-number" id="totalLogs">0</div>
                <div class="stat-label">Total Logs</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="todayLogs">0</div>
                <div class="stat-label">Today's Activities</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="userLogs">0</div>
                <div class="stat-label">User Actions</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="systemLogs">0</div>
                <div class="stat-label">System Events</div>
            </div>
        </div>

        <!-- Filter Controls -->
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
                <label>User:</label>
                <select class="filter-select" id="userFilter">
                    <option value="all">All Users</option>
                    <option value="admin">Admin User</option>
                    <option value="system">System</option>
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

        <!-- Logs Container -->
        <div class="logs-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="section-title"><i class="fas fa-stream"></i> Activity History</h3>
                <div class="action-buttons">
                    <button class="btn-icon btn-success" id="refreshBtn">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <button class="btn-icon btn-danger" id="clearLogsBtn">
                        <i class="fas fa-trash"></i> Clear Logs
                    </button>
                    <button class="btn-icon btn-primary" id="exportLogsBtn">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
            </div>
            
            <div class="log-items" id="logsList">
                <!-- Log items will be loaded here -->
                <div class="empty-state">
                    <i class="fas fa-clipboard-list"></i>
                    <h4>No Activity Logs</h4>
                    <p>Activity logs will appear here</p>
                </div>
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
            let activityLogs = JSON.parse(localStorage.getItem('cbocLogs')) || [];
            
            // Load initial data
            loadLogs();
            updateStats();
            
            // Search functionality
            document.getElementById('searchInput').addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                filterLogs(searchTerm);
            });
            
            // Filter changes
            document.getElementById('typeFilter').addEventListener('change', loadLogs);
            document.getElementById('userFilter').addEventListener('change', loadLogs);
            document.getElementById('moduleFilter').addEventListener('change', loadLogs);
            document.getElementById('startDate').addEventListener('change', loadLogs);
            document.getElementById('endDate').addEventListener('change', loadLogs);
            
            // Refresh button
            document.getElementById('refreshBtn').addEventListener('click', function() {
                loadLogs();
                updateStats();
                showNotification('Logs refreshed');
            });
            
            // Clear logs button
            document.getElementById('clearLogsBtn').addEventListener('click', function() {
                if (confirm('Clear all activity logs? This action cannot be undone.')) {
                    activityLogs = [{
                        id: Date.now(),
                        timestamp: new Date().toISOString(),
                        action: 'All activity logs cleared',
                        user: 'Admin User',
                        type: 'system',
                        module: 'logs',
                        details: 'User cleared all activity logs'
                    }];
                    localStorage.setItem('cbocLogs', JSON.stringify(activityLogs));
                    loadLogs();
                    updateStats();
                    showNotification('Logs cleared');
                }
            });
            
            // Export logs button
            document.getElementById('exportLogsBtn').addEventListener('click', exportLogs);
            
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
            function loadLogs() {
                const logsList = document.getElementById('logsList');
                
                // Initialize sample data if empty
                if (activityLogs.length === 0) {
                    initializeSampleLogs();
                }
                
                // Get filter values
                const typeFilter = document.getElementById('typeFilter').value;
                const userFilter = document.getElementById('userFilter').value;
                const moduleFilter = document.getElementById('moduleFilter').value;
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;
                
                // Filter logs
                let filteredLogs = activityLogs;
                
                if (typeFilter !== 'all') {
                    filteredLogs = filteredLogs.filter(log => log.type === typeFilter);
                }
                
                if (userFilter !== 'all') {
                    filteredLogs = filteredLogs.filter(log => log.user.toLowerCase().includes(userFilter));
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
                
                // Sort by most recent
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
                        <div class="log-header">
                            <span class="log-timestamp">${formatDateTime(log.timestamp)}</span>
                            <span class="log-type type-${log.type}">${log.type.toUpperCase()}</span>
                        </div>
                        <div class="log-action">${log.action}</div>
                        <div class="log-details">
                            <span>User: <span class="log-user">${log.user}</span></span>
                            <span class="log-module">Module: ${log.module || 'General'}</span>
                        </div>
                        ${log.details ? `<small class="text-muted">${log.details}</small>` : ''}
                    </div>
                `).join('');
            }
            
            function filterLogs(searchTerm) {
                const logs = document.querySelectorAll('.log-item');
                logs.forEach(log => {
                    const text = log.textContent.toLowerCase();
                    log.style.display = text.includes(searchTerm) ? 'block' : 'none';
                });
            }
            
            function updateStats() {
                const today = new Date().toDateString();
                const todayLogs = activityLogs.filter(log => 
                    new Date(log.timestamp).toDateString() === today
                ).length;
                
                const userLogs = activityLogs.filter(log => log.type === 'user').length;
                const systemLogs = activityLogs.filter(log => log.type === 'system').length;
                
                document.getElementById('totalLogs').textContent = activityLogs.length;
                document.getElementById('todayLogs').textContent = todayLogs;
                document.getElementById('userLogs').textContent = userLogs;
                document.getElementById('systemLogs').textContent = systemLogs;
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
                    },
                    {
                        id: 4,
                        timestamp: new Date(Date.now() - 10800000).toISOString(),
                        action: 'Request #45 status updated',
                        user: 'Admin User',
                        type: 'update',
                        module: 'requests',
                        details: 'Status changed from Pending to Approved'
                    },
                    {
                        id: 5,
                        timestamp: new Date(Date.now() - 14400000).toISOString(),
                        action: 'Member archived: Jane Smith',
                        user: 'Admin User',
                        type: 'delete',
                        module: 'archive',
                        details: 'Archived due to resignation'
                    },
                    {
                        id: 6,
                        timestamp: new Date(Date.now() - 18000000).toISOString(),
                        action: 'System backup completed',
                        user: 'System',
                        type: 'system',
                        module: 'system',
                        details: 'Daily backup completed successfully'
                    },
                    {
                        id: 7,
                        timestamp: new Date(Date.now() - 21600000).toISOString(),
                        action: 'Password changed',
                        user: 'Admin User',
                        type: 'update',
                        module: 'system',
                        details: 'User changed their password'
                    },
                    {
                        id: 8,
                        timestamp: new Date(Date.now() - 25200000).toISOString(),
                        action: 'Export report generated',
                        user: 'Admin User',
                        type: 'create',
                        module: 'reports',
                        details: 'Monthly report exported to PDF'
                    }
                ];
                localStorage.setItem('cbocLogs', JSON.stringify(activityLogs));
            }
            
            function exportLogs() {
                const dataStr = JSON.stringify(activityLogs, null, 2);
                const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
                const exportFileDefaultName = 'cboc-activity-logs-export.json';
                
                const linkElement = document.createElement('a');
                linkElement.setAttribute('href', dataUri);
                linkElement.setAttribute('download', exportFileDefaultName);
                linkElement.click();
                
                showNotification('Logs exported successfully');
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
                // Create notification element
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
                
                // Remove after 3 seconds
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