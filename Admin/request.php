<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requests - Cavite Business Owners Club</title>
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

        /* Request Container */
        .request-container {
            background: var(--card-bg);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            border: 1px solid var(--border-color);
        }

        .request-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .request-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary);
        }

        .request-actions {
            display: flex;
            gap: 10px;
        }

        .request-btn {
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

        .request-btn:hover {
            background: var(--secondary);
            transform: scale(1.05);
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

        /* Request Table */
        .request-table-container {
            overflow-x: auto;
        }

        .request-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .request-table th, .request-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.3s ease;
        }

        .request-table th {
            background-color: rgba(0,0,0,0.03);
            font-weight: 600;
            color: var(--primary);
        }

        .dark-mode .request-table th {
            background-color: rgba(255,255,255,0.05);
        }

        .request-table tbody tr:hover {
            background-color: rgba(0,0,0,0.03);
        }

        .dark-mode .request-table tbody tr:hover {
            background-color: rgba(255,255,255,0.05);
        }

        .request-status {
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            text-align: center;
            display: inline-block;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-approved {
            background: #d1edff;
            color: #004085;
        }

        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .status-completed {
            background: #d1f7e4;
            color: #0f5132;
        }

        .dark-mode .status-pending {
            background: #664d03;
            color: #ffda6a;
        }

        .dark-mode .status-approved {
            background: #0d3c61;
            color: #7abfff;
        }

        .dark-mode .status-rejected {
            background: #5c1a22;
            color: #f1aeb5;
        }

        .dark-mode .status-completed {
            background: #1a3d2f;
            color: #75b798;
        }

        .request-action-btn {
            background: none;
            border: none;
            color: var(--primary);
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 5px;
            border-radius: 3px;
            margin-right: 5px;
        }

        .request-action-btn:hover {
            background: rgba(67, 97, 238, 0.1);
            transform: scale(1.1);
        }

        .request-action-btn.delete {
            color: #dc3545;
        }

        .request-action-btn.delete:hover {
            background: rgba(220, 53, 69, 0.1);
        }

        /* Modal Styles */
        .request-modal .modal-content {
            background-color: var(--card-bg);
            color: var(--text-color);
            border: 1px solid var(--border-color);
        }

        .request-modal .modal-header {
            border-bottom: 1px solid var(--border-color);
        }

        .request-modal .modal-footer {
            border-top: 1px solid var(--border-color);
        }

        .request-form label {
            color: var(--text-color);
        }

        .request-form .form-control, .request-form .form-select {
            background-color: var(--card-bg);
            color: var(--text-color);
            border: 1px solid var(--border-color);
        }

        .request-form .form-control:focus, .request-form .form-select:focus {
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
            
            .request-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .request-actions {
                width: 100%;
                justify-content: space-between;
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
            <li><a href="invoice.php"><i class="fas fa-file-invoice"></i> <span>Invoices</span></a></li>
            <li><a href="calendar.php"><i class="fas fa-calendar"></i> <span>Calendar</span></a></li>
            <li><a href="location.php"><i class="fas fa-map-marked-alt"></i> <span>Maps</span></a></li>
            <li><a href="request.php" class="active"><i class="fas fa-clipboard-list"></i> <span>Requests</span></a></li>
            <li><a href="login.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="page-header">
                <div class="club-title">Cavite Business Owners Club</div>
                <h1 class="page-title">Request Management</h1>
            </div>
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
                <div class="stat-number">45</div>
                <div class="stat-label">Total Requests</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">12</div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">28</div>
                <div class="stat-label">Approved</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">5</div>
                <div class="stat-label">Rejected</div>
            </div>
        </div>

        <!-- Request Management Section -->
        <div class="request-container">
            <div class="request-header">
                <h2 class="request-title"><i class="fas fa-clipboard-list"></i> All Requests</h2>
                <div class="request-actions">
                    <button class="request-btn" id="filterRequests">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <button class="request-btn" id="newRequestBtn" data-bs-toggle="modal" data-bs-target="#requestModal">
                        <i class="fas fa-plus"></i> New Request
                    </button>
                </div>
            </div>

            <div class="request-table-container">
                <table class="request-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Request Type</th>
                            <th>Email</th>
                            <th>Operation</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="requestsTableBody">
                        <!-- Requests will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Request Modal -->
    <div class="modal fade request-modal" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestModalLabel">Add New Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="requestForm" class="request-form">
                        <input type="hidden" id="requestId">
                        <div class="mb-3">
                            <label for="requestName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="requestName" required>
                        </div>
                        <div class="mb-3">
                            <label for="requestType" class="form-label">Request Type</label>
                            <select class="form-select" id="requestType" required>
                                <option value="">Select Request Type</option>
                                <option value="Membership Application">Membership Application</option>
                                <option value="Event Registration">Event Registration</option>
                                <option value="Payment Issue">Payment Issue</option>
                                <option value="Account Update">Account Update</option>
                                <option value="General Inquiry">General Inquiry</option>
                                <option value="Technical Support">Technical Support</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="requestEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="requestEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="requestOperation" class="form-label">Operation</label>
                            <select class="form-select" id="requestOperation" required>
                                <option value="">Select Operation</option>
                                <option value="Create">Create</option>
                                <option value="Update">Update</option>
                                <option value="Delete">Delete</option>
                                <option value="Review">Review</option>
                                <option value="Process">Process</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="requestStatus" class="form-label">Status</label>
                            <select class="form-select" id="requestStatus" required>
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="requestDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="requestDescription" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveRequest">Save Request</button>
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
    
    <!-- JavaScript for Request Management -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize requests data
            let requests = JSON.parse(localStorage.getItem('cbocRequests')) || [
                {
                    id: 1,
                    name: 'Lucia Merry',
                    type: 'Membership Application',
                    email: 'mistprod@gmail.com',
                    operation: 'Create',
                    status: 'Pending',
                    description: 'New membership application for THE MIST COP.'
                },
                {
                    id: 2,
                    name: 'Sabrina Tan',
                    type: 'Event Registration',
                    email: 'sabrina@realtyvale.com',
                    operation: 'Process',
                    status: 'Approved',
                    description: 'Registration for annual business conference.'
                },
                {
                    id: 3,
                    name: 'Andy Sewer',
                    type: 'Payment Issue',
                    email: 'andy@fawcettor.com',
                    operation: 'Review',
                    status: 'Pending',
                    description: 'Invoice payment discrepancy inquiry.'
                },
                {
                    id: 4,
                    name: 'Shanon Matilda',
                    type: 'Account Update',
                    email: 'shanon@goldenfruit.com',
                    operation: 'Update',
                    status: 'Completed',
                    description: 'Update company information and contact details.'
                },
                {
                    id: 5,
                    name: 'Ethan Cravejal',
                    type: 'Technical Support',
                    email: 'ethan@newcastle.com',
                    operation: 'Review',
                    status: 'Rejected',
                    description: 'Website access issues and password reset.'
                }
            ];

            // Render requests table
            function renderRequests() {
                const tableBody = document.getElementById('requestsTableBody');
                tableBody.innerHTML = '';

                requests.forEach(request => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${request.name}</td>
                        <td>${request.type}</td>
                        <td>${request.email}</td>
                        <td>${request.operation}</td>
                        <td><span class="request-status status-${request.status.toLowerCase()}">${request.status}</span></td>
                        <td>
                            <button class="request-action-btn edit" data-id="${request.id}" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="request-action-btn delete" data-id="${request.id}" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });

                // Add event listeners to action buttons
                document.querySelectorAll('.request-action-btn.edit').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const requestId = parseInt(this.getAttribute('data-id'));
                        editRequest(requestId);
                    });
                });

                document.querySelectorAll('.request-action-btn.delete').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const requestId = parseInt(this.getAttribute('data-id'));
                        deleteRequest(requestId);
                    });
                });

                // Update stats
                updateStats();
            }

            // Update statistics
            function updateStats() {
                const total = requests.length;
                const pending = requests.filter(r => r.status === 'Pending').length;
                const approved = requests.filter(r => r.status === 'Approved').length;
                const rejected = requests.filter(r => r.status === 'Rejected').length;

                document.querySelectorAll('.stat-number')[0].textContent = total;
                document.querySelectorAll('.stat-number')[1].textContent = pending;
                document.querySelectorAll('.stat-number')[2].textContent = approved;
                document.querySelectorAll('.stat-number')[3].textContent = rejected;
            }

            // Edit request
            function editRequest(id) {
                const request = requests.find(r => r.id === id);
                if (request) {
                    document.getElementById('requestId').value = request.id;
                    document.getElementById('requestName').value = request.name;
                    document.getElementById('requestType').value = request.type;
                    document.getElementById('requestEmail').value = request.email;
                    document.getElementById('requestOperation').value = request.operation;
                    document.getElementById('requestStatus').value = request.status;
                    document.getElementById('requestDescription').value = request.description || '';

                    document.getElementById('requestModalLabel').textContent = 'Edit Request';
                    new bootstrap.Modal(document.getElementById('requestModal')).show();
                }
            }

            // Delete request
            function deleteRequest(id) {
                if (confirm('Are you sure you want to delete this request?')) {
                    requests = requests.filter(r => r.id !== id);
                    localStorage.setItem('cbocRequests', JSON.stringify(requests));
                    renderRequests();
                }
            }

            // Save request (Create/Update)
            document.getElementById('saveRequest').addEventListener('click', function() {
                const id = document.getElementById('requestId').value;
                const name = document.getElementById('requestName').value;
                const type = document.getElementById('requestType').value;
                const email = document.getElementById('requestEmail').value;
                const operation = document.getElementById('requestOperation').value;
                const status = document.getElementById('requestStatus').value;
                const description = document.getElementById('requestDescription').value;

                if (name && type && email && operation && status) {
                    if (id) {
                        // Update existing request
                        const index = requests.findIndex(r => r.id === parseInt(id));
                        if (index !== -1) {
                            requests[index] = {
                                ...requests[index],
                                name,
                                type,
                                email,
                                operation,
                                status,
                                description
                            };
                        }
                    } else {
                        // Create new request
                        const newId = requests.length > 0 ? Math.max(...requests.map(r => r.id)) + 1 : 1;
                        requests.push({
                            id: newId,
                            name,
                            type,
                            email,
                            operation,
                            status,
                            description
                        });
                    }

                    localStorage.setItem('cbocRequests', JSON.stringify(requests));
                    
                    // Reset form and close modal
                    document.getElementById('requestForm').reset();
                    bootstrap.Modal.getInstance(document.getElementById('requestModal')).hide();
                    
                    // Re-render requests
                    renderRequests();
                } else {
                    alert('Please fill in all required fields.');
                }
            });

            // New Request button
            document.getElementById('newRequestBtn').addEventListener('click', function() {
                document.getElementById('requestForm').reset();
                document.getElementById('requestId').value = '';
                document.getElementById('requestModalLabel').textContent = 'Add New Request';
            });

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

            // Filter functionality
            document.getElementById('filterRequests').addEventListener('click', function() {
                alert('Filter functionality would be implemented here!');
                // In a real application, this would open a filter modal or sidebar
            });

            // Initialize the page
            renderRequests();

            // Add subtle animation to stats cards on page load
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>
</html> 