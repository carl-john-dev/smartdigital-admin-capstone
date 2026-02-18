<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archive - CBOC </title>
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

        /* Archive Container */
        .archive-container {
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

        /* Archive Stats */
        .archive-stats {
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
        .archive-items {
            margin-top: 20px;
        }

        .archive-item {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .archive-item:hover {
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

        .type-member {
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary);
        }

        .type-event {
            background: rgba(76, 201, 240, 0.1);
            color: var(--success);
        }

        .type-request {
            background: rgba(231, 76, 60, 0.1);
            color: var(--danger);
        }

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

        .btn-restore {
            background: var(--success);
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .btn-restore:hover {
            background: #3aa8c4;
            transform: scale(1.05);
        }

        .btn-delete {
            background: var(--danger);
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .btn-delete:hover {
            background: #c0392b;
            transform: scale(1.05);
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
            
            .archive-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 576px) {
            .archive-stats {
                grid-template-columns: 1fr;
            }
            
            .item-header {
                flex-direction: column;
                gap: 10px;
            }
            
            .item-actions {
                flex-direction: column;
            }
            
            .btn-restore, .btn-delete {
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
            <li><a href="admin_profile.php"><i class="fas fa-id-card"></i> <span>Profile</span></a></li>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
            <li><a href="signup.php"><i class="fas fa-user-plus"></i> <span>Create Account</span></a></li>
            <li><a href="members.php"><i class="fas fa-users"></i> <span>Members</span></a></li>
            <li><a href="calendar.php"><i class="fas fa-calendar"></i> <span>Calendar</span></a></li>
            <li><a href="location.php"><i class="fas fa-map-marked-alt"></i><span>Location</span></a></li>
            <li><a href="request.php"><i class="fas fa-clipboard-list"></i> <span>Requests</span></a></li>
            <li><a href="#" class="active"><i class="fas fa-archive"></i> <span>Archive</span></a></li>
            <li><a href="logs.php"><i class="fas fa-history"></i> <span>Activity Logs</span></a></li>
            <li><a href="e-portfolio.php"><i class="fas fa-id-card"></i> <span>E-Portfolio</span></a></li>
            <li><a href="rsvptracker.php"><i class="fas fa-calendar-check"></i> <span>RSVP Tracker</span></a></li>
            <li><a href="login.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <h1><i class="fas fa-archive"></i> Archive</h1>
            <div class="user-info">
                <div class="user-avatar">AD</div>
                <div>
                    <div class="fw-bold">Admin User</div>
                    <small class="text-muted">Archivist</small>
                </div>
            </div>
        </div>

        <!-- Search Box -->
        <div class="search-box">
            <input type="text" class="search-input" placeholder="Search archived items..." id="searchInput">
        </div>

        <!-- Archive Stats -->
        <div class="archive-stats">
            <div class="stat-card">
                <div class="stat-number" id="totalArchived">0</div>
                <div class="stat-label">Total Archived</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="archivedMembers">0</div>
                <div class="stat-label">Archived Members</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="archivedEvents">0</div>
                <div class="stat-label">Archived Events</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="archivedRequests">0</div>
                <div class="stat-label">Archived Requests</div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <button class="filter-tab active" data-filter="all">All Items</button>
            <button class="filter-tab" data-filter="member">Members</button>
            <button class="filter-tab" data-filter="event">Events</button>
            <button class="filter-tab" data-filter="request">Requests</button>
            <button class="filter-tab" data-filter="recent">Recently Archived</button>
        </div>

        <!-- Archive Items Container -->
        <div class="archive-container">
            <h3 class="section-title"><i class="fas fa-box"></i> Archived Items</h3>
            
            <div class="archive-items" id="archiveList">
                <!-- Archive items will be loaded here -->
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <h4>No Archived Items</h4>
                    <p>Archived items will appear here</p>
                </div>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div class="archive-container">
            <h3 class="section-title"><i class="fas fa-tasks"></i> Bulk Actions</h3>
            <div class="d-flex gap-2">
                <button class="btn btn-primary" id="bulkRestoreBtn">
                    <i class="fas fa-undo me-2"></i> Restore Selected
                </button>
                <button class="btn btn-danger" id="bulkDeleteBtn">
                    <i class="fas fa-trash me-2"></i> Delete Selected
                </button>
                <button class="btn btn-outline-primary" id="exportBtn">
                    <i class="fas fa-download me-2"></i> Export Archive
                </button>
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
            
            // Load initial data
            loadArchiveData();
            updateStats();
            
            // Filter tabs
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    loadArchiveData(this.dataset.filter);
                });
            });
            
            // Search functionality
            document.getElementById('searchInput').addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                filterArchiveItems(searchTerm);
            });
            
            // Bulk restore
            document.getElementById('bulkRestoreBtn').addEventListener('click', function() {
                const selectedItems = document.querySelectorAll('.archive-checkbox:checked');
                if (selectedItems.length === 0) {
                    alert('Please select items to restore');
                    return;
                }
                
                if (confirm(`Restore ${selectedItems.length} item(s)?`)) {
                    selectedItems.forEach(checkbox => {
                        const itemId = parseInt(checkbox.dataset.id);
                        restoreItem(itemId);
                    });
                    loadArchiveData();
                    updateStats();
                    alert('Selected items restored successfully!');
                }
            });
            
            // Bulk delete
            document.getElementById('bulkDeleteBtn').addEventListener('click', function() {
                const selectedItems = document.querySelectorAll('.archive-checkbox:checked');
                if (selectedItems.length === 0) {
                    alert('Please select items to delete');
                    return;
                }
                
                if (confirm(`Permanently delete ${selectedItems.length} item(s)? This action cannot be undone.`)) {
                    selectedItems.forEach(checkbox => {
                        const itemId = parseInt(checkbox.dataset.id);
                        deleteItem(itemId);
                    });
                    loadArchiveData();
                    updateStats();
                    alert('Selected items deleted permanently!');
                }
            });
            
            // Export functionality
            document.getElementById('exportBtn').addEventListener('click', function() {
                exportArchiveData();
            });
            
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
                
                // Sort by most recent
                filteredData.sort((a, b) => new Date(b.archivedAt) - new Date(a.archivedAt));
                
                archiveList.innerHTML = filteredData.map(item => `
                    <div class="archive-item">
                        <div class="item-header">
                            <div>
                                <input type="checkbox" class="archive-checkbox" data-id="${item.id}">
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
                            <button class="btn-restore" onclick="restoreSingleItem(${item.id})">
                                <i class="fas fa-undo me-1"></i> Restore
                            </button>
                            <button class="btn-delete" onclick="deleteSingleItem(${item.id})">
                                <i class="fas fa-trash me-1"></i> Delete
                            </button>
                        </div>
                    </div>
                `).join('');
            }
            
            function filterArchiveItems(searchTerm) {
                const items = document.querySelectorAll('.archive-item');
                items.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    item.style.display = text.includes(searchTerm) ? 'block' : 'none';
                });
            }
            
            function updateStats() {
                const members = archiveData.filter(item => item.type === 'member').length;
                const events = archiveData.filter(item => item.type === 'event').length;
                const requests = archiveData.filter(item => item.type === 'request').length;
                
                document.getElementById('totalArchived').textContent = archiveData.length;
                document.getElementById('archivedMembers').textContent = members;
                document.getElementById('archivedEvents').textContent = events;
                document.getElementById('archivedRequests').textContent = requests;
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
                    },
                    {
                        id: 4,
                        type: 'member',
                        name: 'Jane Smith',
                        reason: 'Resigned',
                        description: 'Member resigned from the organization effective January 1, 2024.',
                        archivedBy: 'Admin User',
                        archivedAt: '2024-01-05T16:45:00Z'
                    },
                    {
                        id: 5,
                        type: 'event',
                        name: 'Community Workshop',
                        reason: 'Cancelled',
                        description: 'Workshop cancelled due to low registration numbers.',
                        archivedBy: 'Admin User',
                        archivedAt: '2023-11-30T11:20:00Z'
                    }
                ];
                localStorage.setItem('cbocArchive', JSON.stringify(archiveData));
            }
            
            function restoreItem(id) {
                const index = archiveData.findIndex(item => item.id === id);
                if (index > -1) {
                    archiveData.splice(index, 1);
                    localStorage.setItem('cbocArchive', JSON.stringify(archiveData));
                }
            }
            
            function deleteItem(id) {
                restoreItem(id); // For now, same as restore
            }
            
            function exportArchiveData() {
                const dataStr = JSON.stringify(archiveData, null, 2);
                const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
                const exportFileDefaultName = 'cboc-archive-export.json';
                
                const linkElement = document.createElement('a');
                linkElement.setAttribute('href', dataUri);
                linkElement.setAttribute('download', exportFileDefaultName);
                linkElement.click();
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
            
            // Global functions for button clicks
            window.restoreSingleItem = function(id) {
                if (confirm('Restore this item?')) {
                    restoreItem(id);
                    loadArchiveData();
                    updateStats();
                    alert('Item restored successfully!');
                }
            };
            
            window.deleteSingleItem = function(id) {
                if (confirm('Permanently delete this item?')) {
                    deleteItem(id);
                    loadArchiveData();
                    updateStats();
                    alert('Item deleted permanently!');
                }
            };
        });
    </script>
</body>
</html>