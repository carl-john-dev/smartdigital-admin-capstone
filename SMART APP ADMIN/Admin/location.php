<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maps - Dashboard</title>
    <link rel="icon" type="icon" href="location.png"/>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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

        /* Map Container */
        .map-container {
            background: var(--card-bg);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .map-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .map-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary);
        }

        .map-controls {
            display: flex;
            gap: 10px;
        }

        .map-control-btn {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 15px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .map-control-btn:hover {
            background: var(--secondary);
            transform: scale(1.05);
        }

        #map {
            height: 600px;
            width: 100%;
            border-radius: 8px;
            z-index: 1;
        }

        /* User Location Cards */
        .user-card {
            background: var(--card-bg);
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 15px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .user-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-left: 4px solid var(--primary);
        }

        .user-card.active {
            border-left: 4px solid var(--primary);
            background: rgba(67, 97, 238, 0.05);
        }

        .user-card-title {
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--primary);
        }

        .user-card-address {
            font-size: 0.9rem;
            color: var(--gray);
            margin-bottom: 8px;
        }

        .user-status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-active {
            background-color: #d1f7e4;
            color: #0f5132;
        }

        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        /* Role type colors */
        .role-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-right: 5px;
        }

        .role-admin {
            background-color: #e3f2fd;
            color: #1565c0;
        }

        .role-user {
            background-color: #f3e5f5;
            color: #7b1fa2;
        }

        .role-moderator {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        /* Profile Picture Styles */
        .user-profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            flex-shrink: 0;
            border: 2px solid var(--primary);
        }

        /* Profile Picture Marker */
        .profile-pic-marker {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            border: 3px solid white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }

        .profile-pic-marker.active {
            border-color: #4CAF50;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.3);
        }

        .profile-pic-marker.inactive {
            border-color: #f44336;
            box-shadow: 0 0 0 3px rgba(244, 67, 54, 0.3);
        }

        .profile-pic-marker.pending {
            border-color: #ff9800;
            box-shadow: 0 0 0 3px rgba(255, 152, 0, 0.3);
        }

        /* Current User Marker */
        .current-user-marker {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            border: 3px solid #2196F3;
            box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.3);
        }

        /* Stats Cards */
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

        /* Action Buttons on Cards */
        .user-card-actions {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 5px;
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 2;
        }

        .user-card:hover .user-card-actions {
            opacity: 1;
        }

        .user-action-btn {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .edit-user-btn {
            background: rgba(13, 110, 253, 0.1);
            border: 1px solid rgba(13, 110, 253, 0.3);
            color: #0d6efd;
        }

        .edit-user-btn:hover {
            background: #0d6efd;
            color: white;
            transform: scale(1.1);
        }

        .delete-user-btn {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #dc3545;
        }

        .delete-user-btn:hover {
            background: #dc3545;
            color: white;
            transform: scale(1.1);
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

        /* Modal Styles */
        .user-modal .modal-content {
            background-color: var(--card-bg);
            color: var(--text-color);
            border: 1px solid var(--border-color);
        }

        .user-modal .modal-header {
            border-bottom: 1px solid var(--border-color);
        }

        .user-modal .modal-footer {
            border-top: 1px solid var(--border-color);
        }

        .user-form label {
            color: var(--text-color);
        }

        .user-form .form-control, .user-form .form-select {
            background-color: var(--card-bg);
            color: var(--text-color);
            border: 1px solid var(--border-color);
        }

        .user-form .form-control:focus, .user-form .form-select:focus {
            background-color: var(--card-bg);
            color: var(--text-color);
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }

        /* Profile Preview in Modal */
        .profile-preview {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
            padding: 20px;
            background: rgba(var(--primary-rgb, 67, 97, 238), 0.05);
            border-radius: 10px;
        }

        .profile-pic-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            margin-bottom: 15px;
            border: 3px solid var(--primary);
            background-color: #f8f9fa;
            overflow: hidden;
            position: relative;
        }

        .profile-pic-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-info-preview {
            text-align: center;
        }

        .profile-info-preview h5 {
            margin-bottom: 5px;
            color: var(--text-color);
        }

        .profile-info-preview p {
            color: var(--gray);
            margin-bottom: 10px;
        }

        /* File Upload Styles */
        .file-upload-container {
            position: relative;
            margin-bottom: 20px;
        }

        .file-upload-label {
            display: block;
            width: 100%;
            padding: 12px;
            background: rgba(67, 97, 238, 0.1);
            border: 2px dashed rgba(67, 97, 238, 0.5);
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-upload-label:hover {
            background: rgba(67, 97, 238, 0.2);
            border-color: var(--primary);
        }

        .file-upload-label i {
            font-size: 24px;
            color: var(--primary);
            margin-bottom: 8px;
        }

        .file-upload-label span {
            display: block;
            color: var(--text-color);
            font-weight: 500;
        }

        .file-upload-label small {
            display: block;
            color: var(--gray);
            font-size: 0.8rem;
            margin-top: 5px;
        }

        .file-upload-input {
            display: none;
        }

        .upload-preview {
            margin-top: 15px;
            text-align: center;
        }

        .upload-preview img {
            max-width: 100%;
            max-height: 200px;
            border-radius: 8px;
            margin-top: 10px;
            border: 2px solid var(--border-color);
        }

        .remove-image-btn {
            margin-top: 10px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .remove-image-btn:hover {
            background: #c82333;
            transform: scale(1.05);
        }

        /* Image Source Options */
        .image-source-options {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .image-source-btn {
            flex: 1;
            padding: 10px;
            text-align: center;
            background: rgba(67, 97, 238, 0.1);
            border: 1px solid rgba(67, 97, 238, 0.3);
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .image-source-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .image-source-btn:hover:not(.active) {
            background: rgba(67, 97, 238, 0.2);
        }

        .image-source-btn i {
            margin-right: 5px;
        }

        /* URL Input */
        .url-input-container {
            margin-top: 15px;
        }

        /* Dark Mode Styles */
        .dark-mode .status-active {
            background-color: #1a3d2f;
            color: #75b798;
        }

        .dark-mode .status-inactive {
            background-color: #5c1a22;
            color: #f1aeb5;
        }

        .dark-mode .status-pending {
            background-color: #664d03;
            color: #ffda6a;
        }

        .dark-mode .role-admin {
            background-color: #0d3c61;
            color: #64b5f6;
        }

        .dark-mode .role-user {
            background-color: #4a1e6e;
            color: #ce93d8;
        }

        .dark-mode .role-moderator {
            background-color: #1a472a;
            color: #81c784;
        }

        .dark-mode .file-upload-label {
            background: rgba(67, 97, 238, 0.05);
            border-color: rgba(67, 97, 238, 0.3);
        }

        .dark-mode .file-upload-label:hover {
            background: rgba(67, 97, 238, 0.1);
        }

        /* Leaflet Dark Mode */
        .dark-mode .leaflet-tile {
            filter: brightness(0.6) invert(1) contrast(3) hue-rotate(200deg) saturate(0.3) brightness(0.7);
        }
        
        .dark-mode .leaflet-container {
            background: #303030;
        }
        
        .dark-mode .leaflet-popup-content-wrapper {
            background: var(--card-bg);
            color: var(--text-color);
        }
        
        .dark-mode .leaflet-popup-tip {
            background: var(--card-bg);
        }

        /* Add User Button */
        .add-user-btn {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            position: fixed;
            bottom: 90px;
            right: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .add-user-btn:hover {
            background: var(--secondary);
            transform: scale(1.1) rotate(90deg);
        }

        /* Notification */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
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

        .notification.success {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .notification.error {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .notification.info {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        .notification.warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
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
            
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .sidebar-header, .sidebar-menu span {
                display: none;
            }
            
            .sidebar-menu i {
                margin-right: 0;
            }
            
            #map {
                height: 400px;
            }
            
            .map-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .map-controls {
                width: 100%;
                justify-content: space-between;
            }
            
            .image-source-options {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-tachometer-alt"></i> Dashboard</h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="index.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
            <li><a href="members.php"><i class="fas fa-users"></i> <span>Members</span></a></li>
            <li><a href="calendar.php"><i class="fas fa-calendar"></i> <span>Calendar</span></a></li>
            <li><a href="location.php" class="active"><i class="fas fa-map-marked-alt"></i><span>Location</span></a></li>
            <li><a href="request.php"><i class="fas fa-clipboard-list"></i> <span>Requests</span></a></li>
            <li><a href="archive.php" class=""><i class="fas fa-archive"></i> <span>Archive</span></a></li>
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
            <h1>User Locations Map</h1>
            <div class="user-info">
                <div class="user-avatar">AD</div>
                <div>
                    <div class="fw-bold">Admin User</div>
                    <small class="text-muted">Administrator</small>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-number" id="totalUsers">6</div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="activeUsers">4</div>
                <div class="stat-label">Active Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="inactiveUsers">1</div>
                <div class="stat-label">Inactive Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="pendingUsers">1</div>
                <div class="stat-label">Pending Users</div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column - Map (Now bigger - 9 columns) -->
            <div class="col-lg-9">
                <div class="map-container">
                    <div class="map-header">
                        <h2 class="map-title"><i class="fas fa-map-marker-alt"></i> Rosario, Cavite - User Locations</h2>
                        <div class="map-controls">
                            <button class="map-control-btn" id="locateMe">
                                <i class="fas fa-location-arrow"></i> Locate Me
                            </button>
                            <button class="map-control-btn" id="resetView">
                                <i class="fas fa-sync-alt"></i> Reset View
                            </button>
                            <button class="map-control-btn" id="toggleUsers">
                                <i class="fas fa-users"></i> Show Users
                            </button>
                        </div>
                    </div>
                    <div id="map"></div>
                </div>
            </div>

            <!-- Right Column - User Locations Only (Now smaller - 3 columns) -->
            <div class="col-lg-3">
                <div class="map-container">
                    <h3 class="section-title"><i class="fas fa-users"></i> Users</h3>
                    
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" id="searchUsers" class="form-control" placeholder="Search users...">
                            <button class="btn btn-outline-primary" type="button" id="searchButton">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div id="userLocationsList">
                        <!-- User location cards will be dynamically added here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Floating Button -->
    <button class="add-user-btn" id="addUserBtn" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="fas fa-plus"></i>
    </button>

    <!-- Add User Modal -->
    <div class="modal fade user-modal" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm" class="user-form">
                        <div class="profile-preview">
                            <div class="profile-pic-preview" id="newProfilePicPreview">
                                <div id="newProfilePicText" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 2rem; background-color: #4361ee;">
                                    JD
                                </div>
                            </div>
                            <div class="profile-info-preview">
                                <h5 id="newProfileNamePreview">John Doe</h5>
                                <p id="newProfileRolePreview">User</p>
                                <span class="user-status status-active" id="newProfileStatusPreview">Active</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="newUserName" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="newUserName" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="newUserEmail" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="newUserEmail" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="newUserRole" class="form-label">Role *</label>
                                <select class="form-select" id="newUserRole" required>
                                    <option value="">Select Role</option>
                                    <option value="Admin">Admin</option>
                                    <option value="User" selected>User</option>
                                    <option value="Moderator">Moderator</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="newUserStatus" class="form-label">Status *</label>
                                <select class="form-select" id="newUserStatus" required>
                                    <option value="">Select Status</option>
                                    <option value="Active" selected>Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Pending">Pending</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="newUserAddress" class="form-label">Address *</label>
                            <input type="text" class="form-control" id="newUserAddress" placeholder="Enter full address" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="newUserLat" class="form-label">Latitude *</label>
                                <input type="number" step="0.000001" class="form-control" id="newUserLat" value="14.4160" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="newUserLng" class="form-label">Longitude *</label>
                                <input type="number" step="0.000001" class="form-control" id="newUserLng" value="120.8541" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="newUserAvatar" class="form-label">Avatar Initials (2 letters)</label>
                            <input type="text" class="form-control" id="newUserAvatar" maxlength="2" placeholder="e.g., JD">
                        </div>
                        
                        <!-- Image Upload Section -->
                        <div class="mb-3">
                            <label class="form-label">Profile Picture</label>
                            <div class="image-source-options" id="newImageSourceOptions">
                                <button type="button" class="image-source-btn active" data-source="upload">
                                    <i class="fas fa-upload"></i> Upload
                                </button>
                                <button type="button" class="image-source-btn" data-source="url">
                                    <i class="fas fa-link"></i> URL
                                </button>
                            </div>
                            
                            <!-- Upload Section -->
                            <div class="file-upload-container" id="newUploadSection">
                                <label for="newUserProfilePicUpload" class="file-upload-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Click to upload photo</span>
                                    <small>Supported formats: JPG, PNG, GIF (Max 5MB)</small>
                                </label>
                                <input type="file" class="file-upload-input" id="newUserProfilePicUpload" accept="image/*">
                                <div class="upload-preview" id="newUploadPreview"></div>
                            </div>
                            
                            <!-- URL Section -->
                            <div class="url-input-container d-none" id="newUrlSection">
                                <input type="text" class="form-control" id="newUserProfilePicUrl" placeholder="https://example.com/image.jpg">
                                <small class="text-muted">Enter image URL</small>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveNewUser">Add User</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade user-modal" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm" class="user-form">
                        <input type="hidden" id="editUserId">
                        <div class="profile-preview">
                            <div class="profile-pic-preview" id="editProfilePicPreview">
                                <div id="editProfilePicText" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 2rem; background-color: #4361ee;">
                                    JD
                                </div>
                            </div>
                            <div class="profile-info-preview">
                                <h5 id="editProfileNamePreview">John Doe</h5>
                                <p id="editProfileRolePreview">User</p>
                                <span class="user-status status-active" id="editProfileStatusPreview">Active</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editUserName" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="editUserName" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editUserEmail" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="editUserEmail" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editUserRole" class="form-label">Role *</label>
                                <select class="form-select" id="editUserRole" required>
                                    <option value="">Select Role</option>
                                    <option value="Admin">Admin</option>
                                    <option value="User">User</option>
                                    <option value="Moderator">Moderator</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editUserStatus" class="form-label">Status *</label>
                                <select class="form-select" id="editUserStatus" required>
                                    <option value="">Select Status</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Pending">Pending</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editUserAddress" class="form-label">Address *</label>
                            <input type="text" class="form-control" id="editUserAddress" placeholder="Enter full address" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editUserLat" class="form-label">Latitude *</label>
                                <input type="number" step="0.000001" class="form-control" id="editUserLat" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editUserLng" class="form-label">Longitude *</label>
                                <input type="number" step="0.000001" class="form-control" id="editUserLng" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editUserAvatar" class="form-label">Avatar Initials (2 letters)</label>
                            <input type="text" class="form-control" id="editUserAvatar" maxlength="2" placeholder="e.g., JD">
                        </div>
                        
                        <!-- Image Upload Section -->
                        <div class="mb-3">
                            <label class="form-label">Profile Picture</label>
                            <div class="image-source-options" id="editImageSourceOptions">
                                <button type="button" class="image-source-btn active" data-source="upload">
                                    <i class="fas fa-upload"></i> Upload
                                </button>
                                <button type="button" class="image-source-btn" data-source="url">
                                    <i class="fas fa-link"></i> URL
                                </button>
                            </div>
                            
                            <!-- Upload Section -->
                            <div class="file-upload-container" id="editUploadSection">
                                <label for="editUserProfilePicUpload" class="file-upload-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Click to upload photo</span>
                                    <small>Supported formats: JPG, PNG, GIF (Max 5MB)</small>
                                </label>
                                <input type="file" class="file-upload-input" id="editUserProfilePicUpload" accept="image/*">
                                <div class="upload-preview" id="editUploadPreview"></div>
                            </div>
                            
                            <!-- URL Section -->
                            <div class="url-input-container d-none" id="editUrlSection">
                                <input type="text" class="form-control" id="editUserProfilePicUrl" placeholder="https://example.com/image.jpg">
                                <small class="text-muted">Enter image URL</small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editUserLastSeen" class="form-label">Last Seen</label>
                            <input type="text" class="form-control" id="editUserLastSeen" readonly>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveEditUser">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade user-modal" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="deleteConfirmModalLabel"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong id="deleteUserName"></strong>?</p>
                    <p class="text-danger"><small>This action cannot be undone. The user will be removed from the map and user list.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete User</button>
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
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- JavaScript for Map and Interactive Elements -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the map
            const map = L.map('map').setView([14.4160, 120.8541], 14);
            
            // Add tile layer (OpenStreetMap)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            
            // Define users with their locations and PROFILE PICTURES
            let users = JSON.parse(localStorage.getItem('mapUsers')) || [
                {
                    id: 1,
                    name: "Carl John D. Anthony",
                    email: "tc.carljohn.anthony@cvsu.edu.ph",
                    role: "Admin",
                    status: "Active",
                    coords: [14.4170, 120.8551],
                    avatar: "CJ",
                    profilePic: "mee.jfif",
                    lastSeen: "2024-01-20 14:30",
                    address: "Poblacion, Rosario, Cavite"
                },
                {
                    id: 2,
                    name: "Sabrina Tan",
                    email: "sabrina.tan@realtyvale.com",
                    role: "User",
                    status: "Active",
                    coords: [14.4190, 120.8532],
                    avatar: "ST",
                    profilePic: "https://randomuser.me/api/portraits/women/44.jpg",
                    lastSeen: "2024-01-20 10:15",
                    address: "Tejeros, Rosario, Cavite"
                },
                {
                    id: 3,
                    name: "Andy Sewer",
                    email: "andy.sewer@fawcettor.com",
                    role: "Moderator",
                    status: "Inactive",
                    coords: [14.4135, 120.8560],
                    avatar: "AS",
                    profilePic: "https://randomuser.me/api/portraits/men/32.jpg",
                    lastSeen: "2024-01-19 16:45",
                    address: "Wawa II, Rosario, Cavite"
                },
                {
                    id: 4,
                    name: "Shanon Matilda",
                    email: "shanon.matilda@goldenfruit.com",
                    role: "User",
                    status: "Pending",
                    coords: [14.4155, 120.8580],
                    avatar: "SM",
                    profilePic: "https://randomuser.me/api/portraits/women/68.jpg",
                    lastSeen: "2024-01-20 09:20",
                    address: "Sapa I, Rosario, Cavite"
                },
                {
                    id: 5,
                    name: "Ethan Cravejal",
                    email: "ethan.cravejal@newcastle.com",
                    role: "User",
                    status: "Active",
                    coords: [14.4210, 120.8520],
                    avatar: "EC",
                    profilePic: "https://randomuser.me/api/portraits/men/67.jpg",
                    lastSeen: "2024-01-20 11:45",
                    address: "Wawa I, Rosario, Cavite"
                },
                {
                    id: 6,
                    name: "John Doe",
                    email: "john.doe@example.com",
                    role: "User",
                    status: "Active",
                    coords: [14.4165, 120.8515],
                    avatar: "JD",
                    profilePic: "https://randomuser.me/api/portraits/men/75.jpg",
                    lastSeen: "2024-01-20 13:30",
                    address: "Kanluran, Rosario, Cavite"
                }
            ];

            // Save users to localStorage
            function saveUsers() {
                localStorage.setItem('mapUsers', JSON.stringify(users));
                updateStatistics();
            }

            // Profile Picture Marker Icons
            const profilePicMarkerIcons = {
                Active: function(profilePicUrl) {
                    return L.divIcon({
                        html: `<div class="profile-pic-marker active" style="background-image: url('${profilePicUrl}')"></div>`,
                        className: 'custom-div-icon',
                        iconSize: [40, 40],
                        iconAnchor: [20, 20]
                    });
                },
                Inactive: function(profilePicUrl) {
                    return L.divIcon({
                        html: `<div class="profile-pic-marker inactive" style="background-image: url('${profilePicUrl}')"></div>`,
                        className: 'custom-div-icon',
                        iconSize: [40, 40],
                        iconAnchor: [20, 20]
                    });
                },
                Pending: function(profilePicUrl) {
                    return L.divIcon({
                        html: `<div class="profile-pic-marker pending" style="background-image: url('${profilePicUrl}')"></div>`,
                        className: 'custom-div-icon',
                        iconSize: [40, 40],
                        iconAnchor: [20, 20]
                    });
                }
            };

            // Variable to track if user markers are shown
            let userMarkersVisible = true;
            let userMarkers = [];
            let userToDelete = null;
            let userToEdit = null;
            let uploadedImages = {}; // Store uploaded images
            let previewMarker = null; // For preview marker

            // Function to get initials from name
            function getInitials(name) {
                return name.split(' ').map(word => word[0]).join('').toUpperCase().substring(0, 2);
            }

            // Function to perform geocoding (convert address to coordinates)
            async function geocodeAddress(address) {
                try {
                    // Using OpenStreetMap Nominatim API (free, no API key needed)
                    const response = await fetch(
                        `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}, Rosario, Cavite, Philippines&limit=1`
                    );
                    const data = await response.json();
                    
                    if (data && data.length > 0) {
                        return {
                            lat: parseFloat(data[0].lat),
                            lng: parseFloat(data[0].lon)
                        };
                    } else {
                        throw new Error('Address not found');
                    }
                } catch (error) {
                    console.error('Geocoding error:', error);
                    return null;
                }
            }

            // Function to get default profile picture
            function getDefaultProfilePic() {
                const genders = ['men', 'women'];
                const gender = genders[Math.floor(Math.random() * genders.length)];
                const number = Math.floor(Math.random() * 100);
                return `https://randomuser.me/api/portraits/${gender}/${number}.jpg`;
            }

            // Create user markers with PROFILE PICTURES
            function createUserMarkers() {
                // Clear existing markers
                userMarkers.forEach(userMarker => {
                    if (userMarker.marker) {
                        map.removeLayer(userMarker.marker);
                    }
                });
                userMarkers = [];
                
                users.forEach(user => {
                    const marker = L.marker(user.coords, {
                        icon: profilePicMarkerIcons[user.status](user.profilePic)
                    })
                    .bindPopup(`
                        <div class="user-popup">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="user-profile-pic" style="background-image: url('${user.profilePic}')"></div>
                                <div>
                                    <h5 class="mb-0">${user.name}</h5>
                                    <small>${user.role}</small>
                                </div>
                            </div>
                            <hr class="my-2">
                            <p class="mb-1"><strong>Email:</strong> ${user.email}</p>
                            <p class="mb-1"><strong>Status:</strong> <span class="user-status status-${user.status.toLowerCase()}">${user.status}</span></p>
                            <p class="mb-1"><strong>Last Seen:</strong> ${user.lastSeen}</p>
                            <p class="mb-1"><strong>Address:</strong> ${user.address}</p>
                            <div class="d-flex gap-2 mt-2">
                                <button class="btn btn-sm btn-primary w-100" onclick="focusUserOnMap(${user.id})">
                                    <i class="fas fa-map-marker-alt"></i> View
                                </button>
                                <button class="btn btn-sm btn-warning w-100" onclick="editUserProfile(${user.id})">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-danger w-100" onclick="showDeleteConfirmation(${user.id})">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    `);
                    
                    if (userMarkersVisible) {
                        marker.addTo(map);
                    }
                    
                    userMarkers.push({
                        id: user.id,
                        marker: marker,
                        user: user
                    });
                });
            }

            // Function to create user location cards with PROFILE PICTURES
            function createUserLocationCards() {
                const userListContainer = document.getElementById('userLocationsList');
                userListContainer.innerHTML = '';
                
                users.forEach(user => {
                    const card = document.createElement('div');
                    card.className = 'user-card';
                    card.setAttribute('data-user-id', user.id);
                    
                    // Generate avatar initials
                    const avatar = user.avatar || getInitials(user.name);
                    
                    card.innerHTML = `
                        <div class="user-card-actions">
                            <button class="user-action-btn edit-user-btn" onclick="editUserProfile(${user.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="user-action-btn delete-user-btn" onclick="showDeleteConfirmation(${user.id})">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="user-card-title">${user.name}</div>
                                <div class="user-card-address">${user.address}</div>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <span class="role-badge role-${user.role.toLowerCase()}">${user.role}</span>
                                    <span class="user-status status-${user.status.toLowerCase()}">${user.status}</span>
                                </div>
                            </div>
                            <div class="user-profile-pic" style="background-image: url('${user.profilePic}')"></div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-clock"></i> Last seen: ${user.lastSeen}
                            </small>
                            <button class="btn btn-sm btn-outline-primary mt-2 w-100" onclick="focusUserOnMap(${user.id})">
                                <i class="fas fa-map-marker-alt"></i> View on Map
                            </button>
                        </div>
                    `;
                    
                    card.addEventListener('click', function(e) {
                        if (!e.target.closest('button')) {
                            focusUserOnMap(user.id);
                        }
                    });
                    
                    userListContainer.appendChild(card);
                });
            }

            // Function to focus on a specific user on the map
            window.focusUserOnMap = function(userId) {
                const user = users.find(u => u.id === userId);
                if (user) {
                    map.flyTo(user.coords, 16);
                    
                    // Find and open the user's marker popup
                    const userMarker = userMarkers.find(m => m.id === userId);
                    if (userMarker) {
                        userMarker.marker.openPopup();
                    }
                    
                    // Highlight the user card
                    document.querySelectorAll('.user-card').forEach(card => {
                        card.classList.remove('active');
                    });
                    document.querySelector(`[data-user-id="${userId}"]`).classList.add('active');
                }
            };

            // Function to edit user profile
            window.editUserProfile = function(userId) {
                event.stopPropagation();
                const user = users.find(u => u.id === userId);
                if (user) {
                    userToEdit = user;
                    
                    // Fill the edit form with user data
                    document.getElementById('editUserId').value = user.id;
                    document.getElementById('editUserName').value = user.name;
                    document.getElementById('editUserEmail').value = user.email;
                    document.getElementById('editUserRole').value = user.role;
                    document.getElementById('editUserStatus').value = user.status;
                    document.getElementById('editUserAddress').value = user.address;
                    document.getElementById('editUserLat').value = user.coords[0];
                    document.getElementById('editUserLng').value = user.coords[1];
                    document.getElementById('editUserAvatar').value = user.avatar || getInitials(user.name);
                    document.getElementById('editUserLastSeen').value = user.lastSeen;
                    
                    // Check if user has an uploaded image
                    if (uploadedImages[user.id]) {
                        document.getElementById('editUserProfilePicUrl').value = '';
                        showImagePreview(user.id, 'edit');
                    } else {
                        document.getElementById('editUserProfilePicUrl').value = user.profilePic || '';
                    }
                    
                    // Update preview
                    updateEditProfilePreview();
                    
                    // Show the edit modal
                    const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
                    editModal.show();
                    
                    // FLY TO THE USER'S LOCATION ON MAP WHEN EDITING
                    map.flyTo(user.coords, 16);
                    
                    // Find and open the user's marker popup
                    const userMarker = userMarkers.find(m => m.id === userId);
                    if (userMarker) {
                        setTimeout(() => {
                            userMarker.marker.openPopup();
                        }, 1000);
                    }
                    
                    // Add event listener to address field for real-time location update
                    const addressInput = document.getElementById('editUserAddress');
                    let addressTimeout;
                    
                    addressInput.addEventListener('input', function() {
                        clearTimeout(addressTimeout);
                        addressTimeout = setTimeout(async () => {
                            const address = this.value;
                            if (address && address.length > 3) {
                                const coords = await geocodeAddress(address);
                                if (coords) {
                                    // Update the coordinates in the form
                                    document.getElementById('editUserLat').value = coords.lat.toFixed(6);
                                    document.getElementById('editUserLng').value = coords.lng.toFixed(6);
                                    
                                    // Fly to the new location on the map
                                    map.flyTo([coords.lat, coords.lng], 16);
                                    
                                    // Show a preview marker
                                    if (previewMarker) {
                                        map.removeLayer(previewMarker);
                                    }
                                    previewMarker = L.marker([coords.lat, coords.lng], {
                                        icon: L.divIcon({
                                            html: '<div style="width: 40px; height: 40px; background: rgba(255, 0, 0, 0.5); border: 3px solid red; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 12px;">?</div>',
                                            className: 'custom-div-icon',
                                            iconSize: [40, 40],
                                            iconAnchor: [20, 20]
                                        })
                                    })
                                    .addTo(map)
                                    .bindPopup('Preview of new location<br>Click "Save Changes" to confirm')
                                    .openPopup();
                                }
                            }
                        }, 800); // Wait 800ms after typing stops
                    });
                }
            };

            // Function to show image preview
            function showImagePreview(userId, type = 'new') {
                const previewId = type === 'new' ? 'newUploadPreview' : 'editUploadPreview';
                const previewContainer = document.getElementById(previewId);
                const profilePicPreview = document.getElementById(`${type}ProfilePicPreview`);
                const profilePicText = document.getElementById(`${type}ProfilePicText`);
                
                if (uploadedImages[userId]) {
                    const img = document.createElement('img');
                    img.src = uploadedImages[userId];
                    img.alt = 'Profile Preview';
                    img.style.maxWidth = '100%';
                    img.style.maxHeight = '200px';
                    img.style.borderRadius = '8px';
                    
                    previewContainer.innerHTML = '';
                    previewContainer.appendChild(img);
                    
                    // Add remove button
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'remove-image-btn';
                    removeBtn.innerHTML = '<i class="fas fa-trash"></i> Remove Image';
                    removeBtn.onclick = function() {
                        delete uploadedImages[userId];
                        previewContainer.innerHTML = '';
                        updateProfilePreview(type);
                    };
                    previewContainer.appendChild(removeBtn);
                    
                    // Update profile preview
                    profilePicPreview.style.backgroundImage = `url('${uploadedImages[userId]}')`;
                    profilePicText.style.display = 'none';
                }
            }

            // Function to update edit profile preview
            function updateEditProfilePreview() {
                const name = document.getElementById('editUserName').value;
                const role = document.getElementById('editUserRole').value;
                const status = document.getElementById('editUserStatus').value;
                const avatar = document.getElementById('editUserAvatar').value || getInitials(name);
                const url = document.getElementById('editUserProfilePicUrl').value;
                const userId = document.getElementById('editUserId').value;
                
                // Update preview elements
                document.getElementById('editProfileNamePreview').textContent = name;
                document.getElementById('editProfileRolePreview').textContent = role;
                
                // Update status preview
                const statusPreview = document.getElementById('editProfileStatusPreview');
                statusPreview.textContent = status;
                statusPreview.className = `user-status status-${status.toLowerCase()}`;
                
                // Update profile picture preview
                const profilePicPreview = document.getElementById('editProfilePicPreview');
                const profilePicText = document.getElementById('editProfilePicText');
                
                if (uploadedImages[userId]) {
                    profilePicPreview.style.backgroundImage = `url('${uploadedImages[userId]}')`;
                    profilePicText.style.display = 'none';
                } else if (url) {
                    profilePicPreview.style.backgroundImage = `url('${url}')`;
                    profilePicText.style.display = 'none';
                } else {
                    profilePicPreview.style.backgroundImage = '';
                    profilePicPreview.style.backgroundColor = '#4361ee';
                    profilePicText.textContent = avatar;
                    profilePicText.style.display = 'flex';
                }
            }

            // Function to update new user profile preview
            function updateNewProfilePreview() {
                const name = document.getElementById('newUserName').value || 'John Doe';
                const role = document.getElementById('newUserRole').value || 'User';
                const status = document.getElementById('newUserStatus').value || 'Active';
                const avatar = document.getElementById('newUserAvatar').value || getInitials(name) || 'JD';
                const url = document.getElementById('newUserProfilePicUrl').value;
                
                // Update preview elements
                document.getElementById('newProfileNamePreview').textContent = name;
                document.getElementById('newProfileRolePreview').textContent = role;
                
                // Update status preview
                const statusPreview = document.getElementById('newProfileStatusPreview');
                statusPreview.textContent = status;
                statusPreview.className = `user-status status-${status.toLowerCase()}`;
                
                // Update profile picture preview
                const profilePicPreview = document.getElementById('newProfilePicPreview');
                const profilePicText = document.getElementById('newProfilePicText');
                
                if (url) {
                    profilePicPreview.style.backgroundImage = `url('${url}')`;
                    profilePicText.style.display = 'none';
                } else {
                    profilePicPreview.style.backgroundImage = '';
                    profilePicPreview.style.backgroundColor = '#4361ee';
                    profilePicText.textContent = avatar;
                    profilePicText.style.display = 'flex';
                }
            }

            // Function to update profile preview
            function updateProfilePreview(type = 'new') {
                if (type === 'new') {
                    updateNewProfilePreview();
                } else {
                    updateEditProfilePreview();
                }
            }

            // Show delete confirmation
            window.showDeleteConfirmation = function(userId) {
                event.stopPropagation();
                const user = users.find(u => u.id === userId);
                if (user) {
                    userToDelete = user;
                    document.getElementById('deleteUserName').textContent = user.name;
                    
                    const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
                    deleteModal.show();
                }
            };

            // Function to toggle user markers visibility
            function toggleUserMarkers() {
                userMarkersVisible = !userMarkersVisible;
                userMarkers.forEach(userMarker => {
                    if (userMarkersVisible) {
                        userMarker.marker.addTo(map);
                    } else {
                        map.removeLayer(userMarker.marker);
                    }
                });
                
                const toggleBtn = document.getElementById('toggleUsers');
                if (userMarkersVisible) {
                    toggleBtn.innerHTML = '<i class="fas fa-users"></i> Hide Users';
                } else {
                    toggleBtn.innerHTML = '<i class="fas fa-users"></i> Show Users';
                }
            }

            // Update statistics
            function updateStatistics() {
                const totalUsers = users.length;
                const activeUsers = users.filter(u => u.status === 'Active').length;
                const inactiveUsers = users.filter(u => u.status === 'Inactive').length;
                const pendingUsers = users.filter(u => u.status === 'Pending').length;
                
                document.getElementById('totalUsers').textContent = totalUsers;
                document.getElementById('activeUsers').textContent = activeUsers;
                document.getElementById('inactiveUsers').textContent = inactiveUsers;
                document.getElementById('pendingUsers').textContent = pendingUsers;
            }

            // Initialize markers and cards
            createUserMarkers();
            createUserLocationCards();
            updateStatistics();
            
            // Initialize image source options
            function initImageSourceOptions() {
                // New user modal options
                const newSourceOptions = document.querySelectorAll('#newImageSourceOptions .image-source-btn');
                newSourceOptions.forEach(btn => {
                    btn.addEventListener('click', function() {
                        const source = this.getAttribute('data-source');
                        
                        // Update active state
                        newSourceOptions.forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                        
                        // Show/hide sections
                        if (source === 'upload') {
                            document.getElementById('newUploadSection').classList.remove('d-none');
                            document.getElementById('newUrlSection').classList.add('d-none');
                        } else {
                            document.getElementById('newUploadSection').classList.add('d-none');
                            document.getElementById('newUrlSection').classList.remove('d-none');
                        }
                    });
                });
                
                // Edit user modal options
                const editSourceOptions = document.querySelectorAll('#editImageSourceOptions .image-source-btn');
                editSourceOptions.forEach(btn => {
                    btn.addEventListener('click', function() {
                        const source = this.getAttribute('data-source');
                        
                        // Update active state
                        editSourceOptions.forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                        
                        // Show/hide sections
                        if (source === 'upload') {
                            document.getElementById('editUploadSection').classList.remove('d-none');
                            document.getElementById('editUrlSection').classList.add('d-none');
                        } else {
                            document.getElementById('editUploadSection').classList.add('d-none');
                            document.getElementById('editUrlSection').classList.remove('d-none');
                        }
                    });
                });
            }
            
            // Initialize file upload handlers
            function initFileUploadHandlers() {
                // New user file upload
                const newFileUpload = document.getElementById('newUserProfilePicUpload');
                const newUploadPreview = document.getElementById('newUploadPreview');
                
                newFileUpload.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        // Check file size (5MB limit)
                        if (file.size > 5 * 1024 * 1024) {
                            showNotification('File size must be less than 5MB', 'error');
                            this.value = '';
                            return;
                        }
                        
                        // Check file type
                        if (!file.type.match('image.*')) {
                            showNotification('Please select an image file', 'error');
                            this.value = '';
                            return;
                        }
                        
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            const imageData = event.target.result;
                            const tempId = 'temp-' + Date.now();
                            uploadedImages[tempId] = imageData;
                            
                            // Show preview
                            const img = document.createElement('img');
                            img.src = imageData;
                            img.alt = 'Profile Preview';
                            img.style.maxWidth = '100%';
                            img.style.maxHeight = '200px';
                            img.style.borderRadius = '8px';
                            
                            newUploadPreview.innerHTML = '';
                            newUploadPreview.appendChild(img);
                            
                            // Add remove button
                            const removeBtn = document.createElement('button');
                            removeBtn.type = 'button';
                            removeBtn.className = 'remove-image-btn';
                            removeBtn.innerHTML = '<i class="fas fa-trash"></i> Remove Image';
                            removeBtn.onclick = function() {
                                delete uploadedImages[tempId];
                                newUploadPreview.innerHTML = '';
                                newFileUpload.value = '';
                                updateNewProfilePreview();
                            };
                            newUploadPreview.appendChild(removeBtn);
                            
                            // Update profile preview
                            const profilePicPreview = document.getElementById('newProfilePicPreview');
                            const profilePicText = document.getElementById('newProfilePicText');
                            profilePicPreview.style.backgroundImage = `url('${imageData}')`;
                            profilePicText.style.display = 'none';
                        };
                        reader.readAsDataURL(file);
                    }
                });
                
                // Edit user file upload
                const editFileUpload = document.getElementById('editUserProfilePicUpload');
                const editUploadPreview = document.getElementById('editUploadPreview');
                
                editFileUpload.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        // Check file size (5MB limit)
                        if (file.size > 5 * 1024 * 1024) {
                            showNotification('File size must be less than 5MB', 'error');
                            this.value = '';
                            return;
                        }
                        
                        // Check file type
                        if (!file.type.match('image.*')) {
                            showNotification('Please select an image file', 'error');
                            this.value = '';
                            return;
                        }
                        
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            const imageData = event.target.result;
                            const userId = document.getElementById('editUserId').value;
                            uploadedImages[userId] = imageData;
                            
                            // Show preview
                            showImagePreview(userId, 'edit');
                            updateEditProfilePreview();
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
            
            // Initialize URL input handlers
            function initUrlInputHandlers() {
                // New user URL input
                const newUrlInput = document.getElementById('newUserProfilePicUrl');
                newUrlInput.addEventListener('input', function() {
                    updateNewProfilePreview();
                });
                
                // Edit user URL input
                const editUrlInput = document.getElementById('editUserProfilePicUrl');
                editUrlInput.addEventListener('input', function() {
                    updateEditProfilePreview();
                });
            }
            
            // Search users functionality
            document.getElementById('searchUsers').addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                
                users.forEach(user => {
                    const userMarker = userMarkers.find(m => m.id === user.id);
                    const userCard = document.querySelector(`[data-user-id="${user.id}"]`);
                    
                    const matchesSearch = 
                        user.name.toLowerCase().includes(searchTerm) ||
                        user.email.toLowerCase().includes(searchTerm) ||
                        user.address.toLowerCase().includes(searchTerm) ||
                        user.role.toLowerCase().includes(searchTerm);
                    
                    // Show/hide marker and card based on search
                    if (userMarker) {
                        if (searchTerm === '' || matchesSearch) {
                            if (userMarkersVisible) {
                                userMarker.marker.addTo(map);
                            }
                            if (userCard) userCard.style.display = '';
                        } else {
                            map.removeLayer(userMarker.marker);
                            if (userCard) userCard.style.display = 'none';
                        }
                    }
                });
            });
            
            // Locate Me button
            document.getElementById('locateMe').addEventListener('click', function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const userLocation = [position.coords.latitude, position.coords.longitude];
                            const currentUserMarker = L.marker(userLocation, {
                                icon: L.divIcon({
                                    html: '<div class="current-user-marker"><i class="fas fa-user-circle"></i></div>',
                                    className: 'custom-div-icon',
                                    iconSize: [45, 45],
                                    iconAnchor: [22, 22]
                                })
                            })
                            .addTo(map)
                            .bindPopup('You are here!')
                            .openPopup();
                            map.flyTo(userLocation, 15);
                        },
                        function(error) {
                            alert('Unable to retrieve your location. Please ensure location services are enabled.');
                        }
                    );
                } else {
                    alert('Geolocation is not supported by this browser.');
                }
            });
            
            // Reset View button
            document.getElementById('resetView').addEventListener('click', function() {
                map.flyTo([14.4160, 120.8541], 14);
                
                // Remove active class from all cards
                document.querySelectorAll('.user-card').forEach(c => {
                    c.classList.remove('active');
                });
                
                // Remove preview marker
                if (previewMarker) {
                    map.removeLayer(previewMarker);
                    previewMarker = null;
                }
            });
            
            // Toggle Users button
            document.getElementById('toggleUsers').addEventListener('click', toggleUserMarkers);
            
            // Search button click
            document.getElementById('searchButton').addEventListener('click', function() {
                document.getElementById('searchUsers').focus();
            });
            
            // Save new user
            document.getElementById('saveNewUser').addEventListener('click', function() {
                const name = document.getElementById('newUserName').value;
                const email = document.getElementById('newUserEmail').value;
                const role = document.getElementById('newUserRole').value;
                const status = document.getElementById('newUserStatus').value;
                const address = document.getElementById('newUserAddress').value;
                const lat = parseFloat(document.getElementById('newUserLat').value);
                const lng = parseFloat(document.getElementById('newUserLng').value);
                const avatar = document.getElementById('newUserAvatar').value;
                const url = document.getElementById('newUserProfilePicUrl').value;
                
                // Get uploaded image if exists
                const tempKeys = Object.keys(uploadedImages).filter(key => key.startsWith('temp-'));
                const uploadedImage = tempKeys.length > 0 ? uploadedImages[tempKeys[0]] : null;
                
                if (name && email && role && status && address && !isNaN(lat) && !isNaN(lng)) {
                    const userId = Date.now();
                    const newUser = {
                        id: userId,
                        name,
                        email,
                        role,
                        status,
                        coords: [lat, lng],
                        avatar: avatar || getInitials(name),
                        profilePic: uploadedImage || url || getDefaultProfilePic(),
                        lastSeen: new Date().toLocaleDateString('en-US') + ' ' + new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }),
                        address
                    };
                    
                    // Move uploaded image to permanent storage
                    if (uploadedImage) {
                        uploadedImages[userId] = uploadedImage;
                        // Remove temp key
                        delete uploadedImages[tempKeys[0]];
                    }
                    
                    users.push(newUser);
                    saveUsers();
                    createUserMarkers();
                    createUserLocationCards();
                    
                    // Close modal and reset form
                    bootstrap.Modal.getInstance(document.getElementById('addUserModal')).hide();
                    document.getElementById('addUserForm').reset();
                    
                    // Reset preview
                    updateNewProfilePreview();
                    
                    // Clear upload preview
                    document.getElementById('newUploadPreview').innerHTML = '';
                    
                    // Show success notification
                    showNotification(`User "${name}" added successfully!`, 'success');
                } else {
                    showNotification('Please fill all required fields correctly.', 'error');
                }
            });
            
            // Save edited user
            document.getElementById('saveEditUser').addEventListener('click', function() {
                const userId = parseInt(document.getElementById('editUserId').value);
                const name = document.getElementById('editUserName').value;
                const email = document.getElementById('editUserEmail').value;
                const role = document.getElementById('editUserRole').value;
                const status = document.getElementById('editUserStatus').value;
                const address = document.getElementById('editUserAddress').value;
                const lat = parseFloat(document.getElementById('editUserLat').value);
                const lng = parseFloat(document.getElementById('editUserLng').value);
                const avatar = document.getElementById('editUserAvatar').value;
                const url = document.getElementById('editUserProfilePicUrl').value;
                
                if (name && email && role && status && address && !isNaN(lat) && !isNaN(lng)) {
                    // Update user in array
                    const userIndex = users.findIndex(u => u.id === userId);
                    if (userIndex !== -1) {
                        // Get uploaded image or URL
                        let profilePic;
                        if (uploadedImages[userId]) {
                            profilePic = uploadedImages[userId];
                        } else if (url) {
                            profilePic = url;
                        } else {
                            profilePic = users[userIndex].profilePic || getDefaultProfilePic();
                        }
                        
                        users[userIndex] = {
                            ...users[userIndex],
                            name,
                            email,
                            role,
                            status,
                            address,
                            coords: [lat, lng],
                            avatar: avatar || getInitials(name),
                            profilePic: profilePic
                        };
                        
                        saveUsers();
                        createUserMarkers();
                        createUserLocationCards();
                        
                        // Close modal
                        bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
                        
                        // Clear upload preview
                        document.getElementById('editUploadPreview').innerHTML = '';
                        
                        // Remove preview marker
                        if (previewMarker) {
                            map.removeLayer(previewMarker);
                            previewMarker = null;
                        }
                        
                        // Show success notification
                        showNotification(`User "${name}" updated successfully!`, 'success');
                    }
                } else {
                    showNotification('Please fill all required fields correctly.', 'error');
                }
            });
            
            // Confirm delete button
            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                if (userToDelete) {
                    // Remove user from array
                    users = users.filter(user => user.id !== userToDelete.id);
                    
                    // Remove uploaded image if exists
                    if (uploadedImages[userToDelete.id]) {
                        delete uploadedImages[userToDelete.id];
                    }
                    
                    saveUsers();
                    createUserMarkers();
                    createUserLocationCards();
                    
                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal')).hide();
                    
                    // Show success notification
                    showNotification(`User "${userToDelete.name}" deleted successfully!`, 'success');
                    
                    // Reset userToDelete
                    userToDelete = null;
                }
            });
            
            // Show notification
            function showNotification(message, type) {
                const notification = document.createElement('div');
                notification.className = `notification ${type}`;
                notification.innerHTML = `
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
                    <span>${message}</span>
                `;
                
                document.body.appendChild(notification);
                
                // Remove after 3 seconds
                setTimeout(() => {
                    notification.style.animation = 'slideOut 0.3s ease';
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.parentNode.removeChild(notification);
                        }
                    }, 300);
                }, 3000);
            }
            
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

            // Initialize everything
            initImageSourceOptions();
            initFileUploadHandlers();
            initUrlInputHandlers();
            
            // Add real-time preview for new user form
            const newUserFormFields = ['newUserName', 'newUserRole', 'newUserStatus', 'newUserAvatar'];
            newUserFormFields.forEach(field => {
                document.getElementById(field).addEventListener('input', updateNewProfilePreview);
            });

            // Add real-time preview for edit user form
            const editUserFormFields = ['editUserName', 'editUserRole', 'editUserStatus', 'editUserAvatar'];
            editUserFormFields.forEach(field => {
                document.getElementById(field).addEventListener('input', updateEditProfilePreview);
            });

            // When clicking on map, update lat/lng in add user form
            map.on('click', function(e) {
                const latInput = document.getElementById('newUserLat');
                const lngInput = document.getElementById('newUserLng');
                const editLatInput = document.getElementById('editUserLat');
                const editLngInput = document.getElementById('editUserLng');
                
                // Update add form if visible
                if (latInput && lngInput) {
                    latInput.value = e.latlng.lat.toFixed(6);
                    lngInput.value = e.latlng.lng.toFixed(6);
                }
                
                // Update edit form if visible
                if (editLatInput && editLngInput && document.getElementById('editUserModal').classList.contains('show')) {
                    editLatInput.value = e.latlng.lat.toFixed(6);
                    editLngInput.value = e.latlng.lng.toFixed(6);
                    
                    // Also update preview if coordinates changed
                    updateEditProfilePreview();
                }
            });

            // Reset modals when closed
            document.getElementById('addUserModal').addEventListener('hidden.bs.modal', function() {
                document.getElementById('addUserForm').reset();
                document.getElementById('newUploadPreview').innerHTML = '';
                updateNewProfilePreview();
            });
            
            document.getElementById('editUserModal').addEventListener('hidden.bs.modal', function() {
                document.getElementById('editUploadPreview').innerHTML = '';
                // Remove preview marker
                if (previewMarker) {
                    map.removeLayer(previewMarker);
                    previewMarker = null;
                }
            });

            // Initialize new user form preview
            updateNewProfilePreview();
        });
    </script>
</body>
</html>