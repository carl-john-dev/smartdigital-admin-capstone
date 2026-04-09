<?php
    define('SECURE_ACCESS', true);
    require_once 'auth_guard.php';
    requireAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Homepage - CBOC</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="icon" href="CBOC LOGO.jpg"/>
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
            cursor: pointer;
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

        /* Stats Container */
        .stats-container {
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

        /* Dashboard Sections */
        .dashboard-section {
            background: var(--card-bg);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid var(--border-color);
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .calendar-list {
            list-style: none;
            padding: 0;
        }

        .calendar-list li {
            padding: 10px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .calendar-list li:last-child {
            border-bottom: none;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 10px;
            color: #6c757d;
            font-weight: 600;
            font-size: 0.9rem;
        }

        td {
            padding: 10px;
            border-top: 1px solid var(--border-color);
        }

        .status {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-pending {
            background: rgba(243, 156, 18, 0.1);
            color: var(--warning);
        }

        .status-resolve {
            background: rgba(76, 201, 240, 0.1);
            color: var(--success);
        }

        /* Member Cards */
        .member-card {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .member-card:last-child {
            border-bottom: none;
        }

        .member-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .online {
            background-color: #28a745;
        }

        .offline {
            background-color: #dc3545;
        }

        .member-card h6 {
            margin: 0;
            font-size: 0.9rem;
        }
        .member-card p {
            margin: 0;
            font-size: 0.75rem;
            color: #f0f0f0;
        }

        .see-all {
            text-align: right;
            margin-top: 15px;
        }

        .see-all a {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .see-all a:hover {
            text-decoration: underline;
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
        
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        
        .toast {
            min-width: 250px;
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
            
            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 576px) {
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .top-bar {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <!-- Toast Notification Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-tachometer-alt"> </i>CBOC</h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="#" class="active"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
            <li><a href="members.php"><i class="fas fa-users"></i><span>Users</span></a></li>
            <li><a href="location.php"><i class="fas fa-map-marked-alt"></i><span>Location</span></a></li>
            <li><a href="e-portfolio.php"><i class="fas fa-id-card"></i><span>E-Portfolio</span></a></li>
            <li><a href="calendar.php"><i class="fas fa-calendar"></i><span>Calendar</span></a></li>
            <li><a href="rsvp.php"><i class="fas fa-calendar-check"></i><span>RSVP Tracker</span></a></li>
            <li><a href="request.php"><i class="fas fa-clipboard-list"></i><span>Approvals</span></a></li>
            <li><a href="ordercard.php"><i class="fas fa-shopping-cart"></i><span>NFC Card</span></a></li>
            <li>
                <form action="logout.php" method="POST" class="logout-form">
                    <button type="submit" class="sidebar-link">
                        <span> </span><i class="fas fa-sign-out-alt"></i><span>Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        
        <!-- Top Bar with Three Dots Menu -->
        <div class="top-bar">
            <h1>Dashboard</h1>
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
                        <button class="dropdown-item" id="refreshDashboard">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                        <button class="dropdown-item" id="exportDashboard">
                            <i class="fas fa-download"></i> Export Report
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

        <!-- Stats Section -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-number" id="memberCount">0</div>
                <div class="stat-label">Members</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="calendarCount">0</div>
                <div class="stat-label">Calendar</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="approvalCount">0</div>
                <div class="stat-label">Approval Requests</div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Calendar Section -->
                <div class="dashboard-section">
                    <h3 class="section-title"><i class="fas fa-calendar-alt"></i> Calendar</h3>
                    <ul class="calendar-list">
                        <!-- <li><strong>Request</strong> - Team meeting at 10:00 AM</li>
                        <li><strong>Request</strong> - Project deadline at 3:00 PM</li> -->
                    </ul>
                </div>

                <!-- Recent Activity Section -->
                <div class="dashboard-section">
                    <h3 class="section-title"><i class="fas fa-clock"></i> Recent</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Members</th>
                                <th>Todo</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="recentUsersTable">
                            <!-- <tr>
                                <td>Jonatan</td>
                                <td>Membership</td>
                                <td><span class="status status-pending">Pending</span></td>
                            </tr>
                            <tr>
                                <td>Halley</td>
                                <td>Request</td>
                                <td><span class="status status-resolve">Resolve</span></td>
                            </tr>
                            <tr>
                                <td>Nari</td>
                                <td>Membership</td>
                                <td><span class="status status-resolve">Resolve</span></td>
                            </tr>
                            <tr>
                                <td>Austin</td>
                                <td>Membership</td>
                                <td><span class="status status-resolve">Resolve</span></td>
                            </tr>
                            <tr>
                                <td>Isabelle</td>
                                <td>Request</td>
                                <td><span class="status status-resolve">Resolve</span></td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>

                <!-- Website Content Editor -->
                <div class="dashboard-section">
                    <h3 class="section-title">
                        <i class="fas fa-edit"></i> Website Content
                    </h3>

                    <p class="text-muted">Edit the public About section of the website.</p>

                    <button class="btn btn-primary" id="openAboutEditor">
                        <i class="fas fa-pen"></i> Edit About Section
                    </button>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Members Online Section -->
                <div class="dashboard-section">
                    <h3 class="section-title"><i class="fas fa-user-circle"></i> Members Online</h3>

                    <div class="d-flex gap-2 mb-3">
                        <button class="btn btn-sm btn-success" id="showOnlineBtn">Show Online</button>
                        <button class="btn btn-sm btn-secondary" id="showAllBtn">Show All</button>
                    </div>

                    <div id="membersOnlineContainer" class="d-flex flex-column gap-2">
                        <!-- Online/all member cards will be inserted here -->
                    </div>
                </div>

                <!-- New Members Section -->
                <div class="dashboard-section">
                    <h3 class="section-title"><i class="fas fa-user-plus"></i> New Members</h3>
        
                    <!-- Member Cards Container -->
                    <div id="newMembersContainer">
                        <!-- Dynamic member cards will be inserted here -->
                    </div>
                    
                    <!-- <div class="member-card">
                        <div class="member-avatar">LM</div>
                        <div>
                            <h6 class="mb-1">Lucia Merry</h6>
                            <p class="mb-0 text-muted small">CEO, THE MIST COP.</p>
                        </div>
                    </div>
                    
                    <div class="member-card">
                        <div class="member-avatar">ST</div>
                        <div>
                            <h6 class="mb-1">Sabrina Tan</h6>
                            <p class="mb-0 text-muted small">CEO, Realtyvale.</p>
                        </div>
                    </div>
                    
                    <div class="member-card">
                        <div class="member-avatar">AS</div>
                        <div>
                            <h6 class="mb-1">Andy Sewer</h6>
                            <p class="mb-0 text-muted small">CEO, Fawcettor.</p>
                        </div>
                    </div>
                    
                    <div class="member-card">
                        <div class="member-avatar">SM</div>
                        <div>
                            <h6 class="mb-1">Shanon Matilda</h6>
                            <p class="mb-0 text-muted small">CEO, Goldenfruit.</p>
                        </div>
                    </div>
                    
                    <div class="member-card">
                        <div class="member-avatar">EC</div>
                        <div>
                            <h6 class="mb-1">Ethan Cravejal</h6>
                            <p class="mb-0 text-muted small">CEO, Newcastle.</p>
                        </div>
                    </div> -->
                    
                    <div class="see-all">
                        <a href="members.php">See all <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dark Mode Toggle Button -->
    <button class="dark-mode-toggle" id="darkModeToggle">
        <i class="fas fa-moon" id="darkModeIcon"></i>
    </button>

    <!-- About Section Editor Modal -->
    <div class="modal fade" id="aboutEditorModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit About Section</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>CBOC Welcome Header (Text inside &lt;span&gt;&lt;/span&gt; wile be higlighted in orange)</label>
                        <textarea id="editWelcomeHeader" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>CBOC Welcome Text</label>
                        <textarea id="editWelcomeText" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>CBOC Background Text 1</label>
                        <textarea id="editBackground1" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>CBOC Background Text 2</label>
                        <textarea id="editBackground2" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>About Us Paragraph 1</label>
                        <textarea id="editAbout1" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>About Us Paragraph 2</label>
                        <textarea id="editAbout2" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Mission</label>
                        <textarea id="editMission" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Vision</label>
                        <textarea id="editVision" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Company Values</label>
                        <textarea id="editValues" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Founded Year</label>
                        <input type="text" id="editYear" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Founded Label</label>
                        <input type="text" id="editLabel" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Footer Text</label>
                        <input type="text" id="editFooterText" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Footer Copyright 1 (Can include HTML)</label>
                        <input type="text" id="editFooterCopyright1" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Footer Copyright 2 (Can include HTML)</label>
                        <input type="text" id="editFooterCopyright2" class="form-control">
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-success" id="saveAboutContent">Save Changes</button>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script nonce="<?= $nonce ?>" src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript for Interactive Elements -->
    <script nonce="<?= $nonce ?>" type="module" src="backend/dashboard.js"></script>
    <script nonce="<?= $nonce ?>" type="module" src="backend/backend.js"></script>
</body>
</html>