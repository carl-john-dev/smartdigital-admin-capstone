<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="icon" href="mems.png"/>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-tachometer-alt"></i> Dashboard</h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="admin_profile.php"><i class="fas fa-id-card"></i> <span>Profile</span></a></li>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
            <li><a href="signup.php"><i class="fas fa-user-plus"></i> <span>Create Account</span></a></li>
            <li><a href="#" class="active"><i class="fas fa-users"></i> <span>Members</span></a></li>
            <li><a href="calendar.php"><i class="fas fa-calendar"></i> <span>Calendar</span></a></li>
            <li><a href="location.php"><i class="fas fa-map-marked-alt"></i><span>Location</span></a></li>
            <li><a href="request.php"><i class="fas fa-clipboard-list"></i> <span>Requests</span></a></li>
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
            <h1>Members Management</h1>
            <div class="user-info">
                <div class="user-avatar">AD</div>
                <div>
                    <div class="fw-bold">Admin User</div>
                    <small class="text-muted">Administrator</small>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="stats-container stagger-animation">
            <div class="stat-card">
                <div class="stat-number">856</div>
                <div class="stat-label">Total Members</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">723</div>
                <div class="stat-label">Active Members</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">45</div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">88</div>
                <div class="stat-label">Inactive</div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="dashboard-section">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                        <i class="fas fa-plus me-1"></i> Add Member
                    </button>
                    <button class="btn btn-outline-secondary me-2">
                        <i class="fas fa-download me-1"></i> Export
                    </button>
                    <button class="btn btn-outline-secondary">
                        <i class="fas fa-upload me-1"></i> Import
                    </button>
                </div>
                <div class="d-flex gap-2">
                    <input type="text" class="form-control" placeholder="Search members..." style="width: 250px;">
                    <select class="form-select" style="width: 150px;">
                        <option>All Status</option>
                        <option>Active</option>
                        <option>Inactive</option>
                        <option>Pending</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Members Table Section -->
                <div class="dashboard-section">
                    <h3 class="section-title"><i class="fas fa-users"></i> All Members</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Contact</th>
                                <th>Role</th>
                                <th>Join Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="member-avatar" style="width: 40px; height: 40px; font-size: 0.9rem;">LM</div>
                                        <div class="ms-3">
                                            <div class="fw-bold">Lucia Merry</div>
                                            <small class="text-muted">CEO, THE MIST COP.</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>lucia.merry@mistcop.com</div>
                                    <small class="text-muted">+1 (555) 123-4567</small>
                                </td>
                                <td><span class="status status-resolve">Admin</span></td>
                                <td>2024-01-15</td>
                                <td><span class="status status-resolve">Active</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#viewMemberModal">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning me-1" data-bs-toggle="modal" data-bs-target="#editMemberModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteMemberModal">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="member-avatar" style="width: 40px; height: 40px; font-size: 0.9rem; background: #28a745;">ST</div>
                                        <div class="ms-3">
                                            <div class="fw-bold">Sabrina Tan</div>
                                            <small class="text-muted">CEO, Realtyvale.</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>sabrina.tan@realtyvale.com</div>
                                    <small class="text-muted">+1 (555) 234-5678</small>
                                </td>
                                <td><span class="status status-resolve">User</span></td>
                                <td>2024-02-20</td>
                                <td><span class="status status-resolve">Active</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary me-1">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning me-1">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="member-avatar" style="width: 40px; height: 40px; font-size: 0.9rem; background: #dc3545;">AS</div>
                                        <div class="ms-3">
                                            <div class="fw-bold">Andy Sewer</div>
                                            <small class="text-muted">CEO, Fawcettor.</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>andy.sewer@fawcettor.com</div>
                                    <small class="text-muted">+1 (555) 345-6789</small>
                                </td>
                                <td><span class="status status-resolve">Moderator</span></td>
                                <td>2024-03-10</td>
                                <td><span class="status status-pending">Inactive</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary me-1">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning me-1">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="member-avatar" style="width: 40px; height: 40px; font-size: 0.9rem; background: #ffc107;">SM</div>
                                        <div class="ms-3">
                                            <div class="fw-bold">Shanon Matilda</div>
                                            <small class="text-muted">CEO, Goldenfruit.</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>shanon.matilda@goldenfruit.com</div>
                                    <small class="text-muted">+1 (555) 456-7890</small>
                                </td>
                                <td><span class="status status-resolve">User</span></td>
                                <td>2024-01-28</td>
                                <td><span class="status status-pending">Pending</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary me-1">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning me-1">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="member-avatar" style="width: 40px; height: 40px; font-size: 0.9rem; background: #6f42c1;">EC</div>
                                        <div class="ms-3">
                                            <div class="fw-bold">Ethan Cravejal</div>
                                            <small class="text-muted">CEO, Newcastle.</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>ethan.cravejal@newcastle.com</div>
                                    <small class="text-muted">+1 (555) 567-8901</small>
                                </td>
                                <td><span class="status status-resolve">User</span></td>
                                <td>2024-02-15</td>
                                <td><span class="status status-resolve">Active</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary me-1">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning me-1">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Recent Activity Section -->
                <div class="dashboard-section">
                    <h3 class="section-title"><i class="fas fa-clock"></i> Recent Activity</h3>
                    <div class="calendar-list">
                        <li><strong>New Member</strong> - John Doe registered</li>
                        <li><strong>Profile Update</strong> - Sabrina Tan updated profile</li>
                        <li><strong>Status Change</strong> - Andy Sewer deactivated</li>
                        <li><strong>New Member</strong> - Sarah Johnson registered</li>
                    </div>
                </div>
                
                <!-- Quick Actions section removed -->
            </div>
        </div>
    </div>

    <!-- Add Member Modal -->
    <div class="modal fade" id="addMemberModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Add New Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="tel" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role</label>
                            <select class="form-select" required>
                                <option value="">Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="moderator">Moderator</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" required>
                                <option value="">Select Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Add Member</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Member Modal -->
    <div class="modal fade" id="editMemberModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" value="Lucia" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" value="Merry" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="lucia.merry@mistcop.com" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="tel" class="form-control" value="+1 (555) 123-4567">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role</label>
                            <select class="form-select" required>
                                <option value="admin" selected>Admin</option>
                                <option value="moderator">Moderator</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" required>
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Update Member</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Member Modal -->
    <div class="modal fade" id="viewMemberModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user me-2"></i>Member Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="member-avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 1.5rem;">LM</div>
                    <h5>Lucia Merry</h5>
                    <span class="status status-resolve">Active</span>
                    
                    <div class="mt-4 text-start">
                        <p><strong>Email:</strong> lucia.merry@mistcop.com</p>
                        <p><strong>Phone:</strong> +1 (555) 123-4567</p>
                        <p><strong>Role:</strong> <span class="status status-resolve">Admin</span></p>
                        <p><strong>Join Date:</strong> January 15, 2024</p>
                        <p><strong>Company:</strong> THE MIST COP.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editMemberModal" data-bs-dismiss="modal">Edit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteMemberModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-trash text-danger fa-3x mb-3"></i>
                    <p>Are you sure you want to delete <strong>Lucia Merry</strong>?</p>
                    <p class="text-danger"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger">Delete Member</button>
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

            // Search functionality
            const searchInput = document.querySelector('input[type="text"]');
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                document.querySelectorAll('tbody tr').forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        });
    </script>
</body>
</html>