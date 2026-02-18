<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Calendar - Dashboard</title>
    <link rel="icon" type="icon" href="CBOC LOGO.jpg"/>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
            <li><a href="calendar.php" class="active"><i class="fas fa-calendar"></i> <span>Calendar</span></a></li>
            <li><a href="location.php"><i class="fas fa-map-marked-alt"></i><span>Location</span></a></li>
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
            <div>
                <h1>Event Calendar</h1>
                <div id="philippineClock" class="ph-time-clock">
                    <i class="fas fa-clock me-2"></i>
                    <span id="phDate"></span>
                    <span id="phTime" class="fw-bold"></span>
                    <small class="timezone-label">(PHT)</small>
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

        <!-- Moved sections to the top (BEFORE calendar) -->
        <div class="row mb-4">
            <!-- Upcoming Events Section -->
            <div class="col-lg-8">
                <div class="calendar-container">
                    <h3 class="section-title"><i class="fas fa-list"></i> Published Events</h3>
                    <div id="publishedEvents">
                        <!-- Published events will be populated by JavaScript -->
                    </div>
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

            <script type="module">
                import { db } from "./Firebase/firebase_conn.js";
                import {
                    collection,
                    query,
                    where,
                    getDocs,
                    onSnapshot,
                    updateDoc,
                    deleteDoc,
                    doc
                } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-firestore.js";
                
                const defaultUI = document.getElementById("defaultEventManagement");
                const pendingContainer = document.getElementById("pendingEventsContainer");

                function loadPendingEvents() {
                    const q = query(
                        collection(db, "events"),
                        where("approved", "==", false)
                    );

                    //const snapshot = await getDocs(q);
                    onSnapshot(q, (snapshot) => {

                        // 🔹 No pending events → show original UI
                        if (snapshot.empty) {
                            pendingContainer.classList.add("d-none");
                            return;
                        }

                        // 🔹 Pending events exist
                        pendingContainer.classList.remove("d-none");

                        pendingContainer.innerHTML = `
                            <div class="col-lg-4">
                                <div class="calendar-container">
                                    <h3 class="section-title">
                                        <i class="fas fa-clock"></i> Pending Event Approvals
                                    </h3>
                                    <div class="list-group" id="pendingList"></div>
                                </div>
                            </div>
                        `;

                        const list = document.getElementById("pendingList");

                        snapshot.forEach(docSnap => {
                            const event = docSnap.data();

                            list.innerHTML += `
                                <div class="list-group-item mb-2">
                                    <h6 class="mb-1">${event.title ?? "Untitled Event"}</h6>
                                    <p class="mb-2 text-muted">${event.description ?? ""}</p>

                                    <div class="d-flex gap-2">
                                        <button class="btn btn-success btn-sm"
                                            onclick="approveEvent('${docSnap.id}')">
                                            Accept
                                        </button>
                                        <button class="btn btn-danger btn-sm"
                                            onclick="rejectEvent('${docSnap.id}')">
                                            Reject
                                        </button>
                                    </div>
                                </div>
                            `;
                        });
                    });
                }

                window.approveEvent = async (id) => {
                    await updateDoc(doc(db, "events", id), {
                        approved: true
                    });
                };

                window.rejectEvent = async (id) => {
                    await deleteDoc(doc(db, "events", id));
                };
                loadPendingEvents();
            </script>
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
    <script type="module">
        import { db, storage } from './Firebase/firebase_conn.js';
        import { collection, query, where, getDocs, addDoc, serverTimestamp } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-firestore.js";
        import { ref, uploadBytes, getDownloadURL } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-storage.js";

        async function testFirestore() {
            try {
                const q = query(collection(db, "events"), where("createdBy", "==", "Admin"));
                const snapshot = await getDocs(q);
                console.log("Number of documents in 'events':", snapshot.size);
                snapshot.forEach(docSnap => {
                    console.log(docSnap.id, docSnap.data());
                });
            } catch (error) {
                console.error("Firestore error:", error);
            }
        }

        testFirestore();

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Quill Rich Text Editor
            const quill = new Quill('#richTextEditor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link', 'image'],
                        ['clean']
                    ]
                },
                placeholder: 'Write event description here...'
            });

            // Calendar functionality
            let currentDate = new Date();
            let events = JSON.parse(localStorage.getItem('calendarEvents')) || [];
            let eventToDelete = null;
            let eventToEdit = null;
            let selectedImages = [];
            
            // Philippine Time Clock Functionality
            function updatePhilippineTime() {
                const phDateElement = document.getElementById('phDate');
                const phTimeElement = document.getElementById('phTime');
                
                // Philippine Time is UTC+8
                const now = new Date();
                const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
                const phTime = new Date(utc + (3600000 * 8)); // UTC+8
                
                // Format date (e.g., "January 15, 2024")
                const optionsDate = { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                };
                const formattedDate = phTime.toLocaleDateString('en-US', optionsDate);
                
                // Format time (e.g., "14:30:45")
                const hours = phTime.getHours().toString().padStart(2, '0');
                const minutes = phTime.getMinutes().toString().padStart(2, '0');
                const seconds = phTime.getSeconds().toString().padStart(2, '0');
                const formattedTime = `${hours}:${minutes}:${seconds}`;
                
                phDateElement.textContent = formattedDate;
                phTimeElement.textContent = formattedTime;
            }

            // Upload images to Firebase
            async function uploadImages(files) {
                const urls = [];

                for (const file of files) {
                    const storageRef = ref(
                    storage,
                    `events/${Date.now()}_${file.name}`
                    );

                    await uploadBytes(storageRef, file);
                    const url = await getDownloadURL(storageRef);
                    urls.push(url);
                }

                return urls;
            }

            // Create data on Firebase DB
            document.getElementById("saveEvent").addEventListener("click", async () => {
                try {
                    // Basic fields
                    const title = document.getElementById("eventTitle").value.trim();
                    const category = document.getElementById("eventCategory").value;
                    const date = document.getElementById("eventDate").value;
                    const venue = document.getElementById("eventVenue").value.trim();
                    const description = quill.root.innerHTML;
                    const createdBy = "Admin";

                    // Time parsing
                    const startTime = document.getElementById("startTime").value;
                    const endTime = document.getElementById("endTime").value;

                    let startHour = null, startMinute = null;
                    let endHour = null, endMinute = null;

                    if (startTime) {
                    [startHour, startMinute] = startTime.split(":").map(Number);
                    }

                    if (endTime) {
                    [endHour, endMinute] = endTime.split(":").map(Number);
                    }

                    // Switches
                    const pub_to_cal = document.getElementById("publishEvent").checked;
                    const send_notif = document.getElementById("sendNotification").checked;

                    // Images
                    const imageFiles = document.getElementById("eventImages").files;
                    const imageUrl = imageFiles.length
                    ? await uploadImages(imageFiles)
                    : [];

                    // Firestore write
                    await addDoc(collection(db, "events"), {
                        title,
                        category,
                        date,
                        startHour,
                        startMinute,
                        endHour,
                        endMinute,
                        venue,
                        imageUrl,
                        description,
                        pub_to_cal,
                        send_notif,
                        createdBy,
                        createdAt: serverTimestamp()
                    });

                    alert("Event saved successfully!");
                    document.getElementById("eventForm").reset();

                } catch (error) {
                    console.error("Error saving event:", error);
                    alert("Failed to save event.");
                }
            });

            // Initialize and update clock every second
            updatePhilippineTime();
            setInterval(updatePhilippineTime, 1000);
            
            // Initialize calendar
            renderCalendar(currentDate);
            renderPublishedEvents();
            updateStatistics();
            
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
            
            // Calendar navigation
            document.getElementById('prevMonth').addEventListener('click', function() {
                currentDate.setMonth(currentDate.getMonth() - 1);
                renderCalendar(currentDate);
            });
            
            document.getElementById('nextMonth').addEventListener('click', function() {
                currentDate.setMonth(currentDate.getMonth() + 1);
                renderCalendar(currentDate);
            });
            
            // Handle image upload preview
            document.getElementById('eventImages').addEventListener('change', function(e) {
                const files = e.target.files;
                const previewContainer = document.getElementById('imagePreview');
                previewContainer.innerHTML = '';
                
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const imageData = e.target.result;
                            selectedImages.push(imageData);
                            
                            const previewDiv = document.createElement('div');
                            previewDiv.className = 'image-preview';
                            previewDiv.innerHTML = `
                                <img src="${imageData}" alt="Preview ${i + 1}">
                                <button type="button" class="remove-image" data-index="${selectedImages.length - 1}">
                                    <i class="fas fa-times"></i>
                                </button>
                            `;
                            previewContainer.appendChild(previewDiv);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });
            
            // Clear images button
            document.getElementById('clearImages').addEventListener('click', function() {
                document.getElementById('eventImages').value = '';
                document.getElementById('imagePreview').innerHTML = '';
                selectedImages = [];
            });
            
            // Remove image from preview
            document.getElementById('imagePreview').addEventListener('click', function(e) {
                if (e.target.closest('.remove-image')) {
                    const index = parseInt(e.target.closest('.remove-image').dataset.index);
                    selectedImages.splice(index, 1);
                    renderImagePreviews();
                }
            });
            
            // Save event as draft
            document.getElementById('saveDraft').addEventListener('click', function() {
                saveEvent(false);
            });
            
            // Save and publish event
            document.getElementById('saveEvent').addEventListener('click', function() {
                saveEvent(true);
            });
            
            // Delete event button
            document.getElementById('deleteEventBtn').addEventListener('click', function() {
                const eventDetailsModal = bootstrap.Modal.getInstance(document.getElementById('eventDetailsModal'));
                eventDetailsModal.hide();
                
                // Show delete confirmation modal
                const deleteConfirmModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
                deleteConfirmModal.show();
            });
            
            // Edit event button
            document.getElementById('editEventBtn').addEventListener('click', function() {
                const eventDetailsModal = bootstrap.Modal.getInstance(document.getElementById('eventDetailsModal'));
                eventDetailsModal.hide();
                
                if (eventToEdit) {
                    editEvent(eventToEdit);
                }
            });
            
            // Confirm delete button
            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                if (eventToDelete) {
                    // Remove event from array
                    events = events.filter(event => event.id !== eventToDelete.id);
                    
                    // Save to localStorage
                    localStorage.setItem('calendarEvents', JSON.stringify(events));
                    
                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal')).hide();
                    
                    // Re-render calendar and events
                    renderCalendar(currentDate);
                    renderPublishedEvents();
                    updateStatistics();
                    
                    // Reset eventToDelete
                    eventToDelete = null;
                }
            });
            
            // Set today's date as default in the form
            document.getElementById('eventDate').valueAsDate = new Date();
            
            // Calendar rendering function
            function renderCalendar(date) {
                const calendarGrid = document.getElementById('calendarGrid');
                const currentMonthYear = document.getElementById('currentMonthYear');
                
                // Clear existing calendar days (keep headers)
                while (calendarGrid.children.length > 7) {
                    calendarGrid.removeChild(calendarGrid.lastChild);
                }
                
                // Set month and year title
                const monthNames = ["January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ];
                currentMonthYear.textContent = `${monthNames[date.getMonth()]} ${date.getFullYear()}`;
                
                // Get first day of month and number of days
                const firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
                const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
                const daysInMonth = lastDay.getDate();
                const startingDay = firstDay.getDay();
                
                // Add days from previous month
                const prevMonthLastDay = new Date(date.getFullYear(), date.getMonth(), 0).getDate();
                for (let i = startingDay - 1; i >= 0; i--) {
                    const dayElement = createDayElement(prevMonthLastDay - i, true, date);
                    calendarGrid.appendChild(dayElement);
                }
                
                // Add days of current month
                const today = new Date();
                for (let i = 1; i <= daysInMonth; i++) {
                    const dayElement = createDayElement(i, false, date);
                    
                    // Check if this is today
                    if (date.getMonth() === today.getMonth() && 
                        date.getFullYear() === today.getFullYear() && 
                        i === today.getDate()) {
                        dayElement.classList.add('today');
                    }
                    
                    calendarGrid.appendChild(dayElement);
                }
                
                // Add days from next month to complete the grid
                const totalCells = 42; // 6 rows * 7 days
                const daysSoFar = startingDay + daysInMonth;
                const nextMonthDays = totalCells - daysSoFar;
                
                for (let i = 1; i <= nextMonthDays; i++) {
                    const dayElement = createDayElement(i, true, date);
                    calendarGrid.appendChild(dayElement);
                }
            }
            
            // Create a day element
            function createDayElement(dayNumber, isOtherMonth, currentDate) {
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day';
                
                if (isOtherMonth) {
                    dayElement.classList.add('other-month');
                }
                
                const dayNumberElement = document.createElement('div');
                dayNumberElement.className = 'day-number';
                dayNumberElement.textContent = dayNumber;
                dayElement.appendChild(dayNumberElement);
                
                // Add events for this day
                const year = currentDate.getFullYear();
                const month = currentDate.getMonth() + 1;
                const dateString = `${year}-${month.toString().padStart(2, '0')}-${dayNumber.toString().padStart(2, '0')}`;
                
                const dayEvents = events.filter(event => event.date === dateString && event.published);
                
                if (dayEvents.length > 0) {
                    dayElement.classList.add('has-events');
                    
                    dayEvents.forEach(event => {
                        const eventElement = document.createElement('div');
                        eventElement.className = `event-item ${event.category}`;
                        eventElement.textContent = event.title;
                        eventElement.setAttribute('data-bs-toggle', 'tooltip');
                        eventElement.setAttribute('title', `${event.title}${event.startTime ? ' - ' + event.startTime : ''}`);
                        eventElement.addEventListener('click', function(e) {
                            e.stopPropagation();
                            showEventDetails(event);
                        });
                        dayElement.appendChild(eventElement);
                    });
                }
                
                return dayElement;
            }
            
            // Render published events
            async function renderPublishedEvents() {
                const publishedEventsContainer = document.getElementById('publishedEvents');
                publishedEventsContainer.innerHTML = '';

                try {
                    // Query Firestore for events created by Admin
                    const q = query(collection(db, "events"), where("createdBy", "==", "Admin"));
                    const querySnapshot = await getDocs(q);

                    // Convert snapshot to array of event objects
                    const publishedEvents = [];
                    querySnapshot.forEach(doc => {
                        const data = doc.data();
                        publishedEvents.push({
                            id: doc.id,
                            title: data.title,
                            date: data.date,
                            startTime: data.startTime,
                            endTime: data.endTime,
                            description: data.description,
                            venue: data.venue,
                            category: data.category,
                            imageUrl: data.imageUrl || null,
                            published: data.published || false
                        });
                    });

                    // Sort by date (newest first)
                    publishedEvents.sort((a, b) => new Date(b.date) - new Date(a.date));

                    if (publishedEvents.length === 0) {
                        publishedEventsContainer.innerHTML = `
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-plus fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No published events yet</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#eventModal">
                                    <i class="fas fa-plus me-2"></i> Create First Event
                                </button>
                            </div>
                        `;
                        return;
                    }

                    // Render each event
                    publishedEvents.forEach(event => {
                        const eventElement = document.createElement('div');
                        eventElement.className = 'event-card';

                        const eventDate = new Date(event.date);
                        const formattedDate = eventDate.toLocaleDateString('en-US', { 
                            weekday: 'long', 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric' 
                        });

                        let timeInfo = '';
                        if (event.startTime && event.endTime) {
                            timeInfo = `${event.startTime} - ${event.endTime}`;
                        } else if (event.startTime) {
                            timeInfo = `${event.startTime}`;
                        }

                        const imageHtml = event.imageUrl ? 
                            `<img src="${event.imageUrl}" class="event-card-image" alt="${event.title}">` : 
                            `<div class="event-card-image d-flex align-items-center justify-content-center bg-light">
                                <i class="fas fa-calendar-alt fa-3x text-muted"></i>
                            </div>`;

                        eventElement.innerHTML = `
                            ${imageHtml}
                            <h4 class="event-card-title">${event.title}</h4>
                            <div class="event-card-meta">
                                <span class="event-meta-item">
                                    <i class="fas fa-calendar"></i> ${formattedDate}
                                </span>
                                ${timeInfo ? `<span class="event-meta-item">
                                    <i class="fas fa-clock"></i> ${timeInfo}
                                </span>` : ''}
                                ${event.venue ? `<span class="event-meta-item">
                                    <i class="fas fa-map-marker-alt"></i> ${event.venue}
                                </span>` : ''}
                            </div>
                            <div class="event-card-description">
                                ${event.description ? event.description.substring(0, 200) + '...' : 'No description'}
                            </div>
                            <div class="event-card-footer">
                                <div class="event-status">
                                    <span class="status-published">
                                        <i class="fas fa-check-circle"></i> Published
                                    </span>
                                    <span class="badge bg-${getCategoryColor(event.category)}">${event.category}</span>
                                </div>
                                <div class="event-actions">
                                    <button class="btn btn-sm btn-outline-primary view-event" data-id="${event.id}">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning edit-event" data-id="${event.id}">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                </div>
                            </div>
                        `;

                        publishedEventsContainer.appendChild(eventElement);
                    });

                    // Event listeners
                    document.querySelectorAll('.view-event').forEach(button => {
                        button.addEventListener('click', function() {
                            const eventId = this.dataset.id;
                            const event = publishedEvents.find(e => e.id === eventId);
                            if (event) showEventDetails(event);
                        });
                    });

                    document.querySelectorAll('.edit-event').forEach(button => {
                        button.addEventListener('click', function() {
                            const eventId = this.dataset.id;
                            const event = publishedEvents.find(e => e.id === eventId);
                            if (event) editEvent(event);
                        });
                    });

                } catch (error) {
                    console.error("Error fetching events:", error);
                    publishedEventsContainer.innerHTML = `<p class="text-danger">Failed to load events.</p>`;
                }
            }
            
            // Save event function
            function saveEvent(publish) {
                const title = document.getElementById('eventTitle').value;
                const date = document.getElementById('eventDate').value;
                const startTime = document.getElementById('startTime').value;
                const endTime = document.getElementById('endTime').value;
                const category = document.getElementById('eventCategory').value;
                const venue = document.getElementById('eventVenue').value;
                const description = quill.root.innerHTML;
                const sendNotification = document.getElementById('sendNotification').checked;
                
                if (title && date) {
                    const event = {
                        id: eventToEdit ? eventToEdit.id : Date.now(),
                        title,
                        date,
                        startTime,
                        endTime,
                        category,
                        venue,
                        description,
                        images: [...selectedImages],
                        published: publish,
                        createdAt: new Date().toISOString(),
                        updatedAt: new Date().toISOString()
                    };
                    
                    if (eventToEdit) {
                        // Update existing event
                        const index = events.findIndex(e => e.id === eventToEdit.id);
                        if (index !== -1) {
                            events[index] = event;
                        }
                    } else {
                        // Add new event
                        events.push(event);
                    }
                    
                    // Save to localStorage
                    localStorage.setItem('calendarEvents', JSON.stringify(events));
                    
                    // Reset form and close modal
                    resetForm();
                    bootstrap.Modal.getInstance(document.getElementById('eventModal')).hide();
                    
                    // Show success message
                    showNotification(`Event "${title}" ${publish ? 'published' : 'saved as draft'} successfully!`, 'success');
                    
                    // Send notification if enabled
                    if (publish && sendNotification) {
                        simulateNotification(event);
                    }
                    
                    // Re-render calendar and events
                    renderCalendar(currentDate);
                    renderPublishedEvents();
                    updateStatistics();
                    
                    // Reset editing state
                    eventToEdit = null;
                    selectedImages = [];
                }
            }
            
            // Edit event function
            function editEvent(event) {
                eventToEdit = event;
                
                // Populate form fields
                document.getElementById('eventTitle').value = event.title;
                document.getElementById('eventDate').value = event.date;
                document.getElementById('startTime').value = event.startTime || '';
                document.getElementById('endTime').value = event.endTime || '';
                document.getElementById('eventCategory').value = event.category;
                document.getElementById('eventVenue').value = event.venue || '';
                document.getElementById('publishEvent').checked = event.published || false;
                
                // Set rich text editor content
                quill.root.innerHTML = event.description || '';
                
                // Show images if any
                selectedImages = event.images || [];
                renderImagePreviews();
                
                // Change modal title
                document.getElementById('eventModalLabel').textContent = 'Edit Event';
                
                // Show modal
                const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
                eventModal.show();
            }
            
            // Show event details
            function showEventDetails(event) {
                eventToDelete = event;
                eventToEdit = event;
                
                const eventDate = new Date(event.date);
                const formattedDate = eventDate.toLocaleDateString('en-US', { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });
                
                let timeInfo = '';
                if (event.startTime && event.endTime) {
                    timeInfo = `<p><strong>Time:</strong> ${event.startTime} - ${event.endTime}</p>`;
                } else if (event.startTime) {
                    timeInfo = `<p><strong>Time:</strong> ${event.startTime}</p>`;
                }
                
                let venueInfo = event.venue ? `<p><strong>Venue:</strong> ${event.venue}</p>` : '';
                
                let imagesHtml = '';
                if (event.images && event.images.length > 0) {
                    imagesHtml = `
                        <div class="mb-3">
                            <strong>Event Images:</strong>
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                ${event.images.map((img, index) => `
                                    <img src="${img}" class="event-details-image" alt="Event Image ${index + 1}" style="max-width: 150px; max-height: 100px; object-fit: cover;">
                                `).join('')}
                            </div>
                        </div>
                    `;
                }
                
                const eventDetailsContent = document.getElementById('eventDetailsContent');
                eventDetailsContent.innerHTML = `
                    <div class="text-center mb-3">
                        <span class="badge bg-${getCategoryColor(event.category)} mb-2">${event.category}</span>
                        <h4>${event.title}</h4>
                    </div>
                    ${imagesHtml}
                    <div class="mb-3">
                        <p><strong>Date:</strong> ${formattedDate}</p>
                        ${timeInfo}
                        ${venueInfo}
                        ${event.description ? `<div class="mt-3">
                            <strong>Description:</strong>
                            <div class="border rounded p-3 mt-2">${event.description}</div>
                        </div>` : ''}
                    </div>
                    <div class="text-muted small">
                        <p><strong>Status:</strong> ${event.published ? 'Published' : 'Draft'}</p>
                        <p><strong>Created:</strong> ${new Date(event.createdAt).toLocaleDateString()}</p>
                        ${event.updatedAt ? `<p><strong>Last Updated:</strong> ${new Date(event.updatedAt).toLocaleDateString()}</p>` : ''}
                    </div>
                `;
                
                // Show the event details modal
                const eventDetailsModal = new bootstrap.Modal(document.getElementById('eventDetailsModal'));
                eventDetailsModal.show();
            }
            
            // Helper function to get category color
            function getCategoryColor(category) {
                switch(category) {
                    case 'meeting': return 'primary';
                    case 'deadline': return 'danger';
                    case 'event': return 'purple';
                    case 'training': return 'success';
                    case 'reminder': return 'warning';
                    default: return 'secondary';
                }
            }
            
            // Update statistics
            function updateStatistics() {
                const totalEvents = events.length;
                const publishedCount = events.filter(event => event.published).length;
                
                document.getElementById('totalEvents').textContent = totalEvents;
                document.getElementById('publishedCount').textContent = publishedCount;
            }
            
            // Render image previews
            function renderImagePreviews() {
                const previewContainer = document.getElementById('imagePreview');
                previewContainer.innerHTML = '';
                
                selectedImages.forEach((imageData, index) => {
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'image-preview';
                    previewDiv.innerHTML = `
                        <img src="${imageData}" alt="Preview ${index + 1}">
                        <button type="button" class="remove-image" data-index="${index}">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    previewContainer.appendChild(previewDiv);
                });
            }
            
            // Reset form
            function resetForm() {
                document.getElementById('eventForm').reset();
                quill.root.innerHTML = '';
                document.getElementById('imagePreview').innerHTML = '';
                document.getElementById('eventModalLabel').textContent = 'Create New Event';
                eventToEdit = null;
            }
            
            // Show notification
            function showNotification(message, type) {
                const alert = document.createElement('div');
                alert.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
                alert.style.cssText = 'top: 20px; right: 20px; z-index: 1060; min-width: 300px;';
                alert.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(alert);
                
                setTimeout(() => {
                    alert.remove();
                }, 3000);
            }
            
            // Simulate notification
            function simulateNotification(event) {
                console.log(`Notification sent for event: ${event.title}`);
                // In a real application, this would send emails or push notifications
            }
            
            // When modal is hidden, reset form
            document.getElementById('eventModal').addEventListener('hidden.bs.modal', function() {
                resetForm();
            });
        });
    </script>
</body>
</html>