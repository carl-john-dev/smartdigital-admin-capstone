<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Homepage</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="icon" href="CBOC LOGO.jpg"/>
  
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-tachometer-alt"></i> CBOC</h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="admin_profile.php"><i class="fas fa-id-card"></i> <span>Profile</span></a></li>
            <li><a href="#" class="active"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
            <li><a href="signup.php"><i class="fas fa-user-plus"></i> <span>Create Account</span></a></li>
            <li><a href="members.php"><i class="fas fa-users"></i> <span>Members</span></a></li>
            <li><a href="calendar.php"><i class="fas fa-calendar"></i> <span>Calendar</span></a></li>
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
            <h1>Dashboard</h1>
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
                <div class="stat-number">800+</div>
                <div class="stat-label">Members</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">12</div>
                <div class="stat-label">Invoices</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">5</div>
                <div class="stat-label">Calendar</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">45</div>
                <div class="stat-label">Request</div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Calendar Section -->
                <div class="dashboard-section">
                    <h3 class="section-title"><i class="fas fa-calendar-alt"></i> Calendar</h3>
                    <ul class="calendar-list">
                        <li><strong>Request</strong> - Team meeting at 10:00 AM</li>
                        <li><strong>Request</strong> - Project deadline at 3:00 PM</li>
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
                        <tbody>
                            <tr>
                                <td>Jonatan</td>
                                <td>Membership</td>
                                <td><span class="status status-pending">Pending</span></td>
                            </tr>
                            <tr>
                                <td>Halley</td>
                                <td>Request</td>
                                <td><span class="status status-resolve">resolve</span></td>
                            </tr>
                            <tr>
                                <td>Nari</td>
                                <td>Membership</td>
                                <td><span class="status status-resolve">resolve</span></td>
                            </tr>
                            <tr>
                                <td>Austin</td>
                                <td>Membership</td>
                                <td><span class="status status-resolve">resolve</span></td>
                            </tr>
                            <tr>
                                <td>Isabelle</td>
                                <td>Request</td>
                                <td><span class="status status-resolve">resolve</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- New Members Section -->
                <div class="dashboard-section">
                    <h3 class="section-title"><i class="fas fa-user-plus"></i> New Members</h3>
                    
                    <!-- Member Cards -->
                    <div class="member-card">
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
                    </div>
                    
                    <div class="see-all">
                        <a href="#">See all <i class="fas fa-arrow-right"></i></a>
                    </div>
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


            // Add subtle animation to stats cards on page load
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('animate__animated', 'animate__fadeInUp');
            });
        });
    </script>
</body>
</html>