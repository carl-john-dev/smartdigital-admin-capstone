<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - Cavite Business Owners Club</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="icon" type="icon" href="calendar.png"/>
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3a0ca3;
            --success: #4cc9f0;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
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
            --gray: #adb5bd;
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

        .sidebar:hover ~ .main-content {
            margin-left: var(--sidebar-expanded-width);
        }

        /* Add staggered animation for menu items */
        .sidebar-menu li:nth-child(1) span { transition-delay: 0.05s; }
        .sidebar-menu li:nth-child(2) span { transition-delay: 0.1s; }
        .sidebar-menu li:nth-child(3) span { transition-delay: 0.15s; }
        .sidebar-menu li:nth-child(4) span { transition-delay: 0.2s; }
        .sidebar-menu li:nth-child(5) span { transition-delay: 0.25s; }
        .sidebar-menu li:nth-child(6) span { transition-delay: 0.3s; }
        .sidebar-menu li:nth-child(7) span { transition-delay: 0.35s; }

        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
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
            padding: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
        }

        .user-info:hover {
            background: rgba(67, 97, 238, 0.1);
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            margin-right: 10px;
            transition: transform 0.3s ease;
        }

        .user-info:hover .user-avatar {
            transform: scale(1.1);
        }

        .user-details .user-name {
            font-weight: bold;
            font-size: 1rem;
        }

        .user-details .user-role {
            font-size: 0.85rem;
            color: var(--gray);
        }

        /* Page Header */
        .page-header {
            margin-bottom: 30px;
        }

        .club-title {
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 5px;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .dark-mode .page-title {
            color: var(--light);
        }

        /* Profile Container */
        .profile-container {
            background: var(--card-bg);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            border: 1px solid var(--border-color);
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3.5rem;
            font-weight: bold;
            margin: 0 auto 20px;
            border: 5px solid var(--card-bg);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .profile-avatar:hover {
            transform: scale(1.05);
        }

        .profile-name {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--primary);
        }

        .profile-role {
            font-size: 1.2rem;
            color: var(--gray);
            margin-bottom: 15px;
        }

        .profile-badge {
            display: inline-block;
            background: var(--primary);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        /* Profile Info Grid */
        .profile-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .info-section {
            background: rgba(67, 97, 238, 0.05);
            border-radius: 10px;
            padding: 25px;
            border: 1px solid var(--border-color);
            transition: transform 0.3s ease;
        }

        .info-section:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            font-size: 1.2rem;
        }

        .info-item {
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .info-label {
            font-weight: 600;
            color: var(--dark);
            min-width: 120px;
        }

        .dark-mode .info-label {
            color: var(--light);
        }

        .info-value {
            flex: 1;
            text-align: right;
            color: var(--gray);
        }

        .info-value.editable {
            color: var(--primary);
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .info-value.editable:hover {
            color: var(--secondary);
        }

        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .stat-card:hover::before {
            left: 100%;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary);
            margin-bottom: 5px;
            transition: color 0.3s ease;
        }

        .stat-card:hover .stat-number {
            color: var(--secondary);
        }

        .stat-label {
            color: var(--gray);
            font-size: 0.9rem;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 40px;
        }

        .action-btn {
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .action-btn-primary {
            background: var(--primary);
            color: white;
        }

        .action-btn-primary:hover {
            background: var(--secondary);
            transform: translateY(-2px);
        }

        .action-btn-secondary {
            background: var(--card-bg);
            color: var(--text-color);
            border: 1px solid var(--border-color);
        }

        .action-btn-secondary:hover {
            background: rgba(67, 97, 238, 0.1);
            transform: translateY(-2px);
        }

        /* Edit Modal */
        .profile-modal .modal-content {
            background-color: var(--card-bg);
            color: var(--text-color);
            border: 1px solid var(--border-color);
        }

        .profile-modal .modal-header {
            border-bottom: 1px solid var(--border-color);
        }

        .profile-modal .modal-footer {
            border-top: 1px solid var(--border-color);
        }

        .profile-form label {
            color: var(--text-color);
            font-weight: 600;
        }

        .profile-form .form-control, .profile-form .form-select {
            background-color: var(--card-bg);
            color: var(--text-color);
            border: 1px solid var(--border-color);
        }

        .profile-form .form-control:focus, .profile-form .form-select:focus {
            background-color: var(--card-bg);
            color: var(--text-color);
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
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

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1002;
            width: 40px;
            height: 40px;
            border-radius: 5px;
            background: var(--primary);
            color: white;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
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
            
            .calendar-day {
                min-height: 80px;
            }
            
            .event-item {
                font-size: 0.7rem;
                padding: 1px 3px;
            }
            
            .event-card-meta {
                flex-direction: column;
                gap: 8px;
            }

            .ph-time-clock {
                font-size: 0.85rem;
                padding: 4px 8px;
            }
            
            #phTime {
                font-size: 1rem;
                margin: 0 5px;
            }
        }

        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .sidebar {
                width: 0;
                transform: translateX(-100%);
                transition: all 0.3s ease;
            }
            
            .sidebar.mobile-open {
                width: 250px;
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                padding: 70px 15px 15px 15px;
            }
            
            .sidebar.expanded ~ .main-content {
                margin-left: 0;
            }
            
            .sidebar-header, .sidebar-menu span {
                opacity: 1;
                transform: translateX(0);
                display: block;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .profile-info-grid {
                grid-template-columns: 1fr;
            }
            
            .profile-container {
                padding: 20px;
            }
            
            .profile-avatar {
                width: 120px;
                height: 120px;
                font-size: 2.5rem;
            }
            
            .profile-name {
                font-size: 1.8rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-tachometer-alt"></i> Dashboard</h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="#" class="active"><i class="fas fa-id-card"></i> <span>Profile</span></a></li>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
            <li><a href="signup.php"><i class="fas fa-user-plus"></i> <span>Create Account</span></a></li>
            <li><a href="members.php"><i class="fas fa-users"></i> <span>Members</span></a></li>
            <li><a href="calendar.php"><i class="fas fa-calendar"></i> <span>Calendar</span></a></li>
            <li><a href="location.php"><i class="fas fa-map-marked-alt"></i><span>Location</span></a></li>
            <li><a href="request.php"><i class="fas fa-clipboard-list"></i> <span>Requests</span></a></li>
            <li><a href="archive.php" class=""><i class="fas fa-archive"></i> <span>Archive</span></a></li>
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
            <div class="page-header">
                <div class="club-title">Cavite Business Owners Club</div>
                <h1 class="page-title">Admin Profile</h1>
            </div>
            <div class="user-info" id="profileLink">
                <div class="user-avatar">AD</div>
                <div class="user-details">
                    <div class="user-name">Admin User</div>
                    <div class="user-role">Administrator</div>
                </div>
            </div>
        </div>

        <!-- Profile Container -->
        <div class="profile-container">
            <!-- Profile Header -->
            <div class="profile-header">
                <div class="profile-avatar" id="profileAvatar">AD</div>
                <h2 class="profile-name" id="profileName">Admin User</h2>
                <div class="profile-role" id="profileRole">System Administrator</div>
                <span class="profile-badge">Super Admin</span>
            </div>

            <!-- Admin Statistics -->
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-number" id="totalMembers">845</div>
                    <div class="stat-label">Total Members</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                    <div class="stat-number" id="totalRevenue">₱84,500</div>
                    <div class="stat-label">Total Revenue</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-clipboard-list"></i></div>
                    <div class="stat-number" id="pendingRequests">45</div>
                    <div class="stat-label">Pending Requests</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-number" id="activeEvents">12</div>
                    <div class="stat-label">Active Events</div>
                </div>
            </div>

            <!-- Profile Information Grid -->
            <div class="profile-info-grid">
                <!-- Personal Information -->
                <div class="info-section">
                    <h3 class="section-title"><i class="fas fa-user-circle"></i> Personal Information</h3>
                    <div class="info-item">
                        <div class="info-label">Full Name:</div>
                        <div class="info-value editable" data-field="name">Admin User</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email:</div>
                        <div class="info-value editable" data-field="email">admin@cboc.ph</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Phone:</div>
                        <div class="info-value editable" data-field="phone">+63 912 345 6789</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Position:</div>
                        <div class="info-value editable" data-field="position">System Administrator</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Joined:</div>
                        <div class="info-value">January 15, 2023</div>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="info-section">
                    <h3 class="section-title"><i class="fas fa-shield-alt"></i> Account Information</h3>
                    <div class="info-item">
                        <div class="info-label">Username:</div>
                        <div class="info-value">admin_user</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">User ID:</div>
                        <div class="info-value">ADM-001</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Role:</div>
                        <div class="info-value">Super Administrator</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Permissions:</div>
                        <div class="info-value">Full Access</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Last Login:</div>
                        <div class="info-value" id="lastLogin">Today, 09:45 AM</div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="info-section">
                    <h3 class="section-title"><i class="fas fa-address-book"></i> Contact Information</h3>
                    <div class="info-item">
                        <div class="info-label">Office Address:</div>
                        <div class="info-value editable" data-field="address">CBOC Headquarters, Rosario, Cavite</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Office Phone:</div>
                        <div class="info-value editable" data-field="officePhone">(046) 123-4567</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Emergency Contact:</div>
                        <div class="info-value editable" data-field="emergency">+63 917 123 4567</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Department:</div>
                        <div class="info-value">Administration & IT</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Working Hours:</div>
                        <div class="info-value">8:00 AM - 5:00 PM</div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button class="action-btn action-btn-primary" id="editProfileBtn">
                    <i class="fas fa-edit"></i> Edit Profile
                </button>
                <button class="action-btn action-btn-secondary" id="changePasswordBtn">
                    <i class="fas fa-key"></i> Change Password
                </button>
                <button class="action-btn action-btn-secondary" id="viewActivityBtn">
                    <i class="fas fa-history"></i> View Activity Log
                </button>
                <button class="action-btn action-btn-secondary" id="backToDashboard">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade profile-modal" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="profileForm" class="profile-form">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="editName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="editEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPhone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="editPhone">
                        </div>
                        <div class="mb-3">
                            <label for="editPosition" class="form-label">Position</label>
                            <input type="text" class="form-control" id="editPosition">
                        </div>
                        <div class="mb-3">
                            <label for="editAddress" class="form-label">Office Address</label>
                            <textarea class="form-control" id="editAddress" rows="2"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveProfileBtn">Save Changes</button>
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
    
    <!-- JavaScript for Profile Page -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ========== SIDEBAR MANAGEMENT ==========
            const sidebar = document.getElementById('sidebar');
            const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const sidebarLinks = document.querySelectorAll('.sidebar-menu a');
            
            // Check saved sidebar state
            let isSidebarExpanded = localStorage.getItem('sidebarExpanded') === 'true';
            
            // Initialize sidebar
            function initSidebar() {
                if (isSidebarExpanded) {
                    expandSidebar();
                } else {
                    collapseSidebar();
                }
                
                // Set active link based on current page
                setActiveLink();
            }
            
            // Toggle sidebar function
            function toggleSidebar() {
                if (isSidebarExpanded) {
                    collapseSidebar();
                } else {
                    expandSidebar();
                }
            }
            
            function expandSidebar() {
                sidebar.classList.add('expanded');
                isSidebarExpanded = true;
                localStorage.setItem('sidebarExpanded', 'true');
                
                // Update toggle button icon
                if (sidebarToggleBtn) {
                    sidebarToggleBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
                }
            }
            
            function collapseSidebar() {
                sidebar.classList.remove('expanded');
                isSidebarExpanded = false;
                localStorage.setItem('sidebarExpanded', 'false');
                
                // Update toggle button icon
                if (sidebarToggleBtn) {
                    sidebarToggleBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
                }
            }
            
            // Set active link based on current page
            function setActiveLink() {
                const currentPage = window.location.pathname.split('/').pop() || 'index.php';
                sidebarLinks.forEach(link => {
                    const linkHref = link.getAttribute('href');
                    if (linkHref === currentPage || 
                        (currentPage === '' && linkHref === 'index.php')) {
                        link.classList.add('active');
                    } else {
                        link.classList.remove('active');
                    }
                });
            }
            
            // Event Listeners for Sidebar
            if (sidebarToggleBtn) {
                sidebarToggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleSidebar();
                });
            }
            
            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('mobile-open');
                });
            }
            
            // Close mobile sidebar when clicking outside
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768 && 
                    !sidebar.contains(e.target) && 
                    !mobileMenuToggle.contains(e.target)) {
                    sidebar.classList.remove('mobile-open');
                }
            });
            
            // ========== PROFILE MANAGEMENT ==========
            // Load admin profile data from localStorage or use default
            let adminProfile = JSON.parse(localStorage.getItem('adminProfile')) || {
                name: 'Admin User',
                email: 'admin@cboc.ph',
                phone: '+63 912 345 6789',
                position: 'System Administrator',
                address: 'CBOC Headquarters, Rosario, Cavite',
                officePhone: '(046) 123-4567',
                emergency: '+63 917 123 4567',
                lastLogin: 'Today, 09:45 AM'
            };

            // Load admin stats
            let adminStats = JSON.parse(localStorage.getItem('adminStats')) || {
                totalMembers: 845,
                totalRevenue: '₱84,500',
                pendingRequests: 45,
                activeEvents: 12
            };

            // Initialize profile page
            function initProfilePage() {
                // Set profile information
                document.getElementById('profileName').textContent = adminProfile.name;
                document.getElementById('profileRole').textContent = adminProfile.position;
                document.querySelectorAll('.info-value[data-field="name"]').forEach(el => el.textContent = adminProfile.name);
                document.querySelectorAll('.info-value[data-field="email"]').forEach(el => el.textContent = adminProfile.email);
                document.querySelectorAll('.info-value[data-field="phone"]').forEach(el => el.textContent = adminProfile.phone);
                document.querySelectorAll('.info-value[data-field="position"]').forEach(el => el.textContent = adminProfile.position);
                document.querySelectorAll('.info-value[data-field="address"]').forEach(el => el.textContent = adminProfile.address);
                document.querySelectorAll('.info-value[data-field="officePhone"]').forEach(el => el.textContent = adminProfile.officePhone);
                document.querySelectorAll('.info-value[data-field="emergency"]').forEach(el => el.textContent = adminProfile.emergency);
                document.getElementById('lastLogin').textContent = adminProfile.lastLogin;

                // Set stats
                document.getElementById('totalMembers').textContent = adminStats.totalMembers;
                document.getElementById('totalRevenue').textContent = adminStats.totalRevenue;
                document.getElementById('pendingRequests').textContent = adminStats.pendingRequests;
                document.getElementById('activeEvents').textContent = adminStats.activeEvents;
            }

            // Profile link in top bar - Clicking anywhere on the user info goes to profile
            document.getElementById('profileLink').addEventListener('click', function() {
                // Already on profile page, could refresh or do nothing
                // In a multi-page app, this would navigate to profile.php
                initProfilePage();
            });

            // Edit Profile Button
            document.getElementById('editProfileBtn').addEventListener('click', function() {
                // Populate modal with current data
                document.getElementById('editName').value = adminProfile.name;
                document.getElementById('editEmail').value = adminProfile.email;
                document.getElementById('editPhone').value = adminProfile.phone;
                document.getElementById('editPosition').value = adminProfile.position;
                document.getElementById('editAddress').value = adminProfile.address;
                
                // Show modal
                new bootstrap.Modal(document.getElementById('editProfileModal')).show();
            });

            // Save Profile Changes
            document.getElementById('saveProfileBtn').addEventListener('click', function() {
                // Get updated values
                adminProfile.name = document.getElementById('editName').value;
                adminProfile.email = document.getElementById('editEmail').value;
                adminProfile.phone = document.getElementById('editPhone').value;
                adminProfile.position = document.getElementById('editPosition').value;
                adminProfile.address = document.getElementById('editAddress').value;

                // Save to localStorage
                localStorage.setItem('adminProfile', JSON.stringify(adminProfile));

                // Update UI
                initProfilePage();

                // Close modal
                bootstrap.Modal.getInstance(document.getElementById('editProfileModal')).hide();

                // Show success message
                alert('Profile updated successfully!');
            });

            // Change Password Button
            document.getElementById('changePasswordBtn').addEventListener('click', function() {
                const newPassword = prompt('Enter new password:');
                if (newPassword) {
                    const confirmPassword = prompt('Confirm new password:');
                    if (newPassword === confirmPassword) {
                        alert('Password changed successfully!');
                    } else {
                        alert('Passwords do not match!');
                    }
                }
            });

            // View Activity Log
            document.getElementById('viewActivityBtn').addEventListener('click', function() {
                alert('Activity log would open here!\n\nRecent activities:\n- Logged in at 09:45 AM\n- Approved 3 requests\n- Updated member information\n- Generated monthly report');
            });

            // Back to Dashboard Button
            document.getElementById('backToDashboard').addEventListener('click', function() {
                // Simulate navigation to dashboard
                alert('Navigating back to Dashboard...');
                setActiveLink();
            });

            // Click on editable fields
            document.querySelectorAll('.info-value.editable').forEach(field => {
                field.addEventListener('click', function() {
                    const fieldName = this.getAttribute('data-field');
                    const currentValue = this.textContent;
                    const newValue = prompt(`Edit ${fieldName}:`, currentValue);
                    
                    if (newValue && newValue !== currentValue) {
                        adminProfile[fieldName] = newValue;
                        localStorage.setItem('adminProfile', JSON.stringify(adminProfile));
                        initProfilePage();
                    }
                });
            });

            // ========== DARK MODE TOGGLE ==========
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

            // ========== INITIALIZE EVERYTHING ==========
            initSidebar();
            initProfilePage();

            // Add animation to profile avatar
            const profileAvatar = document.getElementById('profileAvatar');
            profileAvatar.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.05) rotate(5deg)';
            });
            
            profileAvatar.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1) rotate(0deg)';
            });

            // Add subtle animation to stat cards
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('mobile-open');
                }
            });
        });
    </script>
</body>
</html>