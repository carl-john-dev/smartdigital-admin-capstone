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
    <title>Member Management - CBOC</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="icon" href="mems.png"/>
    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-firestore-compat.js"></script>
    <style>
        /* Additional styles for CRUD operations */
        .loading-spinner {
            text-align: center;
            padding: 50px;
        }
        
        .loading-spinner i {
            font-size: 3rem;
            color: var(--primary);
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .users-avatar {
            cursor: pointer;
            transition: transform 0.3s;
        }
        
        .users-avatar:hover {
            transform: scale(1.1);
        }
        
        .action-buttons {
            white-space: nowrap;
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
        
        .empty-state {
            text-align: center;
            padding: 50px;
            color: var(--gray);
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
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

        /* Validation error styles */
        .invalid-feedback {
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }
        
        .form-control.is-invalid {
            border-color: #dc3545;
        }
        
        .form-control.is-valid {
            border-color: #198754;
        }
        
        /* Configuration Panel Styles */
        .config-panel {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid var(--border-color);
        }
        
        .config-panel h4 {
            font-size: 1.1rem;
            margin-bottom: 15px;
            color: var(--primary);
        }
        
        .config-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        .config-toggle:last-child {
            border-bottom: none;
        }
        
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }
        
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.3s;
            border-radius: 24px;
        }
        
        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }
        
        input:checked + .toggle-slider {
            background-color: var(--primary);
        }
        
        input:checked + .toggle-slider:before {
            transform: translateX(26px);
        }
        
        .config-badge {
            background: var(--primary);
            color: white;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 0.7rem;
            margin-left: 10px;
        }
        
        .config-save-btn {
            margin-top: 15px;
            width: 100%;
        }
        
        .field-required-badge {
            background: #dc3545;
            color: white;
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 10px;
            margin-left: 8px;
        }
        
        .field-optional-badge {
            background: #6c757d;
            color: white;
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 10px;
            margin-left: 8px;
        }
        
        /* Member Status Styles */
        .member-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .member-active {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .member-inactive {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .member-pending {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        
        .attendance-badge {
            background: #e9ecef;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            margin-right: 5px;
        }
        
        .attendance-list {
            max-height: 200px;
            overflow-y: auto;
        }
        
        .status-update-btn {
            background: none;
            border: none;
            color: var(--primary);
            cursor: pointer;
            font-size: 0.8rem;
        }
        
        .status-update-btn:hover {
            text-decoration: underline;
        }
        
        .attendance-summary {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 5px;
        }
        
        .member-card {
            transition: all 0.3s ease;
        }
        
        .member-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
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
            <li><a href="dashboard.php"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
            <li><a href="#" class="active"><i class="fas fa-users"></i><span>Members</span></a></li>
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
    <div class="main-content fade-in">
        <!-- Top Bar with Three Dots Menu -->
        <div class="top-bar">
            <h1>Member Management</h1>
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
                        <button class="dropdown-item" onclick="exportUsers()">
                            <i class="fas fa-download"></i> Export Members
                        </button>
                        <button class="dropdown-item" onclick="printUsers()">
                            <i class="fas fa-print"></i> Print List
                        </button>
                        <div class="dropdown-divider"></div>
                        <button class="dropdown-item" onclick="refreshusers()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                        <button class="dropdown-item" onclick="showHelp()">
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

        <!-- Stats Section with Member Status -->
        <div class="stats-container stagger-animation" id="statsContainer">
            <div class="stat-card">
                <div class="stat-number" id="totalusers">0</div>
                <div class="stat-label">Total Members</div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #d4edda, #c3e6cb);">
                <div class="stat-number" id="activeusers">0</div>
                <div class="stat-label">Active Members</div>
                <small>≥ 3 events attended</small>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #fff3cd, #ffeeba);">
                <div class="stat-number" id="pendingusers">0</div>
                <div class="stat-label">Pending</div>
                <small>1-2 events attended</small>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #f8d7da, #f5c6cb);">
                <div class="stat-number" id="inactiveusers">0</div>
                <div class="stat-label">Inactive</div>
                <small>0 events attended</small>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="dashboard-section">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addusersModal">
                        <i class="fas fa-user-plus me-1"></i> Add Member
                    </button>
                    <button class="btn btn-outline-secondary me-2" onclick="refreshusers()">
                        <i class="fas fa-sync-alt me-1"></i> Refresh
                    </button>
                    <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#configModal">
                        <i class="fas fa-sliders-h me-1"></i> Configure Fields
                    </button>
                </div>
                <div class="d-flex gap-2">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search Members..." style="width: 250px;">
                    <select class="form-select" id="statusFilter" style="width: 150px;" onchange="filterusers()">
                        <option value="all">All Status</option>
                        <option value="Active">Active (≥3 events)</option>
                        <option value="Pending">Pending (1-2 events)</option>
                        <option value="Inactive">Inactive (0 events)</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Members Table Section -->
                <div class="dashboard-section">
                    <h3 class="section-title"><i class="fas fa-users"></i> Member Directory</h3>
                    <div id="usersTableContainer">
                        <div class="loading-spinner">
                            <i class="fas fa-circle-notch"></i>
                            <p class="mt-2">Loading Members...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Attendance Tracking Section -->
                <div class="dashboard-section">
                    <h3 class="section-title"><i class="fas fa-calendar-check"></i> Attendance Tracking</h3>
                    <div id="attendanceTracker">
                        <div class="text-center py-3">
                            <i class="fas fa-circle-notch fa-spin"></i> Loading...
                        </div>
                    </div>
                </div>
                
                <!-- Quick Stats -->
                <div class="dashboard-section mt-3">
                    <h3 class="section-title"><i class="fas fa-chart-pie"></i> Member Distribution</h3>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-check-circle text-success"></i> Active (≥3 events)</span>
                            <span class="badge bg-success" id="activePercent">0%</span>
                        </div>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-success" id="activeBar" style="width: 0%"></div>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-hourglass-half text-warning"></i> Pending (1-2 events)</span>
                            <span class="badge bg-warning" id="pendingPercent">0%</span>
                        </div>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-warning" id="pendingBar" style="width: 0%"></div>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-user-slash text-danger"></i> Inactive (0 events)</span>
                            <span class="badge bg-danger" id="inactivePercent">0%</span>
                        </div>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-danger" id="inactiveBar" style="width: 0%"></div>
                        </div>
                    </div>
                    
                    <!-- Status Criteria Info -->
                    <div class="alert alert-info mt-2">
                        <i class="fas fa-info-circle"></i>
                        <strong>Member Status Rules:</strong><br>
                        • <strong>Active:</strong> Attended 3 or more events<br>
                        • <strong>Pending:</strong> Attended 1-2 events<br>
                        • <strong>Inactive:</strong> No event attendance
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Configuration Modal -->
    <div class="modal fade" id="configModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-sliders-h me-2"></i>Configure Add Member Form Fields</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">Customize which fields appear in the Add Member form and set required status.</p>
                    <div id="configFieldsContainer"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveFieldConfig()">Save Configuration</button>
                    <button type="button" class="btn btn-outline-danger" onclick="resetFieldConfig()">Reset to Default</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Member Modal -->
    <div class="modal fade" id="addusersModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Add New Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addusersForm">
                        <div class="row" id="dynamicFormFields"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addusers()">Add Member</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Record Attendance Modal -->
    <div class="modal fade" id="attendanceModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-calendar-check me-2"></i>Record Attendance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="attendanceMemberInfo"></div>
                    <div class="mb-3">
                        <label class="form-label">Event Name</label>
                        <input type="text" class="form-control" id="eventName" placeholder="e.g., Monthly Meeting, Workshop, etc.">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Event Date</label>
                        <input type="date" class="form-control" id="eventDate">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="markAttendance" checked>
                        <label class="form-check-label">
                            Mark as Attended
                        </label>
                    </div>
                    <div id="attendanceHistory" class="mt-3">
                        <h6>Attendance History:</h6>
                        <div id="historyList" class="attendance-list"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveAttendance()">Record Attendance</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Member Modal -->
    <div class="modal fade" id="editusersModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editusersForm">
                        <input type="hidden" id="editusersId">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" id="editFirstName" required>
                                <div class="invalid-feedback">First name must contain only letters</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="editLastName" required>
                                <div class="invalid-feedback">Last name must contain only letters</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="editEmail" required>
                                <div class="invalid-feedback">Email must be @gmail.com or @yahoo.com</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="editPhone">
                                <div class="invalid-feedback">Phone number already exists</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company</label>
                                <input type="text" class="form-control" id="editCompany">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateusers()">Update Member</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Member Modal -->
    <div class="modal fade" id="viewusersModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user me-2"></i>Member Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center" id="viewusersContent"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="editFromView()">Edit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteusersModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center" id="deleteusersContent"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete Member</button>
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
    
    <script type="module" src="backend/members.js"></script>
    <script type="module" src="backend/backend.js"></script>
</body>
</html>