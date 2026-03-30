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
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
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
    
    <script type="module">
        import { db, storage } from './Firebase/firebase_conn.js';
        import { collection, query, where, doc, getDocs, addDoc, updateDoc, deleteDoc, serverTimestamp, Timestamp } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-firestore.js";
        
        // Field Configuration
        const fieldConfigs = {
            firstName: { label: 'First Name', enabled: true, required: true, validation: 'letters' },
            lastName: { label: 'Last Name', enabled: true, required: true, validation: 'letters' },
            email: { label: 'Email', enabled: true, required: true, validation: 'email' },
            phone: { label: 'Phone', enabled: true, required: false, validation: 'unique' },
            company: { label: 'Company', enabled: true, required: false, validation: 'none' }
        };
        
        // Global variables
        let allusers = [];
        let currentusersId = null;
        let currentAttendanceMember = null;
        
        // Calculate member status based on attendance count
        function calculateMemberStatus(attendanceCount) {
            if (attendanceCount >= 3) return 'Active';
            if (attendanceCount >= 1) return 'Pending';
            return 'Inactive';
        }
        
        // Load field config from localStorage
        function loadFieldConfig() {
            const saved = localStorage.getItem('addUserFieldConfig');
            if (saved) {
                const savedConfig = JSON.parse(saved);
                Object.keys(savedConfig).forEach(key => {
                    if (fieldConfigs[key]) fieldConfigs[key] = { ...fieldConfigs[key], ...savedConfig[key] };
                });
            }
            return fieldConfigs;
        }
        
        function saveFieldConfigToLocal() {
            localStorage.setItem('addUserFieldConfig', JSON.stringify(fieldConfigs));
            showToast('Configuration saved!', 'success');
        }
        
        function renderConfigModal() {
            const container = document.getElementById('configFieldsContainer');
            let html = '';
            for (const [key, config] of Object.entries(fieldConfigs)) {
                html += `
                    <div class="config-toggle">
                        <div><strong>${config.label}</strong> ${config.required ? '<span class="field-required-badge">Required</span>' : '<span class="field-optional-badge">Optional</span>'}</div>
                        <div class="d-flex align-items-center gap-3">
                            <label class="toggle-switch">
                                <input type="checkbox" class="config-enabled" data-field="${key}" ${config.enabled ? 'checked' : ''}>
                                <span class="toggle-slider"></span>
                            </label>
                            <label><input type="checkbox" class="config-required" data-field="${key}" ${config.required ? 'checked' : ''} ${!config.enabled ? 'disabled' : ''}> Required</label>
                        </div>
                    </div>
                `;
            }
            container.innerHTML = html;
            document.querySelectorAll('.config-enabled').forEach(cb => {
                cb.addEventListener('change', function() {
                    const field = this.dataset.field;
                    const requiredCb = document.querySelector(`.config-required[data-field="${field}"]`);
                    if (requiredCb) { requiredCb.disabled = !this.checked; if (!this.checked) requiredCb.checked = false; }
                });
            });
        }
        
        window.saveFieldConfig = function() {
            document.querySelectorAll('.config-enabled').forEach(cb => { if (fieldConfigs[cb.dataset.field]) fieldConfigs[cb.dataset.field].enabled = cb.checked; });
            document.querySelectorAll('.config-required').forEach(cb => { if (fieldConfigs[cb.dataset.field] && !cb.disabled) fieldConfigs[cb.dataset.field].required = cb.checked; });
            saveFieldConfigToLocal();
            renderDynamicForm();
            bootstrap.Modal.getInstance(document.getElementById('configModal')).hide();
            showToast('Form configuration updated!', 'success');
        };
        
        window.resetFieldConfig = function() {
            Object.assign(fieldConfigs, {
                firstName: { label: 'First Name', enabled: true, required: true, validation: 'letters' },
                lastName: { label: 'Last Name', enabled: true, required: true, validation: 'letters' },
                email: { label: 'Email', enabled: true, required: true, validation: 'email' },
                phone: { label: 'Phone', enabled: true, required: false, validation: 'unique' },
                company: { label: 'Company', enabled: true, required: false, validation: 'none' }
            });
            saveFieldConfigToLocal();
            renderConfigModal();
            renderDynamicForm();
            showToast('Configuration reset to default!', 'success');
        };
        
        function renderDynamicForm() {
            const container = document.getElementById('dynamicFormFields');
            let html = '';
            for (const [key, config] of Object.entries(fieldConfigs)) {
                if (!config.enabled) continue;
                const requiredMark = config.required ? '<span class="text-danger">*</span>' : '';
                if (key === 'firstName' || key === 'lastName') {
                    html += `<div class="col-md-6 mb-3"><label class="form-label">${config.label} ${requiredMark}</label><input type="text" class="form-control" id="${key}" ${config.required ? 'required' : ''}><div class="invalid-feedback">${config.label} must contain only letters</div></div>`;
                } else if (key === 'email') {
                    html += `<div class="col-md-6 mb-3"><label class="form-label">${config.label} ${requiredMark}</label><input type="email" class="form-control" id="${key}" ${config.required ? 'required' : ''}><div class="invalid-feedback">Email must be @gmail.com or @yahoo.com</div></div>`;
                } else if (key === 'phone') {
                    html += `<div class="col-md-6 mb-3"><label class="form-label">${config.label}</label><input type="tel" class="form-control" id="${key}"><div class="invalid-feedback">Phone number already exists</div></div>`;
                } else if (key === 'company') {
                    html += `<div class="col-md-6 mb-3"><label class="form-label">${config.label}</label><input type="text" class="form-control" id="${key}"></div>`;
                }
            }
            container.innerHTML = html;
        }
        
        // Validation functions
        function isOnlyLetters(str) { return /^[A-Za-z]+$/.test(str); }
        function isValidEmailDomain(email) { const allowedDomains = ['gmail.com', 'yahoo.com']; const domain = email.split('@')[1]; return domain && allowedDomains.includes(domain.toLowerCase()); }
        function isDuplicateName(firstName, lastName, excludeId = null) { const fullName = (firstName + " " + lastName).toLowerCase().trim(); return allusers.some(user => { const userFullName = (user.firstName + " " + user.lastName).toLowerCase().trim(); if (excludeId && user.id === excludeId) return false; return userFullName === fullName; }); }
        function isDuplicatePhone(phone, excludeId = null) { if (!phone || phone.trim() === '') return false; const phoneTrimmed = phone.trim(); return allusers.some(user => { if (excludeId && user.id === excludeId) return false; return user.phone && user.phone.trim() === phoneTrimmed; }); }
        
        // Load members from Firebase
        async function loadusers() {
            try {
                const querySnapshot = await getDocs(collection(db, "users"));
                allusers = [];
                for (const docSnap of querySnapshot.docs) {
                    const data = docSnap.data();
                    const fullName = data.name || "";
                    const nameParts = fullName.trim().split(" ");
                    const firstName = nameParts[0] || "???";
                    const lastName = nameParts.slice(1).join(" ") || "";
                    const attendance = data.attendance || [];
                    const attendanceCount = attendance.length;
                    const status = data.status || calculateMemberStatus(attendanceCount);
                    allusers.push({ id: docSnap.id, ...data, firstName, lastName, attendance, attendanceCount, status });
                }
                displayusers(allusers);
                updateStats();
                updateAttendanceTracker();
            } catch (error) { console.error("Error loading members:", error); showToast("Error loading members", "error"); }
        }
        
        function displayusers(users) {
            const container = document.getElementById('usersTableContainer');
            if (users.length === 0) { container.innerHTML = `<div class="empty-state"><i class="fas fa-users-slash"></i><h5>No Members Found</h5><p>Click "Add Member" to create your first member.</p></div>`; return; }
            let html = `<table class="table table-hover">
                <thead>
                <tr>
                <th>UID</th>
                <th>Member</th>
                <th>Contact</th>
                <th>Events Attended</th>
                <th>Member Status</th>
                <th>Actions</th>
                </tr>
                </thead>
                <tbody>`
            ;
            users.forEach(member => {
                const initials = (member.firstName ? member.firstName[0] : '') + (member.lastName ? member.lastName[0] : '');
                const joinDate = member.createdAt ? new Date(member.createdAt.toDate()).toLocaleDateString() : 'N/A';
                let statusClass = '', statusIcon = '';
                if (member.status === 'Active') { statusClass = 'member-active'; statusIcon = '<i class="fas fa-check-circle"></i>'; }
                else if (member.status === 'Pending') { statusClass = 'member-pending'; statusIcon = '<i class="fas fa-hourglass-half"></i>'; }
                else { statusClass = 'member-inactive'; statusIcon = '<i class="fas fa-user-slash"></i>'; }
                html += `<tr>
                    <td>
                        <small class="text-muted" onclick="navigator.clipboard.writeText('${member.id}')" style="cursor:pointer">
                            ${member.id.substring(0,8)}...
                        </small>
                    </td>
                    <td><div class="d-flex align-items-center"><div class="users-avatar" style="width:40px;height:40px;font-size:0.9rem;" onclick="viewusers('${member.id}')">${initials}</div><div class="ms-3"><div class="fw-bold">${escapeHtml(member.firstName)} ${escapeHtml(member.lastName)}</div><small class="text-muted">${escapeHtml(member.company || 'No Company')}</small></div></div></td>
                    <td><div>${escapeHtml(member.email)}</div><small class="text-muted">${escapeHtml(member.phone || 'No phone')}</small></td>
                    <td><span class="badge bg-primary">${member.attendanceCount || 0}</span> events<br><small class="text-muted">Joined: ${joinDate}</small></td>
                    <td><span class="member-status ${statusClass}">${statusIcon} ${member.status || 'Pending'}</span></td>
                    <td class="action-buttons">
                        <button class="btn btn-sm btn-outline-success me-1" onclick="recordAttendance('${member.id}')" title="Record Attendance"><i class="fas fa-calendar-check"></i></button>
                        <button class="btn btn-sm btn-outline-primary me-1" onclick="viewusers('${member.id}')"><i class="fas fa-eye"></i></button>
                        <button class="btn btn-sm btn-outline-warning me-1" onclick="editusers('${member.id}')"><i class="fas fa-edit"></i></button>
                        <!-- <button class="btn btn-sm btn-outline-danger" onclick="deleteusers('${member.id}')"><i class="fas fa-trash"></i></button> -->
                    </td>
                </tr>`;
            });
            html += '</tbody></table>';
            container.innerHTML = html;
        }
        
        function escapeHtml(str) { if (!str) return ''; return str.replace(/[&<>]/g, m => m === '&' ? '&amp;' : m === '<' ? '&lt;' : '&gt;'); }
        
        function updateStats() {
            const total = allusers.length;
            const active = allusers.filter(m => m.status === 'Active').length;
            const pending = allusers.filter(m => m.status === 'Pending').length;
            const inactive = allusers.filter(m => m.status === 'Inactive').length;
            document.getElementById('totalusers').textContent = total;
            document.getElementById('activeusers').textContent = active;
            document.getElementById('pendingusers').textContent = pending;
            document.getElementById('inactiveusers').textContent = inactive;
            if (total > 0) {
                const activePercent = Math.round((active / total) * 100);
                const pendingPercent = Math.round((pending / total) * 100);
                const inactivePercent = Math.round((inactive / total) * 100);
                document.getElementById('activePercent').textContent = activePercent + '%';
                document.getElementById('pendingPercent').textContent = pendingPercent + '%';
                document.getElementById('inactivePercent').textContent = inactivePercent + '%';
                document.getElementById('activeBar').style.width = activePercent + '%';
                document.getElementById('pendingBar').style.width = pendingPercent + '%';
                document.getElementById('inactiveBar').style.width = inactivePercent + '%';
            }
        }
        
        function updateAttendanceTracker() {
            const container = document.getElementById('attendanceTracker');
            const recentAttendees = [...allusers].filter(m => m.attendance && m.attendance.length > 0).sort((a, b) => (b.attendance?.length || 0) - (a.attendance?.length || 0)).slice(0, 5);
            if (recentAttendees.length === 0) {
                container.innerHTML = '<div class="text-center py-3 text-muted"><i class="fas fa-calendar-times"></i><br>No attendance records yet</div>';
                return;
            }
            let html = '<div class="attendance-list">';
            recentAttendees.forEach(member => {
                const lastEvent = member.attendance && member.attendance.length > 0 ? member.attendance[member.attendance.length - 1] : null;
                html += `<div class="member-card p-2 mb-2 border rounded">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><strong>${escapeHtml(member.firstName)} ${escapeHtml(member.lastName)}</strong><br><small class="text-muted">${member.attendanceCount} events attended</small></div>
                        <span class="member-status ${member.status === 'Active' ? 'member-active' : (member.status === 'Pending' ? 'member-pending' : 'member-inactive')}">${member.status}</span>
                    </div>
                    ${lastEvent ? `<div class="attendance-summary mt-1"><i class="fas fa-clock"></i> Last event: ${lastEvent.eventName || 'Event'} (${lastEvent.date || 'N/A'})</div>` : ''}
                </div>`;
            });
            html += '</div>';
            container.innerHTML = html;
        }
        
        // Record Attendance
        window.recordAttendance = async function(id) {
            const member = allusers.find(m => m.id === id);
            if (!member) return;
            currentAttendanceMember = member;
            document.getElementById('attendanceMemberInfo').innerHTML = `<h5>${escapeHtml(member.firstName)} ${escapeHtml(member.lastName)}</h5><p class="text-muted">Current events attended: <strong>${member.attendanceCount || 0}</strong></p>`;
            document.getElementById('eventName').value = '';
            document.getElementById('eventDate').value = new Date().toISOString().split('T')[0];
            let historyHtml = '';
            if (member.attendance && member.attendance.length > 0) {
                member.attendance.forEach((event, idx) => {
                    historyHtml += `<div class="attendance-badge"><i class="fas fa-calendar-alt"></i> ${event.eventName || 'Event'} - ${event.date || 'N/A'}</div>`;
                });
            } else {
                historyHtml = '<p class="text-muted">No attendance records yet</p>';
            }
            document.getElementById('historyList').innerHTML = historyHtml;
            new bootstrap.Modal(document.getElementById('attendanceModal')).show();
        };
        
        window.saveAttendance = async function() {
            if (!currentAttendanceMember) return;
            const eventName = document.getElementById('eventName').value.trim();
            const eventDate = document.getElementById('eventDate').value;
            if (!eventName) { showToast('Please enter event name', 'warning'); return; }
            const newAttendance = {
                eventName: eventName,
                date: eventDate,
                recordedAt: new Date().toISOString()
            };
            const currentAttendance = currentAttendanceMember.attendance || [];
            const updatedAttendance = [...currentAttendance, newAttendance];
            const newAttendanceCount = updatedAttendance.length;
            const newStatus = calculateMemberStatus(newAttendanceCount);
            try {
                const userRef = doc(db, "users", currentAttendanceMember.id);
                await updateDoc(userRef, {
                    attendance: updatedAttendance,
                    attendanceCount: newAttendanceCount,
                    status: newStatus,
                    lastAttendanceDate: eventDate,
                    updatedAt: serverTimestamp()
                });
                showToast(`Attendance recorded! Member status updated to ${newStatus}`, 'success');
                bootstrap.Modal.getInstance(document.getElementById('attendanceModal')).hide();
                loadusers();
            } catch (error) { console.error('Error recording attendance:', error); showToast('Error recording attendance', 'error'); }
        };
        
        // Add new member
        window.addusers = async function() {
            const userData = {};
            let isValid = true;
            for (const [key, config] of Object.entries(fieldConfigs)) {
                if (!config.enabled) continue;
                const input = document.getElementById(key);
                if (input) userData[key] = input.value.trim();
                if (config.required && (!userData[key] || userData[key] === '')) { showToast(`${config.label} is required!`, 'warning'); isValid = false; }
            }
            if (!isValid) return;
            if (fieldConfigs.firstName.enabled && !isOnlyLetters(userData.firstName)) { showToast('First name must contain only letters!', 'warning'); isValid = false; }
            if (fieldConfigs.lastName.enabled && !isOnlyLetters(userData.lastName)) { showToast('Last name must contain only letters!', 'warning'); isValid = false; }
            if (fieldConfigs.email.enabled && !isValidEmailDomain(userData.email)) { showToast('Email must be @gmail.com or @yahoo.com!', 'warning'); isValid = false; }
            if (fieldConfigs.firstName.enabled && fieldConfigs.lastName.enabled && isDuplicateName(userData.firstName, userData.lastName)) { showToast('A member with this name already exists!', 'warning'); isValid = false; }
            if (fieldConfigs.phone.enabled && userData.phone && isDuplicatePhone(userData.phone)) { showToast('This phone number is already registered!', 'warning'); isValid = false; }
            if (!isValid) return;
            const name = (userData.firstName || '') + ' ' + (userData.lastName || '');
            const memberData = {
                name: name.trim(),
                email: userData.email || null,
                phone: userData.phone || null,
                company: userData.company || null,
                role: 'Member',
                status: 'Inactive',
                attendance: [],
                attendanceCount: 0,
                createdAt: serverTimestamp(),
                approved: false
            };
            try {
                await addDoc(collection(db, "users"), memberData);
                showToast('Member added successfully!', 'success');
                bootstrap.Modal.getInstance(document.getElementById('addusersModal')).hide();
                document.getElementById('addusersForm').reset();
                loadusers();
            } catch (error) { console.error('Error adding member:', error); showToast('Error adding member', 'error'); }
        };
        
        // View, Edit, Delete functions
        window.viewusers = async function(id) {
            const member = allusers.find(m => m.id === id);
            if (!member) return;
            currentusersId = id;
            const initials = (member.firstName ? member.firstName[0] : '') + (member.lastName ? member.lastName[0] : '');
            const joinDate = member.createdAt ? new Date(member.createdAt.toDate()).toLocaleDateString() : 'N/A';
            let attendanceHtml = '<div class="attendance-list">';
            if (member.attendance && member.attendance.length > 0) {
                member.attendance.forEach(event => { attendanceHtml += `<div class="attendance-badge"><i class="fas fa-calendar-alt"></i> ${escapeHtml(event.eventName)} - ${event.date}</div>`; });
            } else { attendanceHtml += '<p class="text-muted">No attendance records</p>'; }
            attendanceHtml += '</div>';
            const content = `<div class="users-avatar mx-auto mb-3" style="width:80px;height:80px;font-size:1.5rem;">${initials}</div>
                <h5>${escapeHtml(member.firstName)} ${escapeHtml(member.lastName)}</h5>
                <span class="member-status ${member.status === 'Active' ? 'member-active' : (member.status === 'Pending' ? 'member-pending' : 'member-inactive')}">${member.status || 'Pending'}</span>
                <div class="mt-4 text-start">
                    <p><strong>Email:</strong> ${escapeHtml(member.email)}</p>
                    <p><strong>Phone:</strong> ${escapeHtml(member.phone || 'N/A')}</p>
                    <p><strong>Company:</strong> ${escapeHtml(member.company || 'N/A')}</p>
                    <p><strong>Join Date:</strong> ${joinDate}</p>
                    <p><strong>Events Attended:</strong> <span class="badge bg-primary">${member.attendanceCount || 0}</span></p>
                    <p><strong>Attendance History:</strong></p>
                    ${attendanceHtml}
                </div>`;
            document.getElementById('viewusersContent').innerHTML = content;
            new bootstrap.Modal(document.getElementById('viewusersModal')).show();
        };
        
        window.editusers = async function(id) {
            const member = allusers.find(m => m.id === id);
            if (!member) return;
            currentusersId = id;
            document.getElementById('editusersId').value = id;
            document.getElementById('editFirstName').value = member.firstName || '';
            document.getElementById('editLastName').value = member.lastName || '';
            document.getElementById('editEmail').value = member.email || '';
            document.getElementById('editPhone').value = member.phone || '';
            document.getElementById('editCompany').value = member.company || '';
            new bootstrap.Modal(document.getElementById('editusersModal')).show();
        };
        
        window.updateusers = async function() {
            const id = document.getElementById('editusersId').value;
            const firstName = document.getElementById('editFirstName').value.trim();
            const lastName = document.getElementById('editLastName').value.trim();
            const email = document.getElementById('editEmail').value.trim();
            const phone = document.getElementById('editPhone').value.trim();
            const company = document.getElementById('editCompany').value;
            let isValid = true;
            if (!isOnlyLetters(firstName)) { document.getElementById('editFirstName').classList.add('is-invalid'); isValid = false; }
            else document.getElementById('editFirstName').classList.remove('is-invalid');
            if (!isOnlyLetters(lastName)) { document.getElementById('editLastName').classList.add('is-invalid'); isValid = false; }
            else document.getElementById('editLastName').classList.remove('is-invalid');
            if (!isValidEmailDomain(email)) { document.getElementById('editEmail').classList.add('is-invalid'); isValid = false; }
            else document.getElementById('editEmail').classList.remove('is-invalid');
            if (isDuplicateName(firstName, lastName, id)) { showToast('A member with this name already exists!', 'warning'); isValid = false; }
            if (phone && isDuplicatePhone(phone, id)) { document.getElementById('editPhone').classList.add('is-invalid'); showToast('This phone number is already registered!', 'warning'); isValid = false; }
            if (!isValid) return;
            const name = firstName + " " + lastName;
            try {
                await updateDoc(doc(db, "users", id), { name, email, phone: phone || null, company, updatedAt: serverTimestamp() });
                showToast('Member updated successfully!', 'success');
                bootstrap.Modal.getInstance(document.getElementById('editusersModal')).hide();
                loadusers();
            } catch (error) { showToast('Error updating member', 'error'); }
        };
        
        window.deleteusers = async function(id) {
            const member = allusers.find(m => m.id === id);
            if (!member) return;
            currentusersId = id;
            document.getElementById('deleteusersContent').innerHTML = `<i class="fas fa-trash text-danger fa-3x mb-3"></i><p>Are you sure you want to delete <strong>${escapeHtml(member.firstName)} ${escapeHtml(member.lastName)}</strong>?</p><p class="text-danger"><small>This action cannot be undone. Attendance records will be lost.</small></p>`;
            new bootstrap.Modal(document.getElementById('deleteusersModal')).show();
        };
        
        document.getElementById('confirmDeleteBtn').addEventListener('click', async function() {
            if (!currentusersId) return;
            try { await deleteDoc(doc(db, "users", currentusersId)); showToast('Member deleted successfully!', 'success'); bootstrap.Modal.getInstance(document.getElementById('deleteusersModal')).hide(); loadusers(); } 
            catch (error) { showToast('Error deleting member', 'error'); }
        });
        
        window.editFromView = async function() { bootstrap.Modal.getInstance(document.getElementById('viewusersModal')).hide(); setTimeout(() => { editusers(currentusersId); }, 500); };
        window.filterusers = function() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            let statusFilter = document.getElementById('statusFilter').value;
            const filtered = allusers.filter(member => {
                const matchesSearch = (member.firstName?.toLowerCase() || '').includes(searchTerm) || (member.lastName?.toLowerCase() || '').includes(searchTerm) || (member.email?.toLowerCase() || '').includes(searchTerm) || (member.company?.toLowerCase() || '').includes(searchTerm);
                const matchesStatus = statusFilter === 'all' || member.status === statusFilter;
                return matchesSearch && matchesStatus;
            });
            displayusers(filtered);
        };
        
        function showToast(message, type) {
            const toastContainer = document.getElementById('toastContainer');
            const bgColor = type === 'success' ? 'bg-success' : type === 'warning' ? 'bg-warning' : 'bg-danger';
            const icon = type === 'success' ? 'fa-check-circle' : type === 'warning' ? 'fa-exclamation-triangle' : 'fa-times-circle';
            const toast = document.createElement('div');
            toast.className = `toast show align-items-center text-white ${bgColor} border-0`;
            toast.setAttribute('role', 'alert');
            toast.innerHTML = `<div class="d-flex"><div class="toast-body"><i class="fas ${icon} me-2"></i>${message}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>`;
            toastContainer.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }
        
        window.exportUsers = function() {
            if (allusers.length === 0) { showToast('No members to export', 'warning'); return; }
            const dataStr = JSON.stringify(allusers, null, 2);
            const link = document.createElement('a');
            link.setAttribute('href', 'data:application/json;charset=utf-8,' + encodeURIComponent(dataStr));
            link.setAttribute('download', 'cboc-members-export.json');
            link.click();
            showToast('Members exported successfully!', 'success');
        };
        
        window.printUsers = function() { window.print(); };
        window.showHelp = function() {
            alert(`Member Management Help:
- Member status is AUTOMATICALLY updated based on event attendance:
  • ACTIVE: Attended 3 or more events
  • PENDING: Attended 1-2 events  
  • INACTIVE: No event attendance
- Click the calendar button (📅) to record attendance for a member
- Each attendance record includes event name and date
- Status updates in real-time as attendance is recorded`);
        };
        window.refreshusers = async function() { loadusers(); };
        
        // Initialize
        loadFieldConfig();
        document.addEventListener('DOMContentLoaded', function() {
            const darkModeToggle = document.getElementById('darkModeToggle');
            const darkModeIcon = document.getElementById('darkModeIcon');
            const body = document.body;
            const isDarkMode = localStorage.getItem('darkMode') === 'enabled';
            if (isDarkMode) { body.classList.add('dark-mode'); darkModeIcon.classList.remove('fa-moon'); darkModeIcon.classList.add('fa-sun'); }
            darkModeToggle.addEventListener('click', function() {
                body.classList.toggle('dark-mode');
                if (body.classList.contains('dark-mode')) { darkModeIcon.classList.remove('fa-moon'); darkModeIcon.classList.add('fa-sun'); localStorage.setItem('darkMode', 'enabled'); }
                else { darkModeIcon.classList.remove('fa-sun'); darkModeIcon.classList.add('fa-moon'); localStorage.setItem('darkMode', 'disabled'); }
            });
            const dotsMenuBtn = document.getElementById('dotsMenuBtn');
            const dotsDropdown = document.getElementById('dotsDropdown');
            dotsMenuBtn.addEventListener('click', function(e) { e.stopPropagation(); dotsDropdown.classList.toggle('show'); });
            document.addEventListener('click', function() { dotsDropdown.classList.remove('show'); });
            document.getElementById('configModal').addEventListener('show.bs.modal', renderConfigModal);
            document.getElementById('searchInput').addEventListener('input', filterusers);
            loadusers();
            renderDynamicForm();
        });
    </script>
</body>
</html>