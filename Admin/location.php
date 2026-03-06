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

        .status-active   { background-color: #d1f7e4; color: #0f5132; }
        .status-inactive { background-color: #f8d7da; color: #721c24; }
        .status-pending  { background-color: #fff3cd; color: #856404; }

        .role-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-right: 5px;
        }

        .role-admin     { background-color: #e3f2fd; color: #1565c0; }
        .role-user      { background-color: #f3e5f5; color: #7b1fa2; }
        .role-moderator { background-color: #e8f5e9; color: #2e7d32; }

        .user-profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            flex-shrink: 0;
            border: 2px solid var(--primary);
        }

        .profile-pic-marker {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            border: 3px solid white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }

        .profile-pic-marker.active   { border-color: #4CAF50; box-shadow: 0 0 0 3px rgba(76,175,80,0.3); }
        .profile-pic-marker.inactive { border-color: #f44336; box-shadow: 0 0 0 3px rgba(244,67,54,0.3); }
        .profile-pic-marker.pending  { border-color: #ff9800; box-shadow: 0 0 0 3px rgba(255,152,0,0.3); }

        .current-user-marker {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            border: 3px solid #2196F3;
            box-shadow: 0 0 0 3px rgba(33,150,243,0.3);
        }

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

        .stat-card:hover::before { left: 100%; }

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

        .stat-card:hover .stat-number { color: var(--secondary); }
        .stat-label { color: var(--gray); font-size: 0.9rem; }

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

        .user-card:hover .user-card-actions { opacity: 1; }

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
            background: rgba(13,110,253,0.1);
            border: 1px solid rgba(13,110,253,0.3);
            color: #0d6efd;
        }

        .edit-user-btn:hover {
            background: #0d6efd;
            color: white;
            transform: scale(1.1);
        }

        .delete-user-btn {
            background: rgba(220,53,69,0.1);
            border: 1px solid rgba(220,53,69,0.3);
            color: #dc3545;
        }

        .delete-user-btn:hover {
            background: #dc3545;
            color: white;
            transform: scale(1.1);
        }

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

        .dark-mode-toggle:hover { transform: scale(1.1) rotate(15deg); }

        .user-modal .modal-content {
            background-color: var(--card-bg);
            color: var(--text-color);
            border: 1px solid var(--border-color);
        }

        .user-modal .modal-header { border-bottom: 1px solid var(--border-color); }
        .user-modal .modal-footer { border-top: 1px solid var(--border-color); }

        .user-form label { color: var(--text-color); }

        .user-form .form-control,
        .user-form .form-select {
            background-color: var(--card-bg);
            color: var(--text-color);
            border: 1px solid var(--border-color);
        }

        .user-form .form-control:focus,
        .user-form .form-select:focus {
            background-color: var(--card-bg);
            color: var(--text-color);
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }

        .profile-preview {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
            padding: 20px;
            background: rgba(67, 97, 238, 0.05);
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

        .profile-info-preview { text-align: center; }
        .profile-info-preview h5 { margin-bottom: 5px; color: var(--text-color); }
        .profile-info-preview p  { color: var(--gray); margin-bottom: 10px; }

        .file-upload-container { position: relative; margin-bottom: 20px; }

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

        .file-upload-label i    { font-size: 24px; color: var(--primary); margin-bottom: 8px; }
        .file-upload-label span { display: block; color: var(--text-color); font-weight: 500; }
        .file-upload-label small { display: block; color: var(--gray); font-size: 0.8rem; margin-top: 5px; }

        .file-upload-input { display: none; }

        .upload-preview { margin-top: 15px; text-align: center; }

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

        .remove-image-btn:hover { background: #c82333; transform: scale(1.05); }

        .image-source-options { display: flex; gap: 10px; margin-bottom: 15px; }

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

        .image-source-btn.active { background: var(--primary); color: white; border-color: var(--primary); }
        .image-source-btn:hover:not(.active) { background: rgba(67, 97, 238, 0.2); }
        .image-source-btn i { margin-right: 5px; }

        .url-input-container { margin-top: 15px; }

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

        .add-user-btn:hover { background: var(--secondary); transform: scale(1.1) rotate(90deg); }

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

        .notification.success { background: linear-gradient(135deg, #10b981, #059669); }
        .notification.error   { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .notification.info    { background: linear-gradient(135deg, #3b82f6, #2563eb); }
        .notification.warning { background: linear-gradient(135deg, #f59e0b, #d97706); }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to   { transform: translateX(0);    opacity: 1; }
        }

        @keyframes slideOut {
            from { transform: translateX(0);    opacity: 1; }
            to   { transform: translateX(100%); opacity: 0; }
        }

        /* Dark Mode Overrides */
        .dark-mode .status-active   { background-color: #1a3d2f; color: #75b798; }
        .dark-mode .status-inactive { background-color: #5c1a22; color: #f1aeb5; }
        .dark-mode .status-pending  { background-color: #664d03; color: #ffda6a; }
        .dark-mode .role-admin      { background-color: #0d3c61; color: #64b5f6; }
        .dark-mode .role-user       { background-color: #4a1e6e; color: #ce93d8; }
        .dark-mode .role-moderator  { background-color: #1a472a; color: #81c784; }

        .dark-mode .file-upload-label       { background: rgba(67,97,238,0.05); border-color: rgba(67,97,238,0.3); }
        .dark-mode .file-upload-label:hover { background: rgba(67,97,238,0.1); }

        .dark-mode .leaflet-tile { filter: brightness(0.6) invert(1) contrast(3) hue-rotate(200deg) saturate(0.3) brightness(0.7); }
        .dark-mode .leaflet-container { background: #303030; }
        .dark-mode .leaflet-popup-content-wrapper { background: var(--card-bg); color: var(--text-color); }
        .dark-mode .leaflet-popup-tip { background: var(--card-bg); }

        @media (max-width: 768px) {
            .sidebar { width: 70px; }
            .sidebar:hover { width: 70px; }
            .sidebar:hover ~ .main-content { margin-left: 70px; }
            .main-content { margin-left: 70px; }
            .stats-container { grid-template-columns: 1fr; }
            .sidebar-header, .sidebar-menu span { display: none; }
            .sidebar-menu i { margin-right: 0; }
            #map { height: 400px; }
            .map-header { flex-direction: column; align-items: flex-start; gap: 10px; }
            .map-controls { width: 100%; justify-content: space-between; }
            .image-source-options { flex-direction: column; }
        }
    </style>
</head>
<body>

    <!-- ═══════════════════════════════════════════
         SIDEBAR
    ═══════════════════════════════════════════ -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-tachometer-alt"></i> Dashboard</h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
            <li><a href="members.php"><i class="fas fa-users"></i><span>Members</span></a></li>
            <li><a href="calendar.php"><i class="fas fa-calendar"></i><span>Calendar</span></a></li>
            <li><a href="#" class="active"><i class="fas fa-map-marked-alt"></i><span>Location</span></a></li>
<<<<<<< HEAD
            <li><a href="request.php"><i class="fas fa-clipboard-list"></i> <span>Requests</span></a></li>
            <li><a href="ordercard.php"><i class="fas fa-shopping-cart"></i> <span>Order</span></a></li>
            <li><a href="archive.php" class=""><i class="fas fa-archive"></i> <span>Archive</span></a></li>
            <li><a href="logs.php"><i class="fas fa-history"></i> <span>Activity Logs</span></a></li>
            <li><a href="e-portfolio.php"><i class="fas fa-id-card"></i> <span>E-Portfolio</span></a></li>
            <li><a href="rsvptracker.php"><i class="fas fa-calendar-check"></i> <span>RSVP Tracker</span></a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
=======
            <li><a href="request.php"><i class="fas fa-clipboard-list"></i><span>Requests</span></a></li>
            <li><a href="archive.php"><i class="fas fa-archive"></i><span>Archive</span></a></li>
            <li><a href="logs.php"><i class="fas fa-history"></i><span>Activity Logs</span></a></li>
            <li><a href="e-portfolio.php"><i class="fas fa-id-card"></i><span>E-Portfolio</span></a></li>
            <li><a href="rsvptracker.php"><i class="fas fa-calendar-check"></i><span>RSVP Tracker</span></a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
>>>>>>> ca8f670e5c2af47de48278dcac0b2882a8bf9f49
        </ul>
    </div>

    <!-- ═══════════════════════════════════════════
         MAIN CONTENT
    ═══════════════════════════════════════════ -->
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
                <div class="stat-number" id="totalUsers">0</div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="activeUsers">0</div>
                <div class="stat-label">Active Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="inactiveUsers">0</div>
                <div class="stat-label">Inactive Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="pendingUsers">0</div>
                <div class="stat-label">Pending Users</div>
            </div>
        </div>

        <div class="row">
            <!-- Map Column -->
            <div class="col-lg-9">
                <div class="map-container">
                    <div class="map-header">
                        <h2 class="map-title">
                            <i class="fas fa-map-marker-alt"></i> Rosario, Cavite - User Locations
                        </h2>
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

            <!-- Users List Column -->
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
                        <!-- User cards rendered by JS -->
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /main-content -->

    <!-- ═══════════════════════════════════════════
         FLOATING BUTTONS
    ═══════════════════════════════════════════ -->
    <button class="add-user-btn" id="addUserBtn" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="fas fa-plus"></i>
    </button>

    <button class="dark-mode-toggle" id="darkModeToggle">
        <i class="fas fa-moon" id="darkModeIcon"></i>
    </button>

    <!-- ═══════════════════════════════════════════
         ADD USER MODAL
    ═══════════════════════════════════════════ -->
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
                                <div id="newProfilePicText" style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:white;font-weight:bold;font-size:2rem;background-color:#4361ee;">
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
                            <div class="file-upload-container" id="newUploadSection">
                                <label for="newUserProfilePicUpload" class="file-upload-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Click to upload photo</span>
                                    <small>Supported formats: JPG, PNG, GIF (Max 5MB)</small>
                                </label>
                                <input type="file" class="file-upload-input" id="newUserProfilePicUpload" accept="image/*">
                                <div class="upload-preview" id="newUploadPreview"></div>
                            </div>
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

    <!-- ═══════════════════════════════════════════
         EDIT USER MODAL
    ═══════════════════════════════════════════ -->
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
                                <div id="editProfilePicText" style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:white;font-weight:bold;font-size:2rem;background-color:#4361ee;">
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
                            <div class="file-upload-container" id="editUploadSection">
                                <label for="editUserProfilePicUpload" class="file-upload-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Click to upload photo</span>
                                    <small>Supported formats: JPG, PNG, GIF (Max 5MB)</small>
                                </label>
                                <input type="file" class="file-upload-input" id="editUserProfilePicUpload" accept="image/*">
                                <div class="upload-preview" id="editUploadPreview"></div>
                            </div>
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

    <!-- ═══════════════════════════════════════════
         DELETE CONFIRMATION MODAL
    ═══════════════════════════════════════════ -->
    <div class="modal fade user-modal" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="deleteConfirmModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong id="deleteUserName"></strong>?</p>
                    <p class="text-danger">
                        <small>This action cannot be undone. The user will be removed from the map and user list.</small>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete User</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- ═══════════════════════════════════════════
         PAGE JAVASCRIPT  (UI logic only)
         All Firebase calls come from backend/firebase_operations.js
    ═══════════════════════════════════════════ -->
    <script type="module">

        // ── Import all backend operations from the shared module ──────────────
        // File lives at: Admin/backend/backend.js
        // Rename your backend.php → backend.js for this import to work
        import {
            fetchUsers,
            subscribeToUsers,
            addUser,
            updateUser,
            deleteUser,
            getInitials,
            getDefaultProfilePic,
            geocodeAddress
        } from "./backend/backend.js";
        // ─────────────────────────────────────────────────────────────────────

        document.addEventListener('DOMContentLoaded', function () {

            // ── Map setup ─────────────────────────────────────────────────────
            const mapEl = document.getElementById('map');
            if (!mapEl.style.height) mapEl.style.height = '600px';

            const map = L.map('map', { center: [14.4160, 120.8541], zoom: 14, zoomControl: true });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(map);

            // Force Leaflet to recalculate container size after DOM paint
            setTimeout(() => map.invalidateSize(), 300);

            // ── State ─────────────────────────────────────────────────────────
            let users            = [];
            let userMarkers      = [];
            let userMarkersVisible = true;
            let userToDelete     = null;
            let userToEdit       = null;
            let uploadedImages   = {};
            let previewMarker    = null;

            // ── Marker icon factory ───────────────────────────────────────────
            const markerIcon = (status, picUrl) => L.divIcon({
                html: `<div class="profile-pic-marker ${status.toLowerCase()}" style="background-image:url('${picUrl}')"></div>`,
                className: 'custom-div-icon',
                iconSize: [40, 40],
                iconAnchor: [20, 20]
            });

            // ── Subscribe to real-time Firestore updates ──────────────────────
            subscribeToUsers((updatedUsers) => {
                users = updatedUsers;
                renderMarkers();
                renderUserCards();
                updateStatistics();
            });

            // ── Render map markers ────────────────────────────────────────────
            function renderMarkers() {
                userMarkers.forEach(({ marker }) => map.removeLayer(marker));
                userMarkers = [];

                users.forEach(user => {
                    if (!user.coords || user.coords.length !== 2) return;

                    const marker = L.marker(user.coords, {
                        icon: markerIcon(user.status, user.profilePic)
                    }).bindPopup(`
                        <div class="user-popup">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="user-profile-pic" style="background-image:url('${user.profilePic}')"></div>
                                <div>
                                    <h5 class="mb-0">${user.name}</h5>
                                    <small>${user.role}</small>
                                </div>
                            </div>
                            <hr class="my-2">
                            <p class="mb-1"><strong>Email:</strong> ${user.email}</p>
                            <p class="mb-1"><strong>Status:</strong>
                                <span class="user-status status-${user.status.toLowerCase()}">${user.status}</span>
                            </p>
                            <p class="mb-1"><strong>Last Seen:</strong> ${user.lastSeen}</p>
                            <p class="mb-1"><strong>Address:</strong> ${user.address}</p>
                            <div class="d-flex gap-2 mt-2">
                                <button class="btn btn-sm btn-primary w-100" onclick="focusUserOnMap('${user.id}')">
                                    <i class="fas fa-map-marker-alt"></i> View
                                </button>
                                <button class="btn btn-sm btn-warning w-100" onclick="editUserProfile('${user.id}')">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-danger w-100" onclick="showDeleteConfirmation('${user.id}')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    `);

                    if (userMarkersVisible) marker.addTo(map);
                    userMarkers.push({ id: user.id, marker, user });
                });
            }

            // ── Render sidebar user cards ─────────────────────────────────────
            function renderUserCards() {
                const container = document.getElementById('userLocationsList');
                container.innerHTML = '';

                users.forEach(user => {
                    const card = document.createElement('div');
                    card.className = 'user-card';
                    card.setAttribute('data-user-id', user.id);

                    card.innerHTML = `
                        <div class="user-card-actions">
                            <button class="user-action-btn edit-user-btn" onclick="editUserProfile('${user.id}')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="user-action-btn delete-user-btn" onclick="showDeleteConfirmation('${user.id}')">
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
                            <div class="user-profile-pic" style="background-image:url('${user.profilePic}')"></div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-clock"></i> Last seen: ${user.lastSeen}
                            </small>
                            <button class="btn btn-sm btn-outline-primary mt-2 w-100"
                                onclick="focusUserOnMap('${user.id}')">
                                <i class="fas fa-map-marker-alt"></i> View on Map
                            </button>
                        </div>
                    `;

                    card.addEventListener('click', function (e) {
                        if (!e.target.closest('button')) focusUserOnMap(user.id);
                    });

                    container.appendChild(card);
                });
            }

            // ── Update stats counters ─────────────────────────────────────────
            function updateStatistics() {
                document.getElementById('totalUsers').textContent    = users.length;
                document.getElementById('activeUsers').textContent   = users.filter(u => u.status === 'Active').length;
                document.getElementById('inactiveUsers').textContent = users.filter(u => u.status === 'Inactive').length;
                document.getElementById('pendingUsers').textContent  = users.filter(u => u.status === 'Pending').length;
            }

            // ── Focus map on a user ───────────────────────────────────────────
            window.focusUserOnMap = function (userId) {
                const user = users.find(u => u.id === userId);
                if (!user || !user.coords || user.coords.length !== 2) return;

                map.flyTo(user.coords, 16, { animate: true });

                const entry = userMarkers.find(m => m.id === userId);
                if (entry?.marker) entry.marker.openPopup();

                document.querySelectorAll('.user-card').forEach(c => c.classList.remove('active'));
                const card = document.querySelector(`[data-user-id="${userId}"]`);
                if (card) card.classList.add('active');
            };

            // ── Open edit modal ───────────────────────────────────────────────
            window.editUserProfile = function (userId) {
                event.stopPropagation();
                const user = users.find(u => u.id === userId);
                if (!user) return;

                userToEdit = user;

                document.getElementById('editUserId').value      = user.id;
                document.getElementById('editUserName').value    = user.name;
                document.getElementById('editUserEmail').value   = user.email;
                document.getElementById('editUserRole').value    = user.role;
                document.getElementById('editUserStatus').value  = user.status;
                document.getElementById('editUserAddress').value = user.address;
                document.getElementById('editUserLat').value     = user.coords[0];
                document.getElementById('editUserLng').value     = user.coords[1];
                document.getElementById('editUserAvatar').value  = user.avatar || getInitials(user.name);
                document.getElementById('editUserLastSeen').value = user.lastSeen;
                document.getElementById('editUserProfilePicUrl').value = uploadedImages[user.id] ? '' : (user.profilePic || '');

                updateEditProfilePreview();
                new bootstrap.Modal(document.getElementById('editUserModal')).show();

                map.flyTo(user.coords, 16);
                const entry = userMarkers.find(m => m.id === userId);
                if (entry?.marker) setTimeout(() => entry.marker.openPopup(), 1000);

                // Live geocode while typing address
                const addressInput = document.getElementById('editUserAddress');
                let addressTimeout;
                addressInput.addEventListener('input', function () {
                    clearTimeout(addressTimeout);
                    addressTimeout = setTimeout(async () => {
                        if (this.value.length <= 3) return;
                        const coords = await geocodeAddress(this.value);
                        if (!coords) return;
                        document.getElementById('editUserLat').value = coords.lat.toFixed(6);
                        document.getElementById('editUserLng').value = coords.lng.toFixed(6);
                        map.flyTo([coords.lat, coords.lng], 16);
                        if (previewMarker) map.removeLayer(previewMarker);
                        previewMarker = L.marker([coords.lat, coords.lng], {
                            icon: L.divIcon({
                                html: '<div style="width:40px;height:40px;background:rgba(255,0,0,0.5);border:3px solid red;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:bold;font-size:12px;">?</div>',
                                className: 'custom-div-icon',
                                iconSize: [40, 40],
                                iconAnchor: [20, 20]
                            })
                        }).addTo(map).bindPopup('Preview of new location<br>Click "Save Changes" to confirm').openPopup();
                    }, 800);
                });
            };

            // ── Show delete confirmation ──────────────────────────────────────
            window.showDeleteConfirmation = function (userId) {
                event.stopPropagation();
                const user = users.find(u => u.id === userId);
                if (!user) return;
                userToDelete = user;
                document.getElementById('deleteUserName').textContent = user.name;
                new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
            };

            // ── Profile preview helpers ───────────────────────────────────────
            function updateNewProfilePreview() {
                const name   = document.getElementById('newUserName').value || 'John Doe';
                const role   = document.getElementById('newUserRole').value || 'User';
                const status = document.getElementById('newUserStatus').value || 'Active';
                const avatar = document.getElementById('newUserAvatar').value || getInitials(name) || 'JD';
                const url    = document.getElementById('newUserProfilePicUrl').value;

                document.getElementById('newProfileNamePreview').textContent = name;
                document.getElementById('newProfileRolePreview').textContent = role;

                const statusEl = document.getElementById('newProfileStatusPreview');
                statusEl.textContent = status;
                statusEl.className = `user-status status-${status.toLowerCase()}`;

                const preview = document.getElementById('newProfilePicPreview');
                const text    = document.getElementById('newProfilePicText');
                if (url) {
                    preview.style.backgroundImage = `url('${url}')`;
                    text.style.display = 'none';
                } else {
                    preview.style.backgroundImage = '';
                    preview.style.backgroundColor = '#4361ee';
                    text.textContent = avatar;
                    text.style.display = 'flex';
                }
            }

            function updateEditProfilePreview() {
                const name   = document.getElementById('editUserName').value;
                const role   = document.getElementById('editUserRole').value;
                const status = document.getElementById('editUserStatus').value;
                const avatar = document.getElementById('editUserAvatar').value || getInitials(name);
                const url    = document.getElementById('editUserProfilePicUrl').value;
                const userId = document.getElementById('editUserId').value;

                document.getElementById('editProfileNamePreview').textContent = name;
                document.getElementById('editProfileRolePreview').textContent = role;

                const statusEl = document.getElementById('editProfileStatusPreview');
                statusEl.textContent = status;
                statusEl.className = `user-status status-${status.toLowerCase()}`;

                const preview = document.getElementById('editProfilePicPreview');
                const text    = document.getElementById('editProfilePicText');
                if (uploadedImages[userId]) {
                    preview.style.backgroundImage = `url('${uploadedImages[userId]}')`;
                    text.style.display = 'none';
                } else if (url) {
                    preview.style.backgroundImage = `url('${url}')`;
                    text.style.display = 'none';
                } else {
                    preview.style.backgroundImage = '';
                    preview.style.backgroundColor = '#4361ee';
                    text.textContent = avatar;
                    text.style.display = 'flex';
                }
            }

            // ── Image source toggle (Upload / URL) ────────────────────────────
            function initImageSourceOptions() {
                [
                    { optionsId: 'newImageSourceOptions', uploadId: 'newUploadSection', urlId: 'newUrlSection' },
                    { optionsId: 'editImageSourceOptions', uploadId: 'editUploadSection', urlId: 'editUrlSection' }
                ].forEach(({ optionsId, uploadId, urlId }) => {
                    document.querySelectorAll(`#${optionsId} .image-source-btn`).forEach(btn => {
                        btn.addEventListener('click', function () {
                            document.querySelectorAll(`#${optionsId} .image-source-btn`).forEach(b => b.classList.remove('active'));
                            this.classList.add('active');
                            const isUpload = this.getAttribute('data-source') === 'upload';
                            document.getElementById(uploadId).classList.toggle('d-none', !isUpload);
                            document.getElementById(urlId).classList.toggle('d-none', isUpload);
                        });
                    });
                });
            }

            // ── File upload handlers ──────────────────────────────────────────
            function initFileUploadHandlers() {
                function handleUpload(inputId, previewId, profilePreviewId, profileTextId, tempKey, type) {
                    document.getElementById(inputId).addEventListener('change', function (e) {
                        const file = e.target.files[0];
                        if (!file) return;
                        if (file.size > 5 * 1024 * 1024) { showNotification('File size must be less than 5MB', 'error'); this.value = ''; return; }
                        if (!file.type.match('image.*')) { showNotification('Please select an image file', 'error'); this.value = ''; return; }

                        const reader = new FileReader();
                        reader.onload = (evt) => {
                            const imageData = evt.target.result;
                            const key = tempKey || document.getElementById('editUserId').value;
                            uploadedImages[key] = imageData;

                            const previewContainer = document.getElementById(previewId);
                            const img = Object.assign(document.createElement('img'), { src: imageData, alt: 'Profile Preview' });
                            const removeBtn = document.createElement('button');
                            removeBtn.type = 'button';
                            removeBtn.className = 'remove-image-btn';
                            removeBtn.innerHTML = '<i class="fas fa-trash"></i> Remove Image';
                            removeBtn.onclick = () => {
                                delete uploadedImages[key];
                                previewContainer.innerHTML = '';
                                document.getElementById(inputId).value = '';
                                type === 'new' ? updateNewProfilePreview() : updateEditProfilePreview();
                            };

                            previewContainer.innerHTML = '';
                            previewContainer.append(img, removeBtn);

                            document.getElementById(profilePreviewId).style.backgroundImage = `url('${imageData}')`;
                            document.getElementById(profileTextId).style.display = 'none';
                        };
                        reader.readAsDataURL(file);
                    });
                }

                handleUpload('newUserProfilePicUpload', 'newUploadPreview', 'newProfilePicPreview', 'newProfilePicText', `temp-${Date.now()}`, 'new');
                handleUpload('editUserProfilePicUpload', 'editUploadPreview', 'editProfilePicPreview', 'editProfilePicText', null, 'edit');
            }

            // ── Save NEW user ─────────────────────────────────────────────────
            document.getElementById('saveNewUser').addEventListener('click', async function () {
                const name    = document.getElementById('newUserName').value.trim();
                const email   = document.getElementById('newUserEmail').value.trim();
                const role    = document.getElementById('newUserRole').value;
                const status  = document.getElementById('newUserStatus').value;
                const address = document.getElementById('newUserAddress').value.trim();
                const lat     = parseFloat(document.getElementById('newUserLat').value);
                const lng     = parseFloat(document.getElementById('newUserLng').value);
                const avatar  = document.getElementById('newUserAvatar').value;
                const url     = document.getElementById('newUserProfilePicUrl').value;

                const tempKeys     = Object.keys(uploadedImages).filter(k => k.startsWith('temp-'));
                const uploadedImage = tempKeys.length ? uploadedImages[tempKeys[0]] : null;

                if (!name || !email || !role || !status || !address || isNaN(lat) || isNaN(lng)) {
                    showNotification('Please fill all required fields correctly.', 'error');
                    return;
                }

                try {
                    // ── BACKEND CALL ─────────────────────────────────────────
                    await addUser({
                        name, email, role, status, address,
                        coords: [lat, lng],
                        avatar: avatar || getInitials(name),
                        profilePic: uploadedImage || url || getDefaultProfilePic()
                    });
                    // ─────────────────────────────────────────────────────────

                    if (uploadedImage) tempKeys.forEach(k => delete uploadedImages[k]);

                    bootstrap.Modal.getInstance(document.getElementById('addUserModal')).hide();
                    document.getElementById('addUserForm').reset();
                    document.getElementById('newUploadPreview').innerHTML = '';
                    updateNewProfilePreview();
                    showNotification(`User "${name}" added successfully!`, 'success');

                } catch (err) {
                    showNotification('Failed to save user. Check console.', 'error');
                }
            });

            // ── Save EDITED user ──────────────────────────────────────────────
            document.getElementById('saveEditUser').addEventListener('click', async function () {
                const userId = document.getElementById('editUserId').value;
                if (!userId) return;

                const profilePic = uploadedImages[userId]
                    || document.getElementById('editUserProfilePicUrl').value.trim()
                    || null;

                try {
                    // ── BACKEND CALL ─────────────────────────────────────────
                    await updateUser(userId, {
                        name:       document.getElementById('editUserName').value.trim(),
                        email:      document.getElementById('editUserEmail').value.trim(),
                        role:       document.getElementById('editUserRole').value,
                        status:     document.getElementById('editUserStatus').value,
                        address:    document.getElementById('editUserAddress').value.trim(),
                        coords: [
                            parseFloat(document.getElementById('editUserLat').value),
                            parseFloat(document.getElementById('editUserLng').value)
                        ],
                        avatar:      document.getElementById('editUserAvatar').value.toUpperCase(),
                        profilePic
                    });
                    // ─────────────────────────────────────────────────────────

                    bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
                    if (previewMarker) { map.removeLayer(previewMarker); previewMarker = null; }
                    showNotification('User updated successfully!', 'success');

                } catch (err) {
                    showNotification('Failed to save changes. Check console.', 'error');
                }
            });

            // ── Confirm DELETE ────────────────────────────────────────────────
            document.getElementById('confirmDeleteBtn').addEventListener('click', async function () {
                if (!userToDelete) return;
                try {
                    // ── BACKEND CALL ─────────────────────────────────────────
                    await deleteUser(userToDelete.id);
                    // ─────────────────────────────────────────────────────────

                    bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal')).hide();
                    showNotification(`User "${userToDelete.name}" deleted successfully!`, 'success');
                    userToDelete = null;

                } catch (err) {
                    showNotification('Failed to delete user. Check console.', 'error');
                }
            });

            // ── Map controls ──────────────────────────────────────────────────
            document.getElementById('locateMe').addEventListener('click', function () {
                if (!navigator.geolocation) { alert('Geolocation not supported.'); return; }
                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        const loc = [pos.coords.latitude, pos.coords.longitude];
                        L.marker(loc, {
                            icon: L.divIcon({
                                html: '<div class="current-user-marker"><i class="fas fa-user-circle"></i></div>',
                                className: 'custom-div-icon',
                                iconSize: [45, 45],
                                iconAnchor: [22, 22]
                            })
                        }).addTo(map).bindPopup('You are here!').openPopup();
                        map.flyTo(loc, 15);
                    },
                    () => alert('Unable to retrieve your location.')
                );
            });

            document.getElementById('resetView').addEventListener('click', function () {
                map.flyTo([14.4160, 120.8541], 14);
                document.querySelectorAll('.user-card').forEach(c => c.classList.remove('active'));
                if (previewMarker) { map.removeLayer(previewMarker); previewMarker = null; }
            });

            document.getElementById('toggleUsers').addEventListener('click', function () {
                userMarkersVisible = !userMarkersVisible;
                userMarkers.forEach(({ marker }) => {
                    userMarkersVisible ? marker.addTo(map) : map.removeLayer(marker);
                });
                this.innerHTML = userMarkersVisible
                    ? '<i class="fas fa-users"></i> Hide Users'
                    : '<i class="fas fa-users"></i> Show Users';
            });

            // ── Search ────────────────────────────────────────────────────────
            document.getElementById('searchUsers').addEventListener('input', function () {
                const term = this.value.toLowerCase();
                users.forEach(user => {
                    const matches =
                        user.name.toLowerCase().includes(term) ||
                        user.email.toLowerCase().includes(term) ||
                        user.address.toLowerCase().includes(term) ||
                        user.role.toLowerCase().includes(term);

                    const entry = userMarkers.find(m => m.id === user.id);
                    const card  = document.querySelector(`[data-user-id="${user.id}"]`);

                    if (entry) {
                        (!term || matches) && userMarkersVisible ? entry.marker.addTo(map) : map.removeLayer(entry.marker);
                    }
                    if (card) card.style.display = (!term || matches) ? '' : 'none';
                });
            });

            document.getElementById('searchButton').addEventListener('click', () => document.getElementById('searchUsers').focus());

            // ── Map click → update lat/lng in forms ───────────────────────────
            map.on('click', function (e) {
                document.getElementById('newUserLat').value = e.latlng.lat.toFixed(6);
                document.getElementById('newUserLng').value = e.latlng.lng.toFixed(6);

                if (document.getElementById('editUserModal').classList.contains('show')) {
                    document.getElementById('editUserLat').value = e.latlng.lat.toFixed(6);
                    document.getElementById('editUserLng').value = e.latlng.lng.toFixed(6);
                    updateEditProfilePreview();
                }
            });

            // ── Modal reset handlers ──────────────────────────────────────────
            document.getElementById('addUserModal').addEventListener('hidden.bs.modal', function () {
                document.getElementById('addUserForm').reset();
                document.getElementById('newUploadPreview').innerHTML = '';
                updateNewProfilePreview();
            });

            document.getElementById('editUserModal').addEventListener('hidden.bs.modal', function () {
                document.getElementById('editUploadPreview').innerHTML = '';
                if (previewMarker) { map.removeLayer(previewMarker); previewMarker = null; }
            });

            // ── Real-time form previews ───────────────────────────────────────
            ['newUserName', 'newUserRole', 'newUserStatus', 'newUserAvatar'].forEach(id =>
                document.getElementById(id).addEventListener('input', updateNewProfilePreview)
            );
            ['editUserName', 'editUserRole', 'editUserStatus', 'editUserAvatar'].forEach(id =>
                document.getElementById(id).addEventListener('input', updateEditProfilePreview)
            );
            document.getElementById('newUserProfilePicUrl').addEventListener('input', updateNewProfilePreview);
            document.getElementById('editUserProfilePicUrl').addEventListener('input', updateEditProfilePreview);

            // ── Dark mode ─────────────────────────────────────────────────────
            const darkIcon = document.getElementById('darkModeIcon');
            if (localStorage.getItem('darkMode') === 'enabled') {
                document.body.classList.add('dark-mode');
                darkIcon.classList.replace('fa-moon', 'fa-sun');
            }

            document.getElementById('darkModeToggle').addEventListener('click', function () {
                document.body.classList.toggle('dark-mode');
                const isDark = document.body.classList.contains('dark-mode');
                darkIcon.classList.replace(isDark ? 'fa-moon' : 'fa-sun', isDark ? 'fa-sun' : 'fa-moon');
                localStorage.setItem('darkMode', isDark ? 'enabled' : 'disabled');
            });

            // ── Notification helper ───────────────────────────────────────────
            function showNotification(message, type) {
                const icons = { success: 'check-circle', error: 'exclamation-circle', warning: 'exclamation-triangle', info: 'info-circle' };
                const el = document.createElement('div');
                el.className = `notification ${type}`;
                el.innerHTML = `<i class="fas fa-${icons[type] || 'info-circle'}"></i><span>${message}</span>`;
                document.body.appendChild(el);
                setTimeout(() => {
                    el.style.animation = 'slideOut 0.3s ease';
                    setTimeout(() => el.parentNode?.removeChild(el), 300);
                }, 3000);
            }

            // ── Init ──────────────────────────────────────────────────────────
            initImageSourceOptions();
            initFileUploadHandlers();
            updateNewProfilePreview();
        });
    </script>
</body>
</html>