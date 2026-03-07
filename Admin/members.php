<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>users Management</title>
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
    </style>
</head>
<body>
    <!-- Toast Notification Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-tachometer-alt"></i> Dashboard</h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
            <li><a href="#" class="active"><i class="fas fa-users"></i> <span>users</span></a></li>
            <li><a href="calendar.php"><i class="fas fa-calendar"></i> <span>Calendar</span></a></li>
            <li><a href="location.php"><i class="fas fa-map-marked-alt"></i><span>Location</span></a></li>
            <li><a href="request.php"><i class="fas fa-clipboard-list"></i> <span>Requests</span></a></li>
            <li><a href="ordercard.php"><i class="fas fa-shopping-cart"></i> <span>Order</span></a></li>
            <li><a href="archive.php" class=""><i class="fas fa-archive"></i> <span>Archive</span></a></li>
            <li><a href="logs.php"><i class="fas fa-history"></i> <span>Activity Logs</span></a></li>
            <li><a href="e-portfolio.php"><i class="fas fa-id-card"></i> <span>E-Portfolio</span></a></li>
            <li><a href="rsvptracker.php"><i class="fas fa-calendar-check"></i> <span>RSVP Tracker</span></a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content fade-in">
        <!-- Top Bar -->
        <div class="top-bar">
            <h1>users Management</h1>
            <div class="user-info">
                <div class="user-avatar">AD</div>
                <div>
                    <div class="fw-bold">Admin User</div>
                    <small class="text-muted">Administrator</small>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="stats-container stagger-animation" id="statsContainer">
            <div class="stat-card">
                <div class="stat-number" id="totalusers">0</div>
                <div class="stat-label">Total users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="activeusers">0</div>
                <div class="stat-label">Active users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="pendingusers">0</div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="inactiveusers">0</div>
                <div class="stat-label">Inactive</div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="dashboard-section">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addusersModal">
                        <i class="fas fa-plus me-1"></i> Add users
                    </button>
                    <button class="btn btn-outline-secondary" onclick="refreshusers()">
                        <i class="fas fa-sync-alt me-1"></i> Refresh
                    </button>
                </div>
                <div class="d-flex gap-2">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search users..." style="width: 250px;">
                    <select class="form-select" id="statusFilter" style="width: 150px;" onchange="filterusers()">
                        <option value="all">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                        <option value="Pending">Pending</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- users Table Section -->
                <div class="dashboard-section">
                    <h3 class="section-title"><i class="fas fa-users"></i> All users</h3>
                    <div id="usersTableContainer">
                        <!-- users will be loaded here -->
                        <div class="loading-spinner">
                            <i class="fas fa-circle-notch"></i>
                            <p class="mt-2">Loading users...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Recent Activity Section -->
                <div class="dashboard-section">
                    <h3 class="section-title"><i class="fas fa-clock"></i> Recent Activity</h3>
                    <div class="calendar-list" id="recentActivity">
                        <div class="text-center py-3">
                            <i class="fas fa-circle-notch fa-spin"></i> Loading...
                        </div>
                    </div>
                </div>
                
                <!-- Quick Stats -->
                <div class="dashboard-section mt-3">
                    <h3 class="section-title"><i class="fas fa-chart-pie"></i> users Distribution</h3>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Active</span>
                            <span class="badge bg-success" id="activePercent">0%</span>
                        </div>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-success" id="activeBar" style="width: 0%"></div>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Pending</span>
                            <span class="badge bg-warning" id="pendingPercent">0%</span>
                        </div>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-warning" id="pendingBar" style="width: 0%"></div>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Inactive</span>
                            <span class="badge bg-danger" id="inactivePercent">0%</span>
                        </div>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-danger" id="inactiveBar" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add users Modal -->
    <div class="modal fade" id="addusersModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Add New users</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addusersForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="firstName" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="lastName" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="phone">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company</label>
                                <input type="text" class="form-control" id="company">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Role</label>
                                <select class="form-select" id="role">
                                    <option value="User">User</option>
                                    <option value="Admin">Admin</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" id="status">
                                    <option value="Active">Active</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addusers()">Add users</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit users Modal -->
    <div class="modal fade" id="editusersModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit users</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editusersForm">
                        <input type="hidden" id="editusersId">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" id="editFirstName" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="editLastName" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="editEmail" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="editPhone">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company</label>
                                <input type="text" class="form-control" id="editCompany">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Role</label>
                                <select class="form-select" id="editRole">
                                    <option value="User">User</option>
                                    <option value="Moderator">Moderator</option>
                                    <option value="Admin">Admin</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" id="editStatus">
                                    <option value="Active">Active</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateusers()">Update users</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View users Modal -->
    <div class="modal fade" id="viewusersModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user me-2"></i>users Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center" id="viewusersContent">
                    <!-- Content will be populated dynamically -->
                </div>
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
                <div class="modal-body text-center" id="deleteusersContent">
                    <i class="fas fa-trash text-danger fa-3x mb-3"></i>
                    <p>Are you sure you want to delete this users?</p>
                    <p class="text-danger"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete users</button>
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
    
    <script>
        // Your Firebase configuration
        const firebaseConfig = {
            apiKey: "AIzaSyAQLWlfqnxqNCoHxTh6RL0ZSXZeJ7legz0",
            authDomain: "smartcard-475413.firebaseapp.com",
            projectId: "smartcard-475413",
            storageBucket: "smartcard-475413.firebasestorage.app",
            messagingSenderId: "731351689459",
            appId: "1:731351689459:web:1331697726d9aab1092f86"
        };

        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);
        const db = firebase.firestore();
        
        // Global variables
        let allusers = [];
        let currentusersId = null;
        let currentViewusers = null;

        document.addEventListener('DOMContentLoaded', function() {
            // Dark Mode Toggle
            const darkModeToggle = document.getElementById('darkModeToggle');
            const darkModeIcon = document.getElementById('darkModeIcon');
            const body = document.body;
            
            const isDarkMode = localStorage.getItem('darkMode') === 'enabled';
            if (isDarkMode) {
                body.classList.add('dark-mode');
                darkModeIcon.classList.remove('fa-moon');
                darkModeIcon.classList.add('fa-sun');
            }
            
            darkModeToggle.addEventListener('click', function() {
                body.classList.toggle('dark-mode');
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

            // Load users
            loadusers();
            
            // Search functionality
            document.getElementById('searchInput').addEventListener('input', filterusers);
        });

        // Load users from Firebase
        function loadusers() {
            db.collection('users').get()
                .then((querySnapshot) => {
                    allusers = [];
                    querySnapshot.forEach((doc) => {
                        allusers.push({
                            id: doc.id,
                            ...doc.data()
                        });
                    });
                    
                    displayusers(allusers);
                    updateStats();
                    updateRecentActivity();
                })
                .catch((error) => {
                    console.error('Error loading users:', error);
                    showToast('Error loading users', 'error');
                    document.getElementById('usersTableContainer').innerHTML = 
                        `<div class="empty-state"><p class="text-danger">Error: ${error.message}</p></div>`;
                });
        }

        // Display users in table
        function displayusers(users) {
            const container = document.getElementById('usersTableContainer');
            
            if (users.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-users-slash"></i>
                        <h5>No users Found</h5>
                        <p>Click "Add users" to create your first users.</p>
                    </div>
                `;
                return;
            }
            
            let html = `
                <table>
                    <thead>
                        <tr>
                            <th>users</th>
                            <th>Contact</th>
                            <th>Role</th>
                            <th>Join Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            users.forEach(users => {
                const initials = getInitials(users.firstName, users.lastName);
                const joinDate = users.createdAt ? new Date(users.createdAt.toDate()).toLocaleDateString() : 'N/A';
                const statusClass = users.status === 'Active' ? 'status-resolve' : 
                                   (users.status === 'Pending' ? 'status-pending' : '');
                
                html += `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="users-avatar" style="width: 40px; height: 40px; font-size: 0.9rem;" 
                                     onclick="viewusers('${users.id}')">
                                    ${initials}
                                </div>
                                <div class="ms-3">
                                    <div class="fw-bold">${users.firstName} ${users.lastName}</div>
                                    <small class="text-muted">${users.company || 'No Company'}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>${users.email}</div>
                            <small class="text-muted">${users.phone || 'No phone'}</small>
                        </td>
                        <td><span class="status status-resolve">${users.role || 'User'}</span></td>
                        <td>${joinDate}</td>
                        <td><span class="status ${statusClass}">${users.status || 'Pending'}</span></td>
                        <td class="action-buttons">
                            <button class="btn btn-sm btn-outline-primary me-1" onclick="viewusers('${users.id}')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-warning me-1" onclick="editusers('${users.id}')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteusers('${users.id}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            
            html += '</tbody></table>';
            container.innerHTML = html;
        }

        // Get initials from name
        function getInitials(first, last) {
            return (first ? first[0] : '') + (last ? last[0] : '');
        }

        // Add new users
        function addusers() {
            const firstName = document.getElementById('firstName').value;
            const lastName = document.getElementById('lastName').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            const company = document.getElementById('company').value;
            const role = document.getElementById('role')?.value || 'User';
            const status = document.getElementById('status').value;
            
            if (!firstName || !lastName || !email) {
                showToast('Please fill in all required fields', 'warning');
                return;
            }
            
            const usersData = {
                firstName,
                lastName,
                email,
                phone,
                company,
                role,
                status,
                createdAt: firebase.firestore.FieldValue.serverTimestamp(),
                updatedAt: firebase.firestore.FieldValue.serverTimestamp()
            };
            
            db.collection('users').add(usersData)
                .then(() => {
                    showToast('users added successfully!', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('addusersModal')).hide();
                    document.getElementById('addusersForm').reset();
                    loadusers();
                })
                .catch((error) => {
                    console.error('Error adding users:', error);
                    showToast('Error adding users', 'error');
                });
        }

        // View users details
        function viewusers(id) {
            const users = allusers.find(m => m.id === id);
            if (!users) return;
            
            currentViewusers = users;
            currentusersId = id;
            
            const initials = getInitials(users.firstName, users.lastName);
            const joinDate = users.createdAt ? new Date(users.createdAt.toDate()).toLocaleDateString() : 'N/A';
            const statusClass = users.status === 'Active' ? 'status-resolve' : 
                               (users.status === 'Pending' ? 'status-pending' : '');
            
            const content = `
                <div class="users-avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 1.5rem;">${initials}</div>
                <h5>${users.firstName} ${users.lastName}</h5>
                <span class="status ${statusClass}">${users.status || 'Pending'}</span>
                
                <div class="mt-4 text-start">
                    <p><strong>Email:</strong> ${users.email}</p>
                    <p><strong>Phone:</strong> ${users.phone || 'N/A'}</p>
                    <p><strong>Role:</strong> <span class="status status-resolve">${users.role || 'User'}</span></p>
                    <p><strong>Join Date:</strong> ${joinDate}</p>
                    <p><strong>Company:</strong> ${users.company || 'N/A'}</p>
                </div>
            `;
            
            document.getElementById('viewusersContent').innerHTML = content;
            new bootstrap.Modal(document.getElementById('viewusersModal')).show();
        }

        // Edit users
        function editusers(id) {
            const users = allusers.find(m => m.id === id);
            if (!users) return;
            
            currentusersId = id;
            
            document.getElementById('editusersId').value = id;
            document.getElementById('editFirstName').value = users.firstName || '';
            document.getElementById('editLastName').value = users.lastName || '';
            document.getElementById('editEmail').value = users.email || '';
            document.getElementById('editPhone').value = users.phone || '';
            document.getElementById('editCompany').value = users.company || '';
            document.getElementById('editRole').value = users.role || 'User';
            document.getElementById('editStatus').value = users.status || 'Pending';
            
            new bootstrap.Modal(document.getElementById('editusersModal')).show();
        }

        // Update users
        function updateusers() {
            const id = document.getElementById('editusersId').value;
            const firstName = document.getElementById('editFirstName').value;
            const lastName = document.getElementById('editLastName').value;
            const email = document.getElementById('editEmail').value;
            const phone = document.getElementById('editPhone').value;
            const company = document.getElementById('editCompany').value;
            const role = document.getElementById('editRole').value;
            const status = document.getElementById('editStatus').value;
            
            if (!firstName || !lastName || !email) {
                showToast('Please fill in all required fields', 'warning');
                return;
            }
            
            const usersData = {
                firstName,
                lastName,
                email,
                phone,
                company,
                role,
                status,
                updatedAt: firebase.firestore.FieldValue.serverTimestamp()
            };
            
            db.collection('users').doc(id).update(usersData)
                .then(() => {
                    showToast('users updated successfully!', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('editusersModal')).hide();
                    loadusers();
                })
                .catch((error) => {
                    console.error('Error updating users:', error);
                    showToast('Error updating users', 'error');
                });
        }

        // Delete users
        function deleteusers(id) {
            const users = allusers.find(m => m.id === id);
            if (!users) return;
            
            currentusersId = id;
            
            document.getElementById('deleteusersContent').innerHTML = `
                <i class="fas fa-trash text-danger fa-3x mb-3"></i>
                <p>Are you sure you want to delete <strong>${users.firstName} ${users.lastName}</strong>?</p>
                <p class="text-danger"><small>This action cannot be undone.</small></p>
            `;
            
            new bootstrap.Modal(document.getElementById('deleteusersModal')).show();
        }

        // Confirm delete
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (!currentusersId) return;
            
            db.collection('users').doc(currentusersId).delete()
                .then(() => {
                    showToast('users deleted successfully!', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('deleteusersModal')).hide();
                    loadusers();
                })
                .catch((error) => {
                    console.error('Error deleting users:', error);
                    showToast('Error deleting users', 'error');
                });
        });

        // Edit from view modal
        function editFromView() {
            bootstrap.Modal.getInstance(document.getElementById('viewusersModal')).hide();
            setTimeout(() => {
                editusers(currentusersId);
            }, 500);
        }

        // Filter users
        function filterusers() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            
            const filtered = allusers.filter(users => {
                const matchesSearch = 
                    (users.firstName?.toLowerCase() || '').includes(searchTerm) ||
                    (users.lastName?.toLowerCase() || '').includes(searchTerm) ||
                    (users.email?.toLowerCase() || '').includes(searchTerm) ||
                    (users.company?.toLowerCase() || '').includes(searchTerm);
                
                const matchesStatus = statusFilter === 'all' || users.status === statusFilter;
                
                return matchesSearch && matchesStatus;
            });
            
            displayusers(filtered);
        }

        // Update statistics
        function updateStats() {
            const total = allusers.length;
            const active = allusers.filter(m => m.status === 'Active').length;
            const pending = allusers.filter(m => m.status === 'Pending').length;
            const inactive = allusers.filter(m => m.status === 'Inactive').length;
            
            document.getElementById('totalusers').textContent = total;
            document.getElementById('activeusers').textContent = active;
            document.getElementById('pendingusers').textContent = pending;
            document.getElementById('inactiveusers').textContent = inactive;
            
            // Update percentages
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

        // Update recent activity
        function updateRecentActivity() {
            const recent = allusers.slice(0, 5);
            let html = '<div class="calendar-list">';
            
            recent.forEach(users => {
                const date = users.createdAt ? new Date(users.createdAt.toDate()).toLocaleDateString() : 'Just now';
                html += `
                    <li>
                        <strong>${users.firstName} ${users.lastName}</strong> - 
                        ${users.status === 'Active' ? 'Joined' : 'Pending'} 
                        <small class="text-muted">(${date})</small>
                    </li>
                `;
            });
            
            html += '</div>';
            document.getElementById('recentActivity').innerHTML = html;
        }

        // Show toast notification
        function showToast(message, type) {
            const toastContainer = document.getElementById('toastContainer');
            const toastId = 'toast-' + Date.now();
            
            const bgColor = type === 'success' ? 'bg-success' : 
                           type === 'warning' ? 'bg-warning' : 'bg-danger';
            
            const icon = type === 'success' ? 'fa-check-circle' :
                        type === 'warning' ? 'fa-exclamation-triangle' : 'fa-times-circle';
            
            const toast = document.createElement('div');
            toast.id = toastId;
            toast.className = `toast show align-items-center text-white ${bgColor} border-0`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas ${icon} me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;
            
            toastContainer.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        // Refresh users
        function refreshusers() {
            document.getElementById('usersTableContainer').innerHTML = `
                <div class="loading-spinner">
                    <i class="fas fa-circle-notch"></i>
                    <p class="mt-2">Refreshing...</p>
                </div>
            `;
            loadusers();
        }
    </script>
</body>
</html>