<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Management - CBOC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="icon" href="mems.png"/>
    <style>
        .loading-spinner { text-align:center; padding:50px; }
        .loading-spinner i { font-size:3rem; color:var(--primary); animation:spin 1s linear infinite; }
        @keyframes spin { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }

        .users-avatar { cursor:pointer; transition:transform .3s; }
        .users-avatar:hover { transform:scale(1.1); }
        .action-buttons { white-space:nowrap; }

        .toast-container { position:fixed; top:20px; right:20px; z-index:9999; }
        .toast { min-width:250px; }

        .empty-state { text-align:center; padding:50px; color:var(--gray); }
        .empty-state i { font-size:4rem; margin-bottom:20px; opacity:.5; }

        /* Three-dots menu */
        .three-dots-menu { position:relative; display:inline-block; margin-right:15px; }
        .dots-button { background:transparent; border:none; color:var(--text-color); font-size:1.5rem; cursor:pointer; padding:5px 10px; border-radius:5px; transition:all .3s; }
        .dots-button:hover { background:rgba(67,97,238,.1); color:var(--primary); }
        .dropdown-menu-custom { position:absolute; top:100%; right:0; background:var(--card-bg); border:1px solid var(--border-color); border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,.15); min-width:200px; z-index:1000; display:none; margin-top:5px; }
        .dropdown-menu-custom.show { display:block; animation:fadeIn .2s ease; }
        .dropdown-item { display:flex; align-items:center; gap:10px; padding:12px 15px; color:var(--text-color); text-decoration:none; transition:all .2s; cursor:pointer; border:none; background:transparent; width:100%; text-align:left; }
        .dropdown-item:hover { background:rgba(67,97,238,.1); color:var(--primary); }
        .dropdown-item i { width:20px; color:var(--primary); }
        .dropdown-divider { height:1px; background:var(--border-color); margin:5px 0; }
        @keyframes fadeIn { from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:translateY(0)} }

        /* Tab nav */
        .admin-tabs { display:flex; gap:6px; margin-bottom:20px; flex-wrap:wrap; }
        .admin-tab {
            padding:8px 18px; border-radius:20px; border:1px solid var(--border-color);
            background:transparent; color:var(--text-color); cursor:pointer; font-size:.9rem;
            transition:all .2s; display:flex; align-items:center; gap:6px;
        }
        .admin-tab.active { background:#B71C1C; color:white; border-color:#B71C1C; }
        .admin-tab .tab-badge {
            background:rgba(255,255,255,.3); border-radius:10px;
            padding:1px 7px; font-size:.75rem; font-weight:700;
        }
        .admin-tab:not(.active) .tab-badge { background:#B71C1C; color:white; }

        /* Business cards */
        .biz-approval-card {
            background:var(--card-bg); border-radius:12px; padding:18px;
            border:1px solid var(--border-color); margin-bottom:14px; transition:all .25s;
        }
        .biz-approval-card:hover { box-shadow:0 5px 20px rgba(0,0,0,.08); }
        .biz-approval-card.status-pending  { border-left:4px solid #f59e0b; }
        .biz-approval-card.status-approved { border-left:4px solid #10b981; }
        .biz-approval-card.status-rejected { border-left:4px solid #ef4444; }

        .biz-logo-sm { width:48px; height:48px; border-radius:50%; object-fit:cover; border:2px solid #B71C1C; flex-shrink:0; }
        .biz-logo-placeholder-sm {
            width:48px; height:48px; border-radius:50%; background:#fce4e4;
            display:flex; align-items:center; justify-content:center;
            border:2px solid #B71C1C; flex-shrink:0; font-weight:700; color:#B71C1C;
        }

        .badge-approved { background:#d1fae5; color:#065f46; font-size:.75rem; padding:3px 9px; border-radius:10px; font-weight:600; }
        .badge-pending  { background:#fef3c7; color:#92400e; font-size:.75rem; padding:3px 9px; border-radius:10px; font-weight:600; }
        .badge-rejected { background:#fee2e2; color:#991b1b; font-size:.75rem; padding:3px 9px; border-radius:10px; font-weight:600; }

        /* DTI doc button */
        .dti-btn {
            display:inline-flex; align-items:center; gap:6px; font-size:.82rem;
            color:#B71C1C; background:#fce4e4; border:1px solid #f5c2c2;
            border-radius:7px; padding:5px 11px; cursor:pointer; text-decoration:none;
            transition:all .2s;
        }
        .dti-btn:hover { background:#f5c2c2; color:#B71C1C; }

        /* Filter bar */
        .filter-bar { display:flex; gap:8px; align-items:center; flex-wrap:wrap; margin-bottom:16px; }
        .filter-pill { padding:5px 14px; border-radius:20px; border:1px solid var(--border-color); background:transparent; color:var(--text-color); cursor:pointer; font-size:.85rem; transition:all .2s; }
        .filter-pill.active { background:#B71C1C; color:white; border-color:#B71C1C; }
    </style>
</head>
<body>
<div class="toast-container" id="toastContainer"></div>

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-header"><h3><i class="fas fa-tachometer-alt"></i> Dashboard</h3></div>
    <ul class="sidebar-menu">
        <li><a href="dashboard.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
        <li><a href="members.php" class="active"><i class="fas fa-users"></i> <span>Users</span></a></li>
        <li><a href="calendar.php"><i class="fas fa-calendar"></i> <span>Calendar</span></a></li>
        <li><a href="location.php"><i class="fas fa-map-marked-alt"></i><span>Location</span></a></li>
        <li><a href="request.php"><i class="fas fa-clipboard-list"></i> <span>Requests</span></a></li>
        <li><a href="ordercard.php"><i class="fas fa-credit-card"></i> <span>NFC Card</span></a></li>
        <li><a href="e-portfolio.php"><i class="fas fa-id-card"></i> <span>E-Portfolio</span></a></li>
        <li><a href="rsvptracker.php"><i class="fas fa-calendar-check"></i> <span>RSVP Tracker</span></a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content fade-in">
    <!-- Top Bar -->
    <div class="top-bar">
        <h1>Users Management</h1>
        <div class="d-flex align-items-center">
            <div class="three-dots-menu">
                <button class="dots-button" id="dotsMenuBtn"><i class="fas fa-ellipsis-h"></i></button>
                <div class="dropdown-menu-custom" id="dotsDropdown">
                    <a href="archive.php" class="dropdown-item"><i class="fas fa-archive"></i> Archive</a>
                    <a href="logs.php" class="dropdown-item"><i class="fas fa-history"></i> Activity Logs</a>
                    <div class="dropdown-divider"></div>
                    <button class="dropdown-item" onclick="exportUsers()"><i class="fas fa-download"></i> Export Users</button>
                    <button class="dropdown-item" onclick="window.print()"><i class="fas fa-print"></i> Print List</button>
                    <div class="dropdown-divider"></div>
                    <button class="dropdown-item" onclick="location.reload()"><i class="fas fa-sync-alt"></i> Refresh</button>
                </div>
            </div>
            <div class="user-info">
                <div class="user-avatar">AD</div>
                <div><div class="fw-bold">Admin User</div><small class="text-muted">Administrator</small></div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-container stagger-animation" id="statsContainer">
        <div class="stat-card"><div class="stat-number" id="totalusers">0</div><div class="stat-label">Total Users</div></div>
        <div class="stat-card"><div class="stat-number" id="activeusers">0</div><div class="stat-label">Active Users</div></div>
        <div class="stat-card"><div class="stat-number" id="pendingusers">0</div><div class="stat-label">Pending</div></div>
        <div class="stat-card"><div class="stat-number" id="inactiveusers">0</div><div class="stat-label">Inactive</div></div>
        <div class="stat-card">
            <div class="stat-number" id="pendingBizCount" style="color:#92400e;">0</div>
            <div class="stat-label">Business Reviews</div>
        </div>
    </div>

    <!-- ── TABS ── -->
    <div class="admin-tabs">
        <button class="admin-tab active" data-tab="users">
            <i class="fas fa-users"></i> Users
        </button>
        <button class="admin-tab" data-tab="businesses" id="bizTabBtn">
            <i class="fas fa-store"></i> Business Approvals
            <span class="tab-badge" id="bizTabBadge">0</span>
        </button>
    </div>

    <!-- ══ TWO-COLUMN LAYOUT: left = tab content, right = always-visible panel ══ -->
    <div class="row" style="align-items:flex-start;">

        <!-- LEFT: tab panels -->
        <div class="col-lg-8">

            <!-- USERS TAB -->
            <div id="tabUsers">
                <div class="dashboard-section">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addusersModal">
                                <i class="fas fa-plus me-1"></i> Add Users
                            </button>
                            <button class="btn btn-outline-secondary" onclick="loadusers()">
                                <i class="fas fa-sync-alt me-1"></i> Refresh
                            </button>
                        </div>
                        <div class="d-flex gap-2">
                            <input type="text" class="form-control" id="searchInput" placeholder="Search Users…" style="width:220px;">
                            <select class="form-select" id="statusFilter" style="width:140px;" onchange="filterusers()">
                                <option value="all">All Status</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="Pending">Pending</option>
                            </select>
                        </div>
                    </div>
                    <h3 class="section-title"><i class="fas fa-users"></i> All Users</h3>
                    <div id="usersTableContainer">
                        <div class="loading-spinner"><i class="fas fa-circle-notch"></i><p class="mt-2">Loading Users…</p></div>
                    </div>
                </div>
            </div>

            <!-- BUSINESS APPROVALS TAB -->
            <div id="tabBusinesses" style="display:none;">
                <div class="dashboard-section">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <h3 class="section-title mb-0"><i class="fas fa-store me-2" style="color:#B71C1C;"></i>Business Approval Requests</h3>
                        <div class="filter-bar">
                            <button class="filter-pill active" data-biz-filter="all">All</button>
                            <button class="filter-pill" data-biz-filter="pending">Pending</button>
                            <button class="filter-pill" data-biz-filter="approved">Approved</button>
                            <button class="filter-pill" data-biz-filter="rejected">Rejected</button>
                        </div>
                    </div>
                    <input type="text" id="bizSearch" class="form-control mb-3" placeholder="Search businesses…" style="max-width:320px;">
                    <div id="bizApprovalList">
                        <div class="loading-spinner"><i class="fas fa-circle-notch"></i><p class="mt-2">Loading…</p></div>
                    </div>
                </div>
            </div>

        </div><!-- /col-lg-8 -->

        <!-- RIGHT: always-visible Users Distribution + quick stats -->
        <div class="col-lg-4">
            <div class="dashboard-section" style="position:sticky; top:20px;">
                <h3 class="section-title"><i class="fas fa-chart-pie"></i> Users Distribution</h3>
                <div class="mb-2 d-flex justify-content-between"><span>Active</span><span class="badge bg-success" id="activePercent">0%</span></div>
                <div class="progress mb-3"><div class="progress-bar bg-success" id="activeBar" style="width:0%"></div></div>
                <div class="mb-2 d-flex justify-content-between"><span>Pending</span><span class="badge bg-warning" id="pendingPercent">0%</span></div>
                <div class="progress mb-3"><div class="progress-bar bg-warning" id="pendingBar" style="width:0%"></div></div>
                <div class="mb-2 d-flex justify-content-between"><span>Inactive</span><span class="badge bg-danger" id="inactivePercent">0%</span></div>
                <div class="progress mb-3"><div class="progress-bar bg-danger" id="inactiveBar" style="width:0%"></div></div>

                <hr>

                <!-- Quick business summary — always useful context -->
                <h3 class="section-title"><i class="fas fa-store"></i> Business Summary</h3>
                <div class="mb-2 d-flex justify-content-between align-items-center">
                    <span>Approved</span>
                    <span class="badge-approved" id="bizApprovedCount">0</span>
                </div>
                <div class="mb-2 d-flex justify-content-between align-items-center">
                    <span>Pending Review</span>
                    <span class="badge-pending" id="bizPendingCount">0</span>
                </div>
                <div class="mb-2 d-flex justify-content-between align-items-center">
                    <span>Rejected</span>
                    <span class="badge-rejected" id="bizRejectedCount">0</span>
                </div>
            </div>
        </div><!-- /col-lg-4 -->

    </div><!-- /row -->

</div><!-- /main-content -->

<!-- === ADD USER MODAL ======================================= -->
<div class="modal fade" id="addusersModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Add New Users</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addusersForm">
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">First Name <span class="text-danger">*</span></label><input type="text" class="form-control" id="firstName" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Last Name <span class="text-danger">*</span></label><input type="text" class="form-control" id="lastName" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Email <span class="text-danger">*</span></label><input type="email" class="form-control" id="email" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Phone</label><input type="tel" class="form-control" id="phone"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Company</label><input type="text" class="form-control" id="company"></div>
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
                <button type="button" class="btn btn-primary" onclick="addusers()">Add Users</button>
            </div>
        </div>
    </div>
</div>

<!-- === EDIT USER MODAL ======================================= -->
<div class="modal fade" id="editusersModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editusersForm">
                    <input type="hidden" id="editusersId">
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">First Name</label><input type="text" class="form-control" id="editFirstName" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Last Name</label><input type="text" class="form-control" id="editLastName" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Email</label><input type="email" class="form-control" id="editEmail" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Phone</label><input type="tel" class="form-control" id="editPhone"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Company</label><input type="text" class="form-control" id="editCompany"></div>
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
                <button type="button" class="btn btn-primary" onclick="updateusers()">Update</button>
            </div>
        </div>
    </div>
</div>

<!-- === VIEW USER MODAL ======================================= -->
<div class="modal fade" id="viewusersModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-user me-2"></i>User Details</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body text-center" id="viewusersContent"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="editFromView()">Edit</button>
            </div>
        </div>
    </div>
</div>

<!-- === DELETE CONFIRM MODAL ======================================= -->
<div class="modal fade" id="deleteusersModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body text-center" id="deleteusersContent">
                <i class="fas fa-trash text-danger fa-3x mb-3"></i>
                <p>Are you sure you want to delete this user?</p>
                <p class="text-danger"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- === BUSINESS DETAIL MODAL ═======================================= -->
<div class="modal fade" id="bizDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bizDetailTitle">Business Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="bizDetailBody"></div>
            <div class="modal-footer" id="bizDetailFooter"></div>
        </div>
    </div>
</div>

<!-- Approve confirm -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header"><h6 class="modal-title text-success"><i class="fas fa-check-circle me-2"></i>Approve Business</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body"><p>Approve <strong id="approveBusinessName"></strong>? It will appear on the public map.</p></div>
            <div class="modal-footer">
                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-success btn-sm" id="confirmApproveBtn">Approve</button>
            </div>
        </div>
    </div>
</div>

<!-- Reject modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header"><h6 class="modal-title text-danger"><i class="fas fa-times-circle me-2"></i>Reject Business</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <p>Reject <strong id="rejectBusinessName"></strong>?</p>
                <label class="form-label">Reason <span class="text-danger">*</span></label>
                <textarea id="rejectReason" class="form-control" rows="3" placeholder="Explain the rejection reason…"></textarea>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-danger btn-sm" id="confirmRejectBtn">Reject</button>
            </div>
        </div>
    </div>
</div>

<button class="dark-mode-toggle" id="darkModeToggle"><i class="fas fa-moon" id="darkModeIcon"></i></button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script type="module">
import { db } from './Firebase/firebase_conn.js';
import {
    collection, doc, getDocs, addDoc, updateDoc,
    deleteDoc, serverTimestamp, onSnapshot
} from "https://www.gstatic.com/firebasejs/12.9.0/firebase-firestore.js";

// === Cloudinary =======================================
const CLOUDINARY_CLOUD  = 'dfwe9loex';
const CLOUDINARY_PRESET = 'smartcard';

// === State =======================================
let allusers      = [];
let allBusinesses = [];
let currentusersId  = null;
let currentViewusers = null;
let pendingApproveId = null;
let pendingRejectId  = null;
let bizFilter     = 'all';

// =======================================
// DARK MODE
// =======================================
const darkModeToggle = document.getElementById('darkModeToggle');
const darkModeIcon   = document.getElementById('darkModeIcon');
if (localStorage.getItem('darkMode')==='enabled') {
    document.body.classList.add('dark-mode');
    darkModeIcon.classList.replace('fa-moon','fa-sun');
}
darkModeToggle.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');
    const dark = document.body.classList.contains('dark-mode');
    darkModeIcon.classList.replace(dark?'fa-moon':'fa-sun', dark?'fa-sun':'fa-moon');
    localStorage.setItem('darkMode', dark?'enabled':'disabled');
});

// ══════════════════════════════════════════════════════════════════
// THREE-DOTS MENU
// ══════════════════════════════════════════════════════════════════
const dotsBtn  = document.getElementById('dotsMenuBtn');
const dotsDrop = document.getElementById('dotsDropdown');
dotsBtn.addEventListener('click', e => { e.stopPropagation(); dotsDrop.classList.toggle('show'); });
document.addEventListener('click', () => dotsDrop.classList.remove('show'));

// ══════════════════════════════════════════════════════════════════
// TABS
// ══════════════════════════════════════════════════════════════════
document.querySelectorAll('.admin-tab').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.admin-tab').forEach(t=>t.classList.remove('active'));
        btn.classList.add('active');
        const tab = btn.dataset.tab;
        document.getElementById('tabUsers').style.display      = tab==='users'      ? '' : 'none';
        document.getElementById('tabBusinesses').style.display = tab==='businesses' ? '' : 'none';
    });
});

// ══════════════════════════════════════════════════════════════════
// USERS — load & CRUD
// ══════════════════════════════════════════════════════════════════
async function loadusers() {
    try {
        const snap = await getDocs(collection(db,'users'));
        allusers = [];
        snap.forEach(d => {
            const data = d.data();
            const parts = (data.name||'').trim().split(' ');
            allusers.push({
                id: d.id, ...data,
                firstName: parts[0]||'', lastName: parts.slice(1).join(' ')||''
            });
        });
        displayusers(allusers);
        updateStats();
    } catch(e) {
        console.error(e);
        showToast('Error loading users','error');
        document.getElementById('usersTableContainer').innerHTML =
            `<div class="empty-state"><p class="text-danger">Error: ${e.message}</p></div>`;
    }
}

function displayusers(users) {
    const c = document.getElementById('usersTableContainer');
    if (!users.length) {
        c.innerHTML = `<div class="empty-state"><i class="fas fa-users-slash"></i><h5>No Users Found</h5></div>`;
        return;
    }
    c.innerHTML = `
      <table>
        <thead><tr><th>User</th><th>Contact</th><th>Role</th><th>Join Date</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          ${users.map(u => {
            const init = (u.firstName[0]||'')+(u.lastName[0]||'');
            const date = u.createdAt ? new Date(u.createdAt.toDate()).toLocaleDateString() : '—';
            return `<tr>
              <td>
                <div class="d-flex align-items-center">
                  <div class="users-avatar" style="width:40px;height:40px;font-size:.9rem;" onclick="viewusers('${u.id}')">${init}</div>
                  <div class="ms-3">
                    <div class="fw-bold">${u.firstName} ${u.lastName}</div>
                    <small class="text-muted">${u.company||'No Company'}</small>
                  </div>
                </div>
              </td>
              <td><div>${u.email}</div><small class="text-muted">${u.phone||'No phone'}</small></td>
              <td><span class="status status-resolve">${u.role||'User'}</span></td>
              <td>${date}</td>
              <td><span class="status ${u.approved?'status-resolve':'status-pending'}">${u.approved?'Active':'Pending'}</span></td>
              <td class="action-buttons">
                <button class="btn btn-sm btn-outline-primary me-1" onclick="viewusers('${u.id}')"><i class="fas fa-eye"></i></button>
                <button class="btn btn-sm btn-outline-warning me-1" onclick="editusers('${u.id}')"><i class="fas fa-edit"></i></button>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteusers('${u.id}')"><i class="fas fa-trash"></i></button>
              </td>
            </tr>`;
          }).join('')}
        </tbody>
      </table>`;
}

function updateStats() {
    const total    = allusers.length;
    const active   = allusers.filter(u=>u.status==='Active').length;
    const pending  = allusers.filter(u=>u.status==='pending'||u.approved===false).length;
    const inactive = allusers.filter(u=>u.status!=='Active'&&u.status!=='pending').length;
    document.getElementById('totalusers').textContent    = total;
    document.getElementById('activeusers').textContent   = active;
    document.getElementById('pendingusers').textContent  = pending;
    document.getElementById('inactiveusers').textContent = inactive;
    if (total>0) {
        const ap = Math.round(active/total*100);
        const pp = Math.round(pending/total*100);
        const ip = Math.round(inactive/total*100);
        document.getElementById('activePercent').textContent  = ap+'%';
        document.getElementById('pendingPercent').textContent = pp+'%';
        document.getElementById('inactivePercent').textContent= ip+'%';
        document.getElementById('activeBar').style.width  = ap+'%';
        document.getElementById('pendingBar').style.width = pp+'%';
        document.getElementById('inactiveBar').style.width= ip+'%';
    }
}

window.filterusers = function() {
    const s = document.getElementById('searchInput').value.toLowerCase();
    const f = document.getElementById('statusFilter').value;
    displayusers(allusers.filter(u => {
        const ms = f==='all'||u.status===f;
        const mq = !s||(u.firstName+' '+u.lastName).toLowerCase().includes(s)||u.email.toLowerCase().includes(s)||(u.company||'').toLowerCase().includes(s);
        return ms&&mq;
    }));
};
document.getElementById('searchInput').addEventListener('input', window.filterusers);

window.addusers = async function() {
    const fn = document.getElementById('firstName').value.trim();
    const ln = document.getElementById('lastName').value.trim();
    const em = document.getElementById('email').value.trim();
    if (!fn||!ln||!em) { showToast('Fill required fields','warning'); return; }
    try {
        await addDoc(collection(db,'users'), {
            name: fn+' '+ln, email:em,
            phone: document.getElementById('phone').value,
            company: document.getElementById('company').value,
            status: document.getElementById('status').value,
            createdAt: serverTimestamp()
        });
        showToast('User added!','success');
        bootstrap.Modal.getInstance(document.getElementById('addusersModal')).hide();
        document.getElementById('addusersForm').reset();
        loadusers();
    } catch(e) { showToast('Error: '+e.message,'error'); }
};

window.viewusers = function(id) {
    const u = allusers.find(m=>m.id===id);
    if (!u) return;
    currentViewusers = u; currentusersId = id;
    const init = (u.firstName[0]||'')+(u.lastName[0]||'');
    const date = u.createdAt ? new Date(u.createdAt.toDate()).toLocaleDateString() : '—';
    document.getElementById('viewusersContent').innerHTML = `
      <div class="users-avatar mx-auto mb-3" style="width:80px;height:80px;font-size:1.5rem;">${init}</div>
      <h5>${u.firstName} ${u.lastName}</h5>
      <div class="mt-4 text-start">
        <p><strong>Email:</strong> ${u.email}</p>
        <p><strong>Phone:</strong> ${u.phone||'—'}</p>
        <p><strong>Role:</strong> ${u.role||'User'}</p>
        <p><strong>Join Date:</strong> ${date}</p>
        <p><strong>Company:</strong> ${u.company||'—'}</p>
        <p><strong>Status:</strong> <span class="status ${u.approved?'status-resolve':'status-pending'}">${u.approved?'Active':'Pending'}</span></p>
      </div>`;
    new bootstrap.Modal(document.getElementById('viewusersModal')).show();
};
window.editFromView = function() {
    bootstrap.Modal.getInstance(document.getElementById('viewusersModal')).hide();
    setTimeout(()=>editusers(currentusersId),500);
};
window.editusers = function(id) {
    const u = allusers.find(m=>m.id===id);
    if (!u) return;
    currentusersId = id;
    document.getElementById('editusersId').value  = id;
    document.getElementById('editFirstName').value = u.firstName||'';
    document.getElementById('editLastName').value  = u.lastName||'';
    document.getElementById('editEmail').value     = u.email||'';
    document.getElementById('editPhone').value     = u.phone||'';
    document.getElementById('editCompany').value   = u.company||'';
    document.getElementById('editStatus').value    = u.status||'Pending';
    new bootstrap.Modal(document.getElementById('editusersModal')).show();
};
window.updateusers = async function() {
    const id = document.getElementById('editusersId').value;
    const fn = document.getElementById('editFirstName').value.trim();
    const ln = document.getElementById('editLastName').value.trim();
    const em = document.getElementById('editEmail').value.trim();
    if (!fn||!ln||!em) { showToast('Fill required fields','warning'); return; }
    try {
        await updateDoc(doc(db,'users',id), {
            name: fn+' '+ln, email:em,
            phone: document.getElementById('editPhone').value,
            company: document.getElementById('editCompany').value,
            status: document.getElementById('editStatus').value,
            updatedAt: serverTimestamp()
        });
        showToast('User updated!','success');
        bootstrap.Modal.getInstance(document.getElementById('editusersModal')).hide();
        loadusers();
    } catch(e) { showToast('Error: '+e.message,'error'); }
};
window.deleteusers = function(id) {
    const u = allusers.find(m=>m.id===id);
    if (!u) return;
    currentusersId = id;
    document.getElementById('deleteusersContent').innerHTML = `
      <i class="fas fa-trash text-danger fa-3x mb-3"></i>
      <p>Delete <strong>${u.firstName} ${u.lastName}</strong>?</p>
      <p class="text-danger"><small>This cannot be undone.</small></p>`;
    new bootstrap.Modal(document.getElementById('deleteusersModal')).show();
};
document.getElementById('confirmDeleteBtn').addEventListener('click', async () => {
    if (!currentusersId) return;
    try {
        await deleteDoc(doc(db,'users',currentusersId));
        showToast('User deleted!','success');
        bootstrap.Modal.getInstance(document.getElementById('deleteusersModal')).hide();
        loadusers();
    } catch(e) { showToast('Error: '+e.message,'error'); }
});
window.exportUsers = function() {
    const a = document.createElement('a');
    a.href = 'data:application/json;charset=utf-8,'+encodeURIComponent(JSON.stringify(allusers,null,2));
    a.download = 'cboc-users.json';
    a.click();
    showToast('Exported!','success');
};
window.refreshusers = loadusers;

// ══════════════════════════════════════════════════════════════════
// BUSINESSES — real-time listener on 'businesses' collection
// ══════════════════════════════════════════════════════════════════
function subscribeBusinesses() {
    onSnapshot(collection(db,'businesses'), snap => {
        allBusinesses = snap.docs.map(d => {
            const data = d.data();
            return {
                id: d.id,
                uid: data.uid||'',
                name: data.name||'Unnamed',
                desc: data.desc||'',
                address: data.address||'',
                phone: data.phone||'',
                logoUrl: data.logoUrl||null,
                dtiDocumentUrl: data.dtiDocumentUrl||null,
                dtiFileName: data.dtiFileName||null,
                status: data.status||'pending',
                rejectionReason: data.rejectionReason||null,
                submittedAt: data.submittedAt,
                userName: data.userName||'',
            };
        });

        const pending  = allBusinesses.filter(b=>b.status==='pending').length;
        const approved = allBusinesses.filter(b=>b.status==='approved').length;
        const rejected = allBusinesses.filter(b=>b.status==='rejected').length;
        document.getElementById('pendingBizCount').textContent  = pending;
        document.getElementById('bizTabBadge').textContent      = pending;
        document.getElementById('bizApprovedCount').textContent = approved;
        document.getElementById('bizPendingCount').textContent  = pending;
        document.getElementById('bizRejectedCount').textContent = rejected;

        renderBizList();
    });
}

function renderBizList() {
    const search = (document.getElementById('bizSearch')?.value||'').toLowerCase();
    const list   = document.getElementById('bizApprovalList');

    const filtered = allBusinesses.filter(b => {
        const ms = bizFilter==='all' || b.status===bizFilter;
        const mq = !search || b.name.toLowerCase().includes(search) || b.address.toLowerCase().includes(search);
        return ms&&mq;
    }).sort((a,b) => {
        // pending first
        const order = {pending:0,approved:1,rejected:2};
        return (order[a.status]||0)-(order[b.status]||0);
    });

    if (!filtered.length) {
        list.innerHTML = '<div class="empty-state"><i class="fas fa-store-slash"></i><p>No businesses found.</p></div>';
        return;
    }

    list.innerHTML = filtered.map(biz => {
        const badge = {
            approved: '<span class="badge-approved">✅ Approved</span>',
            pending:  '<span class="badge-pending">⏳ Pending Review</span>',
            rejected: '<span class="badge-rejected">❌ Rejected</span>',
        }[biz.status]||'';

        const logoHtml = biz.logoUrl
            ? `<img class="biz-logo-sm" src="${biz.logoUrl}" onerror="this.outerHTML='<div class=biz-logo-placeholder-sm>${biz.name.charAt(0)}</div>'">`
            : `<div class="biz-logo-placeholder-sm">${biz.name.charAt(0).toUpperCase()}</div>`;

        const dtiHtml = biz.dtiDocumentUrl
            ? `<a href="${biz.dtiDocumentUrl}" target="_blank" class="dti-btn me-2">
                 <i class="fas fa-file-alt"></i> ${biz.dtiFileName||'DTI Document'}
               </a>`
            : `<span class="text-muted" style="font-size:.82rem;"><i class="fas fa-times-circle me-1 text-danger"></i>No DTI attached</span>`;

        const date = biz.submittedAt
            ? new Date(biz.submittedAt.seconds*1000).toLocaleDateString('en-PH')
            : '—';

        const actionBtns = biz.status==='pending'
            ? `<button class="btn btn-sm btn-success" onclick="promptApprove('${biz.id}','${biz.name.replace(/'/g,"\\'")}')"><i class="fas fa-check me-1"></i>Approve</button>
               <button class="btn btn-sm btn-danger ms-2" onclick="promptReject('${biz.id}','${biz.name.replace(/'/g,"\\'")}')"><i class="fas fa-times me-1"></i>Reject</button>`
            : biz.status==='approved'
            ? `<button class="btn btn-sm btn-outline-danger" onclick="promptReject('${biz.id}','${biz.name.replace(/'/g,"\\'")}')"><i class="fas fa-ban me-1"></i>Revoke</button>`
            : `<button class="btn btn-sm btn-outline-success" onclick="promptApprove('${biz.id}','${biz.name.replace(/'/g,"\\'")}')"><i class="fas fa-check me-1"></i>Approve Instead</button>`;

        return `
          <div class="biz-approval-card status-${biz.status}">
            <div class="d-flex align-items-start gap-3">
              ${logoHtml}
              <div style="flex:1;min-width:0;">
                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                  <span class="fw-bold" style="font-size:1rem;">${biz.name}</span>
                  ${badge}
                </div>
                <div class="text-muted" style="font-size:.85rem;">
                  <i class="fas fa-user me-1"></i>${biz.userName||'—'} &nbsp;|&nbsp;
                  <i class="fas fa-calendar me-1"></i>Submitted ${date}
                </div>
                <div class="text-muted mt-1" style="font-size:.85rem;">
                  <i class="fas fa-map-marker-alt me-1"></i>${biz.address||'—'}
                  ${biz.phone?`&nbsp;|&nbsp;<i class="fas fa-phone me-1"></i>${biz.phone}`:''}
                </div>
                ${biz.desc?`<div class="mt-1 text-muted" style="font-size:.82rem;">${biz.desc}</div>`:''}
                ${biz.status==='rejected'&&biz.rejectionReason?`<div style="margin-top:8px;background:#fee2e2;border-radius:6px;padding:6px 10px;font-size:.82rem;color:#991b1b;"><i class="fas fa-exclamation-circle me-1"></i>${biz.rejectionReason}</div>`:''}
                <div class="mt-2 d-flex align-items-center gap-2 flex-wrap">
                  ${dtiHtml}
                  <button class="btn btn-sm btn-outline-secondary" onclick="showBizDetail('${biz.id}')">
                    <i class="fas fa-external-link-alt me-1"></i>Full Details
                  </button>
                </div>
                <div class="mt-2">${actionBtns}</div>
              </div>
            </div>
          </div>`;
    }).join('');
}

document.getElementById('bizSearch').addEventListener('input', renderBizList);
document.querySelectorAll('[data-biz-filter]').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('[data-biz-filter]').forEach(p=>p.classList.remove('active'));
        this.classList.add('active');
        bizFilter = this.dataset.bizFilter;
        renderBizList();
    });
});

// ── Business detail modal ────────────────────────────────────────
window.showBizDetail = function(id) {
    const biz = allBusinesses.find(b=>b.id===id);
    if (!biz) return;

    const statusBadge = {
        approved: '<span class="badge-approved" style="font-size:.85rem;padding:4px 12px;">✅ Approved</span>',
        pending:  '<span class="badge-pending"  style="font-size:.85rem;padding:4px 12px;">⏳ Pending</span>',
        rejected: '<span class="badge-rejected" style="font-size:.85rem;padding:4px 12px;">❌ Rejected</span>',
    }[biz.status]||biz.status;

    const logoHtml = biz.logoUrl
        ? `<img src="${biz.logoUrl}" style="width:68px;height:68px;border-radius:50%;object-fit:cover;border:3px solid #B71C1C;">`
        : `<div style="width:68px;height:68px;border-radius:50%;background:#fce4e4;display:flex;align-items:center;justify-content:center;border:3px solid #B71C1C;font-size:26px;font-weight:700;color:#B71C1C;">${biz.name.charAt(0)}</div>`;

    const dtiHtml = biz.dtiDocumentUrl
        ? `<a href="${biz.dtiDocumentUrl}" target="_blank" class="dti-btn">
             <i class="fas fa-file-alt"></i> View DTI — ${biz.dtiFileName||'Document'}
           </a>`
        : `<span class="text-muted" style="font-size:.9rem;"><i class="fas fa-times-circle text-danger me-1"></i>No DTI document</span>`;

    const date = biz.submittedAt
        ? new Date(biz.submittedAt.seconds*1000).toLocaleDateString('en-PH',{year:'numeric',month:'long',day:'numeric'})
        : '—';

    document.getElementById('bizDetailTitle').textContent = biz.name;
    document.getElementById('bizDetailBody').innerHTML = `
      <div class="d-flex gap-3 align-items-center mb-4">
        ${logoHtml}
        <div>
          <div style="font-size:1.15rem;font-weight:700;">${biz.name}</div>
          <div class="text-muted" style="font-size:.85rem;">Owner: ${biz.userName||'—'}</div>
          <div class="mt-1">${statusBadge}</div>
        </div>
      </div>
      <div class="mb-2"><i class="fas fa-align-left me-2" style="color:#B71C1C;"></i>${biz.desc||'—'}</div>
      <div class="mb-2"><i class="fas fa-map-marker-alt me-2" style="color:#B71C1C;"></i>${biz.address||'—'}</div>
      <div class="mb-2"><i class="fas fa-phone me-2" style="color:#B71C1C;"></i>${biz.phone||'—'}</div>
      <div class="mb-3"><i class="fas fa-calendar me-2" style="color:#B71C1C;"></i>Submitted: ${date}</div>
      <hr>
      <div class="fw-600 mb-2"><i class="fas fa-file-contract me-2" style="color:#B71C1C;"></i>DTI Registration Document</div>
      ${dtiHtml}
      ${biz.status==='rejected'&&biz.rejectionReason?`
        <div style="margin-top:14px;background:#fee2e2;border:1px solid #fca5a5;border-radius:8px;padding:12px;">
          <div style="font-weight:600;color:#991b1b;margin-bottom:4px;"><i class="fas fa-exclamation-circle me-1"></i>Rejection Reason</div>
          <div style="color:#991b1b;">${biz.rejectionReason}</div>
        </div>`:''}
    `;

    let footerHtml = `<button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>`;
    if (biz.status==='pending') {
        footerHtml += `
          <button class="btn btn-danger" onclick="bootstrap.Modal.getInstance(document.getElementById('bizDetailModal')).hide();setTimeout(()=>promptReject('${biz.id}','${biz.name.replace(/'/g,"\\'")}'),350)">
            <i class="fas fa-times me-1"></i>Reject
          </button>
          <button class="btn btn-success" onclick="bootstrap.Modal.getInstance(document.getElementById('bizDetailModal')).hide();setTimeout(()=>promptApprove('${biz.id}','${biz.name.replace(/'/g,"\\'")}'),350)">
            <i class="fas fa-check me-1"></i>Approve
          </button>`;
    } else if (biz.status==='approved') {
        footerHtml += `<button class="btn btn-outline-danger" onclick="bootstrap.Modal.getInstance(document.getElementById('bizDetailModal')).hide();setTimeout(()=>promptReject('${biz.id}','${biz.name.replace(/'/g,"\\'")}'),350)"><i class="fas fa-ban me-1"></i>Revoke</button>`;
    } else {
        footerHtml += `<button class="btn btn-success" onclick="bootstrap.Modal.getInstance(document.getElementById('bizDetailModal')).hide();setTimeout(()=>promptApprove('${biz.id}','${biz.name.replace(/'/g,"\\'")}'),350)"><i class="fas fa-check me-1"></i>Approve</button>`;
    }
    document.getElementById('bizDetailFooter').innerHTML = footerHtml;
    new bootstrap.Modal(document.getElementById('bizDetailModal')).show();
};

// ── Approve / Reject ─────────────────────────────────────────────
window.promptApprove = function(id, name) {
    pendingApproveId = id;
    document.getElementById('approveBusinessName').textContent = name;
    new bootstrap.Modal(document.getElementById('approveModal')).show();
};
window.promptReject = function(id, name) {
    pendingRejectId = id;
    document.getElementById('rejectBusinessName').textContent = name;
    document.getElementById('rejectReason').value = '';
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
};

document.getElementById('confirmApproveBtn').addEventListener('click', async () => {
    if (!pendingApproveId) return;
    try {
        await updateDoc(doc(db,'businesses',pendingApproveId), {
            status: 'approved',
            approvedAt: serverTimestamp(),
            rejectionReason: null
        });
        // Push in-app notification to owner
        const biz = allBusinesses.find(b=>b.id===pendingApproveId);
        if (biz?.uid) {
            await addDoc(collection(db,'user_notifications'), {
                uid: biz.uid,
                type: 'business_approved',
                title: 'Business Approved! 🎉',
                body: `"${biz.name}" has been approved and is now visible on the map.`,
                businessId: pendingApproveId,
                read: false,
                createdAt: serverTimestamp()
            });
        }
        bootstrap.Modal.getInstance(document.getElementById('approveModal')).hide();
        showToast('Business approved!','success');
    } catch(e) { showToast('Error: '+e.message,'error'); }
    pendingApproveId = null;
});

document.getElementById('confirmRejectBtn').addEventListener('click', async () => {
    const reason = document.getElementById('rejectReason').value.trim();
    if (!reason) { document.getElementById('rejectReason').classList.add('is-invalid'); return; }
    document.getElementById('rejectReason').classList.remove('is-invalid');
    if (!pendingRejectId) return;
    try {
        await updateDoc(doc(db,'businesses',pendingRejectId), {
            status: 'rejected',
            rejectionReason: reason,
            rejectedAt: serverTimestamp()
        });
        const biz = allBusinesses.find(b=>b.id===pendingRejectId);
        if (biz?.uid) {
            await addDoc(collection(db,'user_notifications'), {
                uid: biz.uid,
                type: 'business_rejected',
                title: 'Business Submission Rejected',
                body: `"${biz.name}" was not approved. Reason: ${reason}`,
                businessId: pendingRejectId,
                read: false,
                createdAt: serverTimestamp()
            });
        }
        bootstrap.Modal.getInstance(document.getElementById('rejectModal')).hide();
        showToast('Business rejected.','warning');
    } catch(e) { showToast('Error: '+e.message,'error'); }
    pendingRejectId = null;
});

// ── Toast ────────────────────────────────────────────────────────
function showToast(msg, type='info') {
    const c = document.getElementById('toastContainer');
    const id = 'toast-'+Date.now();
    const bgMap = {success:'bg-success',warning:'bg-warning',error:'bg-danger',info:'bg-primary'};
    const iconMap = {success:'fa-check-circle',warning:'fa-exclamation-triangle',error:'fa-times-circle',info:'fa-info-circle'};
    const el = document.createElement('div');
    el.id = id;
    el.className = `toast show align-items-center text-white ${bgMap[type]||'bg-primary'} border-0`;
    el.setAttribute('role','alert');
    el.innerHTML = `<div class="d-flex"><div class="toast-body"><i class="fas ${iconMap[type]||'fa-info-circle'} me-2"></i>${msg}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>`;
    c.appendChild(el);
    setTimeout(()=>el.remove(),3500);
}

// ── Init ─────────────────────────────────────────────────────────
loadusers();
subscribeBusinesses();
</script>
</body>
</html>