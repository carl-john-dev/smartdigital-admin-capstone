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
        .event-details-modal .event-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
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
            <li><a href="invoice.php"><i class="fas fa-file-invoice"></i> <span>Invoices</span></a></li>
            <li><a href="calendar.php" class="active"><i class="fas fa-calendar"></i> <span>Calendar</span></a></li>
            <li><a href="location.php"><i class="fas fa-map-marked-alt"></i><span>Location</span></a></li>
            <li><a href="request.php"><i class="fas fa-clipboard-list"></i> <span>Requests</span></a></li>
            <li><a href="login.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <h1>Event Calendar</h1>
            <div class="user-info">
                <div class="user-avatar">AD</div>
                <div>
                    <div class="fw-bold">Admin User</div>
                    <small class="text-muted">Administrator</small>
                </div>
            </div>
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

        <!-- Upcoming Events Section -->
        <div class="row">
            <div class="col-lg-8">
                <div class="calendar-container">
                    <h3 class="section-title"><i class="fas fa-list"></i> Upcoming Events</h3>
                    <div id="upcomingEvents">
                        <!-- Upcoming events will be populated by JavaScript -->
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="calendar-container">
                    <h3 class="section-title"><i class="fas fa-plus-circle"></i> Add New Event</h3>
                    <button class="btn btn-primary w-100 mb-3" data-bs-toggle="modal" data-bs-target="#eventModal">
                        <i class="fas fa-plus me-2"></i> Create Event
                    </button>
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
        </div>
    </div>

    <!-- Event Modal -->
    <div class="modal fade event-modal" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Add New Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="eventForm" class="event-form">
                        <div class="mb-3">
                            <label for="eventTitle" class="form-label">Event Title</label>
                            <input type="text" class="form-control" id="eventTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="eventDate" class="form-label">Date</label>
                            <input type="date" class="form-control" id="eventDate" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="startTime" class="form-label">Start Time</label>
                                <input type="time" class="form-control" id="startTime">
                            </div>
                            <div class="col">
                                <label for="endTime" class="form-label">End Time</label>
                                <input type="time" class="form-control" id="endTime">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="eventCategory" class="form-label">Category</label>
                            <select class="form-select" id="eventCategory">
                                <option value="meeting">Meeting</option>
                                <option value="deadline">Deadline</option>
                                <option value="event">Event</option>
                                <option value="training">Training</option>
                                <option value="reminder">Reminder</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="eventDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="eventDescription" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveEvent">Save Event</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Details Modal -->
    <div class="modal fade event-details-modal" id="eventDetailsModal" tabindex="-1" aria-labelledby="eventDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
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
    
    <!-- JavaScript for Calendar -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Calendar functionality
            let currentDate = new Date();
            let events = JSON.parse(localStorage.getItem('calendarEvents')) || [];
            let eventToDelete = null;
            
            // Initialize calendar
            renderCalendar(currentDate);
            renderUpcomingEvents();
            
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
            
            // Save event
            document.getElementById('saveEvent').addEventListener('click', function() {
                const title = document.getElementById('eventTitle').value;
                const date = document.getElementById('eventDate').value;
                const startTime = document.getElementById('startTime').value;
                const endTime = document.getElementById('endTime').value;
                const category = document.getElementById('eventCategory').value;
                const description = document.getElementById('eventDescription').value;
                
                if (title && date) {
                    const event = {
                        id: Date.now(),
                        title,
                        date,
                        startTime,
                        endTime,
                        category,
                        description
                    };
                    
                    events.push(event);
                    localStorage.setItem('calendarEvents', JSON.stringify(events));
                    
                    // Reset form and close modal
                    document.getElementById('eventForm').reset();
                    bootstrap.Modal.getInstance(document.getElementById('eventModal')).hide();
                    
                    // Re-render calendar and upcoming events
                    renderCalendar(currentDate);
                    renderUpcomingEvents();
                }
            });
            
            // Delete event button
            document.getElementById('deleteEventBtn').addEventListener('click', function() {
                const eventDetailsModal = bootstrap.Modal.getInstance(document.getElementById('eventDetailsModal'));
                eventDetailsModal.hide();
                
                // Show delete confirmation modal
                const deleteConfirmModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
                deleteConfirmModal.show();
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
                    
                    // Re-render calendar and upcoming events
                    renderCalendar(currentDate);
                    renderUpcomingEvents();
                    
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
                
                const dayEvents = events.filter(event => event.date === dateString);
                
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
            
            // Render upcoming events
            function renderUpcomingEvents() {
                const upcomingEventsContainer = document.getElementById('upcomingEvents');
                upcomingEventsContainer.innerHTML = '';
                
                // Sort events by date
                const sortedEvents = events.sort((a, b) => new Date(a.date) - new Date(b.date));
                
                // Get today's date
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                // Filter upcoming events (today and future)
                const upcomingEvents = sortedEvents.filter(event => {
                    const eventDate = new Date(event.date);
                    eventDate.setHours(0, 0, 0, 0);
                    return eventDate >= today;
                }).slice(0, 10); // Show only next 10 events
                
                if (upcomingEvents.length === 0) {
                    upcomingEventsContainer.innerHTML = '<p class="text-muted">No upcoming events</p>';
                    return;
                }
                
                upcomingEvents.forEach(event => {
                    const eventElement = document.createElement('div');
                    eventElement.className = 'd-flex justify-content-between align-items-center p-3 border-bottom';
                    
                    const eventDate = new Date(event.date);
                    const formattedDate = eventDate.toLocaleDateString('en-US', { 
                        weekday: 'short', 
                        month: 'short', 
                        day: 'numeric' 
                    });
                    
                    eventElement.innerHTML = `
                        <div>
                            <h6 class="mb-1">${event.title}</h6>
                            <small class="text-muted">${formattedDate}${event.startTime ? ' • ' + event.startTime : ''}</small>
                        </div>
                        <span class="badge bg-${getCategoryColor(event.category)}">${event.category}</span>
                    `;
                    
                    eventElement.style.cursor = 'pointer';
                    eventElement.addEventListener('click', function() {
                        showEventDetails(event);
                    });
                    
                    upcomingEventsContainer.appendChild(eventElement);
                });
            }
            
            // Show event details
            function showEventDetails(event) {
                eventToDelete = event;
                
                const eventDate = new Date(event.date);
                const formattedDate = eventDate.toLocaleDateString('en-US', { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });
                
                let timeInfo = '';
                if (event.startTime && event.endTime) {
                    timeInfo = ` from ${event.startTime} to ${event.endTime}`;
                } else if (event.startTime) {
                    timeInfo = ` at ${event.startTime}`;
                }
                
                const eventDetailsContent = document.getElementById('eventDetailsContent');
                eventDetailsContent.innerHTML = `
                    <h6>${event.title}</h6>
                    <p><strong>Date:</strong> ${formattedDate}${timeInfo}</p>
                    <p><strong>Category:</strong> <span class="badge bg-${getCategoryColor(event.category)}">${event.category}</span></p>
                    <p><strong>Description:</strong> ${event.description || 'No description provided'}</p>
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
        });
    </script>
</body>
</html>