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
    <title>Event Calendar - Dashboard</title>
    <link rel="icon" type="icon" href="CBOC LOGO.jpg"/>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Quill Rich Text Editor -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="icon" type="icon" href="calendar.png"/>
    <script src="https://www.gstatic.com/firebasejs/12.9.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/12.9.0/firebase-firestore-compat.js"></script>
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

        .dark-mode .ql-toolbar.ql-snow {
            border-color: #444 !important;
            background-color: #2a2a2a !important;
        }

        .dark-mode .ql-container.ql-snow {
            border-color: #444 !important;
            background-color: #2a2a2a !important;
        }

        .dark-mode .ql-editor {
            color: #e9ecef !important;
        }

        .dark-mode .ql-snow .ql-stroke {
            stroke: #e9ecef !important;
        }

        .dark-mode .ql-snow .ql-fill {
            fill: #e9ecef !important;
        }

        .dark-mode .ql-snow .ql-picker {
            color: #e9ecef !important;
        }

        .dark-mode .ql-snow .ql-picker-options {
            background-color: #2a2a2a !important;
            border-color: #444 !important;
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

        /* Philippine Time Clock Styles */
        .ph-time-clock {
            display: flex;
            align-items: center;
            font-size: 0.95rem;
            color: var(--gray);
            margin-top: 5px;
            background: rgba(67, 97, 238, 0.1);
            padding: 5px 12px;
            border-radius: 20px;
            border: 1px solid rgba(67, 97, 238, 0.2);
            width: fit-content;
        }

        .ph-time-clock i {
            color: var(--primary);
        }

        #phTime {
            color: var(--primary);
            font-size: 1.1rem;
            margin: 0 8px;
            font-family: 'Courier New', monospace;
        }

        .timezone-label {
            color: var(--gray);
            font-size: 0.8rem;
        }

        .dark-mode .ph-time-clock {
            background: rgba(67, 97, 238, 0.15);
            border-color: rgba(67, 97, 238, 0.3);
        }

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

        /* Calendar Styles */
        .calendar-container {
            background: var(--card-bg);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            border: 1px solid var(--border-color);
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .calendar-nav-btn {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 15px;
            transition: all 0.3s ease;
        }

        .calendar-nav-btn:hover {
            background: var(--secondary);
            transform: scale(1.05);
        }

        .calendar-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary);
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            background-color: var(--border-color);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
        }

        .calendar-day-header {
            background-color: var(--primary);
            color: white;
            text-align: center;
            padding: 10px;
            font-weight: 600;
        }

        .calendar-day {
            background-color: var(--card-bg);
            min-height: 100px;
            padding: 8px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            position: relative;
        }

        .calendar-day:hover {
            background-color: rgba(67, 97, 238, 0.05);
            transform: scale(1.02);
            z-index: 1;
        }

        .calendar-day.other-month {
            background-color: rgba(108, 117, 125, 0.1);
            color: var(--gray);
        }

        .calendar-day.today {
            background-color: rgba(67, 97, 238, 0.1);
            border: 2px solid var(--primary);
        }

        .day-number {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .event-item {
            background-color: var(--primary);
            color: white;
            border-radius: 3px;
            padding: 2px 5px;
            margin-bottom: 3px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            position: relative;
        }

        .event-item:hover {
            background-color: var(--secondary);
            transform: translateX(3px);
        }

        .event-item.meeting {
            background-color: #4cc9f0;
        }

        .event-item.deadline {
            background-color: #f72585;
        }

        .event-item.event {
            background-color: #7209b7;
        }

        .event-item.training {
            background-color: #2a9d8f;
        }

        .event-item.reminder {
            background-color: #e9c46a;
        }

        .event-modal .modal-content {
            background-color: var(--card-bg);
            color: var(--text-color);
            border: 1px solid var(--border-color);
        }

        .event-modal .modal-header {
            border-bottom: 1px solid var(--border-color);
        }

        .event-modal .modal-footer {
            border-top: 1px solid var(--border-color);
        }

        .event-form label {
            color: var(--text-color);
        }

        .event-form .form-control, .event-form .form-select {
            background-color: var(--card-bg);
            color: var(--text-color);
            border: 1px solid var(--border-color);
        }

        .event-form .form-control:focus, .event-form .form-select:focus {
            background-color: var(--card-bg);
            color: var(--text-color);
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }

        /* Image Preview Styles */
        .image-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .image-preview {
            position: relative;
            width: 100px;
            height: 100px;
            border-radius: 5px;
            overflow: hidden;
            border: 2px solid var(--border-color);
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .remove-image {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
        }

        .remove-image:hover {
            background: rgba(255, 0, 0, 0.8);
        }

        /* Rich Text Editor */
        .ql-toolbar.ql-snow {
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            background-color: var(--card-bg);
            border-color: var(--border-color) !important;
        }

        .ql-container.ql-snow {
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            border-color: var(--border-color) !important;
            background-color: var(--card-bg);
            min-height: 150px;
        }

        .ql-editor {
            color: var(--text-color);
            min-height: 150px;
            max-height: 300px;
            overflow-y: auto;
        }

        /* Event Card Styles */
        .event-card {
            background: var(--card-bg);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            border: 1px solid var(--border-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .event-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .event-card-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .event-card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--primary);
        }

        .event-card-meta {
            display: flex;
            gap: 15px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }

        .event-meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
            color: var(--gray);
            font-size: 0.9rem;
        }

        .event-meta-item i {
            color: var(--primary);
        }

        .event-card-description {
            color: var(--text-color);
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .event-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid var(--border-color);
        }

        .event-status {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-published {
            color: #2a9d8f;
            background: rgba(42, 157, 143, 0.1);
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.85rem;
        }

        .status-draft {
            color: #e76f51;
            background: rgba(231, 111, 81, 0.1);
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.85rem;
        }

        .event-actions {
            display: flex;
            gap: 10px;
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

        /* Event Details Modal */
        .event-details-modal .modal-content {
            background-color: var(--card-bg);
            color: var(--text-color);
            border: 1px solid var(--border-color);
        }

        .event-details-modal .modal-header {
            border-bottom: 1px solid var(--border-color);
        }

        .event-details-modal .modal-footer {
            border-top: 1px solid var(--border-color);
        }

        .event-details-image {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        /* Delete Confirmation Modal */
        .delete-confirm-modal .modal-content {
            background-color: var(--card-bg);
            color: var(--text-color);
            border: 1px solid var(--border-color);
        }

        .delete-confirm-modal .modal-header {
            border-bottom: 1px solid var(--border-color);
        }

        .delete-confirm-modal .modal-footer {
            border-top: 1px solid var(--border-color);
        }

        /* Switch toggle */
        .form-switch .form-check-input {
            width: 3em;
            height: 1.5em;
        }

        .form-switch .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
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
        
        @media (max-width: 576px) {
            .calendar-day {
                min-height: 60px;
                padding: 5px;
            }
            
            .day-number {
                font-size: 0.9rem;
            }
            
            .event-item {
                display: none;
            }
            
            .calendar-day.has-events::after {
                content: "•";
                color: var(--primary);
                position: absolute;
                top: 5px;
                right: 5px;
                font-size: 1.2rem;
            }
            
            .event-card-footer {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }
            
            .event-actions {
                width: 100%;
                justify-content: space-between;
            }

            .ph-time-clock {
                flex-wrap: wrap;
                justify-content: center;
                width: 100%;
                margin-top: 10px;
            }
            
            #phDate {
                order: 1;
                width: 100%;
                text-align: center;
                margin-bottom: 3px;
            }
            
            #phTime {
                order: 2;
            }
            
            .timezone-label {
                order: 3;
            }
        }
        .calendar-day.past-date {
            background-color: rgba(108, 117, 125, 0.2);
            opacity: 0.7;
            position: relative;
        }

        .calendar-day.past-date::after {
            content: "🔒";
            position: absolute;
            bottom: 5px;
            right: 5px;
            font-size: 12px;
            opacity: 0.5;
        }

        .date-warning {
            color: #f72585;
            font-size: 0.8rem;
            margin-top: 5px;
        }

        .form-date-limit {
            font-size: 0.8rem;
            color: var(--gray);
            margin-top: 5px;
        }

        #publishedEventsWrapper {
            transition: all 0.3s ease;
        }

        .events-collapsed {
            max-height: 500px;
            overflow-y: auto;
        }

        .events-expanded {
            max-height: none;
            overflow: visible;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-tachometer-alt"> </i>CBOC</h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
            <li><a href="members.php"><i class="fas fa-users"></i><span>Users</span></a></li>
            <li><a href="location.php"><i class="fas fa-map-marked-alt"></i><span>Location</span></a></li>
            <li><a href="e-portfolio.php"><i class="fas fa-id-card"></i><span>E-Portfolio</span></a></li>
            <li><a href="#" class="active"><i class="fas fa-calendar"></i><span>Calendar</span></a></li>
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
            <div>
                <h1>Event Calendar</h1>
                <div id="philippineClock" class="ph-time-clock">
                    <i class="fas fa-clock me-2"></i>
                    <span id="phDate"></span>
                    <span id="phTime" class="fw-bold"></span>
                    <small class="timezone-label">(PHT)</small>
                </div>
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
                        <button class="dropdown-item" id="exportEvents">
                            <i class="fas fa-download"></i> Export Events
                        </button>
                        <button class="dropdown-item" id="printCalendar">
                            <i class="fas fa-print"></i> Print Calendar
                        </button>
                        <div class="dropdown-divider"></div>
                        <button class="dropdown-item" id="refreshCalendar">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                        <button class="dropdown-item" id="showCalendarHelp">
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

        <!-- Moved sections to the top (BEFORE calendar) -->
        <div class="row mb-4">
            <!-- Upcoming Events Section -->
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="section-title mb-0">
                        <i class="fas fa-list"></i> Published Events
                    </h3>

                    <button id="toggleEventsBtn" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-expand"></i> Expand
                    </button>
                </div>

                <div id="publishedEventsWrapper" class="events-collapsed">
                    <div id="publishedEvents"></div>
                </div>
            </div>
            
            <!-- Add New Event Section -->
            <div class="col-lg-4">
                <div class="calendar-container">
                    <h3 class="section-title">
                        <i class="fas fa-plus-circle"></i> Event Management
                    </h3>

                    <button class="btn btn-primary w-100 mb-3" data-bs-toggle="modal" data-bs-target="#eventModal">
                        <i class="fas fa-plus me-2"></i> Create New Event
                    </button>

                    <div class="mb-4">
                        <h6 class="mb-3">Event Statistics</h6>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="stat-number" id="totalEvents">0</div>
                                <div class="stat-label">Total Events</div>
                            </div>
                            <div class="col-6">
                                <div class="stat-number" id="publishedCount">0</div>
                                <div class="stat-label">Published</div>
                            </div>
                        </div>
                    </div>

                    <div class="event-categories">
                        <h6 class="mb-3">Event Categories</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-primary">Meeting</span>
                            <span class="badge bg-danger">Deadline</span>
                            <span class="badge bg-purple">Event</span>
                            <span class="badge bg-success">Training</span>
                            <span class="badge bg-warning">Reminder</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Approval List (if there are any) -->
            <div id="pendingEventsContainer" class="d-none"></div>
        </div>

        <!-- Calendar Container -->
        <div class="calendar-container">
            <div class="calendar-header">
                <button class="calendar-nav-btn" id="prevMonth">
                    <i class="fas fa-chevron-left"></i> Previous
                </button>
                <h2 class="calendar-title" id="currentMonthYear">June 2023</h2>
                <button class="calendar-nav-btn" id="nextMonth">
                    Next <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <div class="calendar-grid" id="calendarGrid">
                <!-- Calendar headers -->
                <div class="calendar-day-header">Sun</div>
                <div class="calendar-day-header">Mon</div>
                <div class="calendar-day-header">Tue</div>
                <div class="calendar-day-header">Wed</div>
                <div class="calendar-day-header">Thu</div>
                <div class="calendar-day-header">Fri</div>
                <div class="calendar-day-header">Sat</div>
                
                <!-- Calendar days will be populated by JavaScript -->
            </div>
        </div>

    </div>

    <!-- Event Modal -->
    <div class="modal fade event-modal" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Create New Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="eventForm" class="event-form">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="eventTitle" class="form-label">Event Title *</label>
                                <input type="text" class="form-control" id="eventTitle" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="eventCategory" class="form-label">Category</label>
                                <select class="form-select" id="eventCategory">
                                    <option value="meeting">Meeting</option>
                                    <option value="deadline">Deadline</option>
                                    <option value="event" selected>Event</option>
                                    <option value="training">Training</option>
                                    <option value="reminder">Reminder</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="eventDate" class="form-label">Date *</label>
                                <input type="date" class="form-control" id="eventDate" required>
                                <div id="dateWarning" class="date-warning"></div>
                                <div class="form-date-limit">
                                 <i class="fas fa-info-circle"></i> Past dates are disabled. Maximum 5 months ahead.
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="startTime" class="form-label">Start Time</label>
                                <input type="time" class="form-control" id="startTime">
                            </div>
                            <div class="col-md-3">
                                <label for="endTime" class="form-label">End Time</label>
                                <input type="time" class="form-control" id="endTime">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="eventVenue" class="form-label">Venue/Location</label>
                            <input type="text" class="form-control" id="eventVenue" placeholder="Enter event location">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Event Images</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="eventImages" accept="image/*" multiple>
                                <button class="btn btn-outline-secondary" type="button" id="clearImages">
                                    <i class="fas fa-times"></i> Clear
                                </button>
                            </div>
                            <div class="form-text">You can upload multiple images</div>
                            <div class="image-preview-container" id="imagePreview"></div>
                        </div>

                        <div class="mb-3">
                            <label for="eventDescription" class="form-label">Description</label>
                            <div id="richTextEditor"></div>
                            <input type="hidden" id="eventDescription">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="publishEvent">
                                    <label class="form-check-label" for="publishEvent">Publish to Calendar</label>
                                </div>
                                <div class="form-text">Published events will be visible to everyone</div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="sendNotification">
                                    <label class="form-check-label" for="sendNotification">Send Notifications</label>
                                </div>
                                <div class="form-text">Send email notifications to members</div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-outline-primary" id="saveDraft">Save as Draft</button>
                    <button type="button" class="btn btn-primary" id="saveEvent">Save & Publish</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Details Modal -->
    <div class="modal fade event-details-modal" id="eventDetailsModal" tabindex="-1" aria-labelledby="eventDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventDetailsModalLabel">Event Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="eventDetailsContent">
                    <!-- Event details will be populated by JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-warning" id="editEventBtn">Edit</button>
                    <button type="button" class="btn btn-danger" id="deleteEventBtn">Delete Event</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade delete-confirm-modal" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this event? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
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
    <!-- Quill Rich Text Editor -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    
    <!-- JavaScript for Calendar -->
    <script type="module" src="backend/calendar.js"></script>
    <script type="module" src="backend/backend.js"></script>
</body>
</html>