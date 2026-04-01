<?php
    require_once 'auth_guard.php';
    requireAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requests - Cavite Business Owners Club</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="icon" href="req.png"/>
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
            <h3><i class="fas fa-tachometer-alt"> </i>CBOC</h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
            <li><a href="members.php"><i class="fas fa-users"></i><span>Users</span></a></li>
            <li><a href="location.php"><i class="fas fa-map-marked-alt"></i><span>Location</span></a></li>
            <li><a href="e-portfolio.php"><i class="fas fa-id-card"></i><span>E-Portfolio</span></a></li>
            <li><a href="calendar.php"><i class="fas fa-calendar"></i><span>Calendar</span></a></li>
            <li><a href="rsvp.php"><i class="fas fa-calendar-check"></i><span>RSVP Tracker</span></a></li>
            <li><a href="#" class="active"><i class="fas fa-clipboard-list"></i><span>Approvals</span></a></li>
            <li><a href="ordercard.php"><i class="fas fa-shopping-cart"></i><span>NFC Card</span></a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar with Three Dots Menu -->
        <div class="top-bar">
            <div class="page-header">
                <div class="club-title">Cavite Business Owners Club</div>
                <h1 class="page-title">Approvals Management</h1>
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
                        <button class="dropdown-item" onclick="exportRequests()">
                            <i class="fas fa-download"></i> Export Approvals
                        </button>
                        <button class="dropdown-item" onclick="printRequests()">
                            <i class="fas fa-print"></i> Print List
                        </button>
                        <div class="dropdown-divider"></div>
                        <button class="dropdown-item" onclick="refreshRequests()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                        <button class="dropdown-item" onclick="showRequestHelp()">
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

        <!-- Stats Section -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-number">???</div>
                <div class="stat-label">Total Approvals</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">???</div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">???</div>
                <div class="stat-label">Approved</div>
            </div>
            <!-- <div class="stat-card">
                <div class="stat-number">5</div>
                <div class="stat-label">Rejected</div>
            </div> -->
        </div>

        <!-- Request Management Section -->
        <div class="request-container">
            <div class="request-header">
                <h2 class="request-title"><i class="fas fa-clipboard-list"></i> All Approvals</h2>
                <div class="request-actions">
                    <button class="request-btn" id="filterRequests">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <button class="request-btn" id="newRequestBtn" data-bs-toggle="modal" data-bs-target="#requestModal">
                        <i class="fas fa-plus"></i> New Approval
                    </button>
                </div>
            </div>

            <div class="request-table-container">
                <table class="request-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Approval Type</th>
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
                    <h5 class="modal-title" id="requestModalLabel">Add New Approval</h5>
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
                            <label for="requestType" class="form-label">Approval Type</label>
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
                    <button type="button" class="btn btn-primary" id="saveRequest">Save Approval</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Event Modal -->
    <div class="modal fade" id="eventReviewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Event Review</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h4 id="reviewTitle"></h4>
                    <p id="reviewDescription"></p>
                    <hr>
                    <p><strong>Date:</strong> <span id="reviewDate"></span></p>
                    <p><strong>Time:</strong> <span id="reviewTime"></span></p>
                    <p><strong>Venue:</strong> <span id="reviewVenue"></span></p>
                    <p><strong>Available Slots:</strong> <span id="reviewSlots"></span></p>
                    <p><strong>Organizer:</strong> <span id="reviewOrganizer"></span></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" id="rejectEventBtn">
                        Reject
                    </button>
                    <button class="btn btn-success" id="approveEventBtn">
                        Approve Event
                    </button>
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
    <script type="module">
        import { db } from "./Firebase/firebase_conn.js";
        import {
            collection,
            query,
            where,
            getDocs,
            updateDoc,
            deleteDoc,
            doc,
            getDoc,
            onSnapshot
        } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-firestore.js";

        // async function testFirestore() {
        //     try {
        //         const snapshot = await getDocs(collection(db, "users"));
        //         console.log("Number of documents in 'users':", snapshot.size);
        //         snapshot.forEach(docSnap => {
        //             console.log(docSnap.id, docSnap.data());
        //         });
        //     } catch (error) {
        //         console.error("Firestore error:", error);
        //     }
        // }

        // testFirestore();

        document.addEventListener('DOMContentLoaded', function() {
            // Three Dots Menu Functions
            window.exportRequests = function() {
                const requests = window.requests || [];
                if (requests.length === 0) {
                    showNotification('No requests to export', 'warning');
                    return;
                }
                
                const dataStr = JSON.stringify(requests, null, 2);
                const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
                const exportFileDefaultName = 'cboc-requests-export.json';
                
                const linkElement = document.createElement('a');
                linkElement.setAttribute('href', dataUri);
                linkElement.setAttribute('download', exportFileDefaultName);
                linkElement.click();
                
                showNotification('Approvals exported successfully!', 'success');
            };

            window.printRequests = function() {
                window.print();
            };

            window.refreshRequests = function() {
                location.reload();
            };

            window.showRequestHelp = function() {
                alert(`
Approval Management Help:
- Click "New Approval" to create a Approval
- Use Edit button to modify Approval details
- Use Accept button to approve pending Approvals
- Use Delete button to remove Approvals
- Filter by status using the Filter button
- Stats cards show real-time Approval counts
                `);
            };

            // Three Dots Menu Toggle
            const dotsMenuBtn = document.getElementById('dotsMenuBtn');
            const dotsDropdown = document.getElementById('dotsDropdown');

            dotsMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                dotsDropdown.classList.toggle('show');
            });

            document.addEventListener('click', function() {
                dotsDropdown.classList.remove('show');
            });

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

            // Make requests available globally
            window.requests = requests;

            // Render requests table
            let membershipRequests = [];
            let businessRequests = [];
            let eventRequests = [];

            function renderRequests() {
                const tableBody = document.getElementById('requestsTableBody');
                if (!tableBody) return;

                // 🔹 USERS (Membership Approval)
                const usersQuery = query(
                    collection(db, "users"),
                    where("approved", "==", false)
                );

                onSnapshot(usersQuery, (snapshot) => {
                    membershipRequests = snapshot.docs.map(docSnap => ({
                        id: docSnap.id,
                        type: "Membership Approval",
                        username: docSnap.data().username,
                        email: docSnap.data().email,
                        raw: docSnap.data()
                    }));

                    updateTable();
                });

                // 🔹 BUSINESSES (Business Approval)
                const businessQuery = query(
                    collection(db, "businesses"),
                    where("status", "==", "pending") // ⚠️ adjust if needed
                );

                onSnapshot(businessQuery, (snapshot) => {
                    businessRequests = snapshot.docs.map(docSnap => ({
                        id: docSnap.id,
                        type: "Business Approval",
                        username: docSnap.data().business_name || "Business",
                        email: docSnap.data().email,
                        raw: docSnap.data()
                    }));

                    updateTable();
                });

                // 🔹 EVENTS (Event Approval)
                const eventsQuery = query(
                    collection(db, "events"),
                    where("approved", "==", false)
                );

                onSnapshot(eventsQuery, (snapshot) => {
                    eventRequests = snapshot.docs.map(docSnap => ({
                        id: docSnap.id,
                        type: "Event Approval",
                        username: docSnap.data().title || "Event",
                        email: docSnap.data().createdBy || "-",
                        raw: docSnap.data()
                    }));

                    updateTable();
                });

                // 🔁 Combine + Render
                function updateTable() {
                    tableBody.innerHTML = "";

                    const allRequests = [
                        ...membershipRequests,
                        ...businessRequests,
                        ...eventRequests
                    ];

                    if (allRequests.length === 0) {
                        tableBody.innerHTML = `
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No pending approvals
                                </td>
                            </tr>
                        `;
                        return;
                    }

                    allRequests.forEach(req => {
                        const row = document.createElement('tr');

                        row.innerHTML = `
                            <td>${req.username ?? '-'}</td>
                            <td>${req.type}</td>
                            <td>${req.raw.createdBy ?? '-'}</td>
                            <td>Create</td>
                            <td><span class="request-status status-pending">Pending</span></td>
                            <td>
                                <!-- <button class="request-action-btn edit" data-id="${req.id}" data-type="${req.type}">
                                    <i class="fas fa-edit"></i>
                                </button> -->
                                ${req.type === "Event Approval" ? `
                                    <button class="request-action-btn review" data-id="${req.id}" data-type="${req.type}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                ` : `
                                    <button class="request-action-btn accept" data-id="${req.id}" data-type="${req.type}">
                                        <i class="fas fa-check"></i>
                                    </button>
                                `}
                                <!-- <button class="request-action-btn delete" data-id="${req.id}" data-type="${req.type}">
                                    <i class="fas fa-trash"></i>
                                </button> -->
                            </td>
                        `;

                        tableBody.appendChild(row);
                    });

                    attachRequestHandlers();
                    updateStats();
                }
            }

            function attachRequestHandlers() {
                document.querySelectorAll('.request-action-btn.edit').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const id = btn.dataset.id;
                        editRequest(id); // keep your existing logic
                    });
                });

                document.querySelectorAll('.request-action-btn.accept').forEach(btn => {
                    btn.addEventListener('click', async () => {

                        const id = btn.dataset.id;
                        const type = btn.dataset.type;

                        try {
                            // 🚨 If Event Approval → open review modal first
                            if (type === "Event Approval") {
                                const docRef = doc(db, "events", id);
                                const snap = await getDoc(docRef);

                                if (!snap.exists()) return;

                                const data = snap.data();

                                document.getElementById("reviewTitle").textContent = data.title || "Untitled Event";
                                document.getElementById("reviewDescription").textContent = data.description || "";
                                document.getElementById("reviewDate").textContent = data.date || "";
                                document.getElementById("reviewLocation").textContent = data.location || "";
                                document.getElementById("reviewEmail").textContent = data.email || "";

                                document.getElementById("approveEventBtn").dataset.id = id;
                                document.getElementById("rejectEventBtn").dataset.id = id;

                                new bootstrap.Modal(document.getElementById("eventReviewModal")).show();
                                return; // ⛔ stop normal approval
                            }

                            // 🔀 Normal approvals
                            let ref;
                            let updateData = {};

                            if (type === "Membership Approval") {
                                ref = doc(db, "users", id);
                                updateData = { approved: true };

                            } else if (type === "Business Approval") {
                                ref = doc(db, "businesses", id);
                                updateData = { status: "approved" };
                            }

                            if (!ref) return;
                            await updateDoc(ref, updateData);
                            showNotification('Request approved successfully!', 'success');

                        } catch (error) {
                            console.error("Approval error:", error);
                            showNotification('Failed to approve request', 'error');
                        }
                    });
                });

                document.querySelectorAll('.request-action-btn.review').forEach(btn => {
                    btn.addEventListener('click', async () => {
                        const id = btn.dataset.id;

                        try {
                            const snap = await getDoc(doc(db,"events",id));
                            if(!snap.exists()) return;
                            const data = snap.data();
                            const eventDate = data.date?.toDate().toLocaleDateString() ?? "-";
                            const startTime = formatTime(data.startHour, data.startMinute);
                            const endTime = formatTime(data.endHour, data.endMinute);

                            document.getElementById("reviewTitle").textContent = data.title || "Untitled Event";
                            document.getElementById("reviewDescription").textContent = data.description || "-";
                            document.getElementById("reviewDate").textContent = eventDate;
                            document.getElementById("reviewVenue").textContent = data.venue || "-";
                            document.getElementById("reviewSlots").textContent = data.availableSlots ?? "-";
                            document.getElementById("reviewOrganizer").textContent = data.createdBy ?? "-";
                            document.getElementById("reviewTime").textContent = `${startTime} - ${endTime}`;

                            document.getElementById("approveEventBtn").dataset.id = id;
                            document.getElementById("rejectEventBtn").dataset.id = id;

                            new bootstrap.Modal(
                                document.getElementById("eventReviewModal")
                            ).show();

                        } catch(error){
                            console.error(error);
                            showNotification("Failed to load event","error");
                        }
                    });
                });

                document.querySelectorAll('.request-action-btn.delete').forEach(btn => {
                    btn.addEventListener('click', async () => {
                        const id = btn.dataset.id;

                        if (!confirm("Delete this request?")) return;

                        await deleteDoc(
                            doc(db, "users", id)
                        );

                        showNotification('Request deleted successfully!', 'success');
                        renderRequests(); // refresh table
                    });
                });
            }

            document.getElementById("approveEventBtn").addEventListener("click", async function(){
                const id = this.dataset.id;

                await updateDoc(doc(db,"events",id),{
                    approved:true
                });
                showNotification("Event approved","success");

                bootstrap.Modal.getInstance(
                    document.getElementById("eventReviewModal")
                ).hide();
            });

            document.getElementById("approveEventBtn").addEventListener("click", async function(){
                const id = this.dataset.id;

                await updateDoc(doc(db,"events",id),{
                    approved:true
                });
                showNotification("Event approved","success");

                bootstrap.Modal.getInstance(
                    document.getElementById("eventReviewModal")
                ).hide();
            });

            // Update statistics
            let stats = {
                membershipPending: 0,
                membershipApproved: 0,
                businessPending: 0,
                businessApproved: 0,
                eventPending: 0,
                eventApproved: 0
            };

            function loadRequestStats() {
                // 🔹 USERS (Membership)
                onSnapshot(collection(db, "users"), (snapshot) => {
                    let pending = 0;
                    let approved = 0;

                    snapshot.forEach(doc => {
                        const data = doc.data();
                        if (data.approved === true) approved++;
                        else pending++;
                    });

                    stats.membershipPending = pending;
                    stats.membershipApproved = approved;

                    updateStats();
                });

                // 🔹 BUSINESSES
                onSnapshot(collection(db, "businesses"), (snapshot) => {
                    let pending = 0;
                    let approved = 0;

                    snapshot.forEach(doc => {
                        const data = doc.data();

                        if (data.status === "approved") {
                            approved++;
                        } else {
                            pending++; // anything not approved = pending
                        }
                    });

                    stats.businessPending = pending;
                    stats.businessApproved = approved;

                    updateStats();
                });

                // 🔹 EVENTS
                onSnapshot(collection(db, "events"), (snapshot) => {
                    let pending = 0;
                    let approved = 0;

                    snapshot.forEach(doc => {
                        const data = doc.data();

                        if (data.approved === true) {
                            approved++;
                        } else {
                            pending++;
                        }
                    });

                    stats.eventPending = pending;
                    stats.eventApproved = approved;

                    updateStats();
                });
            }

            function updateStats() {
                const total =
                    stats.membershipPending + stats.membershipApproved +
                    stats.businessPending + stats.businessApproved +
                    stats.eventPending + stats.eventApproved;

                const pending =
                    stats.membershipPending +
                    stats.businessPending +
                    stats.eventPending;

                const approved =
                    stats.membershipApproved +
                    stats.businessApproved +
                    stats.eventApproved;

                const statEls = document.querySelectorAll('.stat-number');

                if (statEls[0]) statEls[0].textContent = total;
                if (statEls[1]) statEls[1].textContent = pending;
                if (statEls[2]) statEls[2].textContent = approved;
            }
            loadRequestStats();

            // 12 hr format time
            function formatTime(hour = 0, minute = 0) {
                const suffix = hour >= 12 ? "PM" : "AM";
                const hour12 = hour % 12 === 0 ? 12 : hour % 12; // convert 0 or 12 -> 12, others mod 12
                const minuteStr = String(minute).padStart(2, "0");
                return `${hour12}:${minuteStr} ${suffix}`;
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
                            showNotification('Request updated successfully!', 'success');
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
                        showNotification('Request created successfully!', 'success');
                    }

                    localStorage.setItem('cbocRequests', JSON.stringify(requests));
                    
                    // Reset form and close modal
                    document.getElementById('requestForm').reset();
                    bootstrap.Modal.getInstance(document.getElementById('requestModal')).hide();
                    
                    // Re-render requests
                    renderRequests();
                } else {
                    showNotification('Please fill in all required fields.', 'warning');
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

            // Notification helper
            function showNotification(message, type) {
                const icons = { 
                    success: 'fa-check-circle', 
                    error: 'fa-exclamation-circle', 
                    warning: 'fa-exclamation-triangle', 
                    info: 'fa-info-circle' 
                };
                
                const notification = document.createElement('div');
                notification.className = `notification ${type}`;
                notification.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: var(--${type === 'success' ? 'success' : type === 'warning' ? 'warning' : type === 'error' ? 'danger' : 'primary'});
                    color: white;
                    padding: 15px 20px;
                    border-radius: 5px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    z-index: 9999;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    animation: slideIn 0.3s ease;
                `;
                notification.innerHTML = `<i class="fas ${icons[type]}"></i><span>${message}</span>`;
                
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.style.animation = 'slideOut 0.3s ease';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }

            // Add CSS for animations if not already present
            if (!document.querySelector('#notification-styles')) {
                const style = document.createElement('style');
                style.id = 'notification-styles';
                style.textContent = `
                    @keyframes slideIn {
                        from { transform: translateX(100%); opacity: 0; }
                        to { transform: translateX(0); opacity: 1; }
                    }
                    @keyframes slideOut {
                        from { transform: translateX(0); opacity: 1; }
                        to { transform: translateX(100%); opacity: 0; }
                    }
                `;
                document.head.appendChild(style);
            }

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