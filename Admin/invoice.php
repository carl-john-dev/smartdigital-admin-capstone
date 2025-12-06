<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoices - Cavite Business Owners Club</title>
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

        .date-filter {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .date-option {
            padding: 8px 16px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            background: var(--card-bg);
            color: var(--text-color);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .date-option.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .date-option:hover {
            transform: translateY(-2px);
        }

        /* Invoice Dashboard */
        .invoice-dashboard {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .invoice-filters {
            background: var(--card-bg);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-color);
        }

        .filter-section {
            margin-bottom: 25px;
        }

        .filter-title {
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--primary);
            font-size: 1.1rem;
        }

        .filter-options {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .filter-option {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 8px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .filter-option:hover {
            background: rgba(67, 97, 238, 0.05);
        }

        .filter-option input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
        }

        .filter-option label {
            cursor: pointer;
            flex: 1;
        }

        .count-badge {
            background: var(--primary);
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        /* Invoice List */
        .invoice-list-container {
            background: var(--card-bg);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-color);
        }

        .invoice-list-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .invoice-count {
            font-weight: 600;
            color: var(--primary);
        }

        .invoice-search {
            display: flex;
            gap: 10px;
        }

        .search-input {
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            background: var(--card-bg);
            color: var(--text-color);
            width: 200px;
        }

        .search-btn {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-btn:hover {
            background: var(--secondary);
            transform: scale(1.05);
        }

        /* Invoice Table */
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-table th {
            text-align: left;
            padding: 12px 15px;
            border-bottom: 2px solid var(--border-color);
            color: var(--primary);
            font-weight: 600;
        }

        .invoice-table td {
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.3s ease;
        }

        .invoice-table tr:hover td {
            background: rgba(67, 97, 238, 0.05);
        }

        .member-info {
            display: flex;
            align-items: center;
            gap: 12px;
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
            font-size: 0.9rem;
        }

        .member-details h5 {
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
        }

        .member-details p {
            margin: 0;
            font-size: 0.85rem;
            color: var(--gray);
        }

        .amount {
            font-weight: 600;
            color: var(--primary);
        }

        .status {
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            text-align: center;
            display: inline-block;
        }

        .status-paid {
            background: #d1edff;
            color: #004085;
        }

        .status-unpaid {
            background: #fff3cd;
            color: #856404;
        }

        .status-overdue {
            background: #f8d7da;
            color: #721c24;
        }

        .dark-mode .status-paid {
            background: #0d3c61;
            color: #7abfff;
        }

        .dark-mode .status-unpaid {
            background: #664d03;
            color: #ffda6a;
        }

        .dark-mode .status-overdue {
            background: #5c1a22;
            color: #f1aeb5;
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
            
            .invoice-dashboard {
                grid-template-columns: 1fr;
            }
            
            .invoice-list-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .invoice-search {
                width: 100%;
            }
            
            .search-input {
                flex: 1;
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
            <li><a href="index.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
            <li><a href="members.php"><i class="fas fa-users"></i> <span>Members</span></a></li>
            <li><a href="invoice.php" class="active"><i class="fas fa-file-invoice"></i> <span>Invoices</span></a></li>
            <li><a href="calendar.php"><i class="fas fa-calendar"></i> <span>Calendar</span></a></li>
            <li><a href="location.php"><i class="fas fa-map-marked-alt"></i> <span>Location</span></a></li>
            <li><a href="request.php"><i class="fas fa-clipboard-list"></i> <span>Requests</span></a></li>
            <li><a href="login.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="page-header">
                <div class="club-title">Cavite Business Owners Club</div>
                <h1 class="page-title">Invoices</h1>
                <div class="date-filter">
                    <div class="date-option">Day Month</div>
                    <div class="date-option active">2024</div>
                    <div class="date-option">2025</div>
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

        <div class="invoice-dashboard">
            <!-- Left Column - Filters -->
            <div class="invoice-filters">
                <div class="filter-section">
                    <div class="filter-title">Users</div>
                    <div class="filter-options">
                        <div class="filter-option">
                            <input type="checkbox" id="paid">
                            <label for="paid">Paid</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="unpaid" checked>
                            <label for="unpaid">Unpaid</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="overdue">
                            <label for="overdue">Overdue</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="balance">
                            <label for="balance">Balance</label>
                        </div>
                    </div>
                </div>

                <div class="filter-section">
                    <div class="filter-title">Analytics</div>
                    <div class="filter-options">
                        <div class="filter-option">
                            <input type="checkbox" id="calendar">
                            <label for="calendar">Calendar</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="request">
                            <label for="request">Request</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="map" checked>
                            <label for="map">Map</label>
                        </div>
                    </div>
                </div>

                <div class="filter-section">
                    <div class="filter-title">All(845)</div>
                    <div class="filter-options">
                        <div class="filter-option">
                            <input type="checkbox" id="email">
                            <label for="email">Email</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="membership-fee" checked>
                            <label for="membership-fee">Membership fee</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="events-fee">
                            <label for="events-fee">Events fee</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="food-control">
                            <label for="food-control">Food control funds</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Invoice List -->
            <div class="invoice-list-container">
                <div class="invoice-list-header">
                    <div class="invoice-count">Status</div>
                    <div class="invoice-search">
                        <input type="text" class="search-input" placeholder="Search invoices...">
                        <button class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <table class="invoice-table">
                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Email</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="member-info">
                                    <div class="member-avatar">LM</div>
                                    <div class="member-details">
                                        <h5>Lucia Merry</h5>
                                        <p>CEO, THE MIST COP.</p>
                                    </div>
                                </div>
                            </td>
                            <td>mistprod@gmail.com</td>
                            <td>08/27/2024</td>
                            <td class="amount">₱500</td>
                            <td><span class="status status-paid">PAID</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="member-info">
                                    <div class="member-avatar">LM</div>
                                    <div class="member-details">
                                        <h5>Lucia Merry</h5>
                                        <p>CEO, THE MIST COP.</p>
                                    </div>
                                </div>
                            </td>
                            <td>mistprod@gmail.com</td>
                            <td>08/27/2024</td>
                            <td class="amount">₱500</td>
                            <td><span class="status status-paid">PAID</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="member-info">
                                    <div class="member-avatar">LM</div>
                                    <div class="member-details">
                                        <h5>Lucia Merry</h5>
                                        <p>CEO, THE MIST COP.</p>
                                    </div>
                                </div>
                            </td>
                            <td>mistprod@gmail.com</td>
                            <td>08/27/2024</td>
                            <td class="amount">₱500</td>
                            <td><span class="status status-paid">PAID</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="member-info">
                                    <div class="member-avatar">LM</div>
                                    <div class="member-details">
                                        <h5>Lucia Merry</h5>
                                        <p>CEO, THE MIST COP.</p>
                                    </div>
                                </div>
                            </td>
                            <td>mistprod@gmail.com</td>
                            <td>08/27/2024</td>
                            <td class="amount">₱500</td>
                            <td><span class="status status-paid">PAID</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="member-info">
                                    <div class="member-avatar">LM</div>
                                    <div class="member-details">
                                        <h5>Lucia Merry</h5>
                                        <p>CEO, THE MIST COP.</p>
                                    </div>
                                </div>
                            </td>
                            <td>mistprod@gmail.com</td>
                            <td>08/27/2024</td>
                            <td class="amount">₱500</td>
                            <td><span class="status status-paid">PAID</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Dark Mode Toggle Button -->
    <button class="dark-mode-toggle" id="darkModeToggle">
        <i class="fas fa-moon" id="darkModeIcon"></i>
    </button>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript for Interactive Elements -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            // Date filter functionality
            const dateOptions = document.querySelectorAll('.date-option');
            dateOptions.forEach(option => {
                option.addEventListener('click', function() {
                    dateOptions.forEach(opt => opt.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Filter checkbox functionality
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    // In a real application, this would filter the invoice list
                    console.log(`Filter ${this.id} is now ${this.checked ? 'checked' : 'unchecked'}`);
                });
            });

            // Search functionality
            const searchInput = document.querySelector('.search-input');
            const searchBtn = document.querySelector('.search-btn');
            
            searchBtn.addEventListener('click', function() {
                const searchTerm = searchInput.value;
                if (searchTerm) {
                    // In a real application, this would filter the invoice list
                    console.log(`Searching for: ${searchTerm}`);
                    alert(`Searching for: ${searchTerm}`);
                }
            });

            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    searchBtn.click();
                }
            });
        });
    </script>
</body>
</html>