/**
 * Dahil iba yung structure, di to pwede ilagay sa backend.js
 * -Arjon
 */
import { db } from '../Firebase/firebase_conn.js';
import { 
    collection, 
    doc, 
    getDocs, 
    addDoc, 
    updateDoc, 
    deleteDoc, 
    serverTimestamp 
} from "https://www.gstatic.com/firebasejs/12.9.0/firebase-firestore.js";

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

function saveFieldConfig() {
    document.querySelectorAll('.config-enabled').forEach(cb => { if (fieldConfigs[cb.dataset.field]) fieldConfigs[cb.dataset.field].enabled = cb.checked; });
    document.querySelectorAll('.config-required').forEach(cb => { if (fieldConfigs[cb.dataset.field] && !cb.disabled) fieldConfigs[cb.dataset.field].required = cb.checked; });
    saveFieldConfigToLocal();
    renderDynamicForm();
    bootstrap.Modal.getInstance(document.getElementById('configModal')).hide();
    showToast('Form configuration updated!', 'success');
};

function resetFieldConfig() {
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
        attachMemberRowListeners();
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
        html += `<tr data-member-id="${member.id}">
            <td>
                <small class="text-muted uid-copy" style="cursor:pointer">
                    ${member.id.substring(0,8)}...
                </small>
            </td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="users-avatar" style="width:40px;height:40px;font-size:0.9rem;">
                        ${initials}
                    </div>
                    <div class="ms-3">
                        <div class="fw-bold">${escapeHtml(member.firstName)} ${escapeHtml(member.lastName)}</div>
                        <small class="text-muted">${escapeHtml(member.company || 'No Company')}</small>
                    </div>
                </div>
            </td>
            <td>
                <div>${escapeHtml(member.email)}</div>
                <small class="text-muted">${escapeHtml(member.phone || 'No phone')}</small>
            </td>
            <td>
                <span class="badge bg-primary">${member.attendanceCount || 0}</span> events<br>
                <small class="text-muted">Joined: ${joinDate}</small>
            </td>
            <td>
                <span class="member-status ${statusClass}">${statusIcon} ${member.status || 'Pending'}</span>
            </td>
            <td class="action-buttons">
                <button class="btn btn-sm btn-outline-success me-1 record-attendance" title="Record Attendance"><i class="fas fa-calendar-check"></i></button>
                <button class="btn btn-sm btn-outline-primary me-1 view-user"><i class="fas fa-eye"></i></button>
                <button class="btn btn-sm btn-outline-warning me-1 edit-user"><i class="fas fa-edit"></i></button>
                <!-- <button class="btn btn-sm btn-outline-danger delete-user"><i class="fas fa-trash"></i></button> -->
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

async function saveAttendance() {
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
async function addusers() {
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

async function updateusers() {
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

async function editFromView() {
    bootstrap.Modal.getInstance(document.getElementById('viewusersModal')).hide();
    setTimeout(() => {
        editusers(currentusersId);
    }, 500);
};

function filterusers() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    let statusFilter = document.getElementById('statusFilter').value;
    const filtered = allusers.filter(member => {
        const matchesSearch = (member.firstName?.toLowerCase() || '').includes(searchTerm) || (member.lastName?.toLowerCase() || '').includes(searchTerm) || (member.email?.toLowerCase() || '').includes(searchTerm) || (member.company?.toLowerCase() || '').includes(searchTerm);
        const matchesStatus = statusFilter === 'all' || member.status === statusFilter;
        return matchesSearch && matchesStatus;
    });
    displayusers(filtered);
};

window.showToast = function(message, type) {
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

function exportUsers() {
    if (allusers.length === 0) { showToast('No members to export', 'warning'); return; }
    const dataStr = JSON.stringify(allusers, null, 2);
    const link = document.createElement('a');
    link.setAttribute('href', 'data:application/json;charset=utf-8,' + encodeURIComponent(dataStr));
    link.setAttribute('download', 'cboc-members-export.json');
    link.click();
    showToast('Members exported successfully!', 'success');
};

function printUsers() {
    window.print();
};

function showHelp() {
    alert(`Member Management Help:
- Member status is AUTOMATICALLY updated based on event attendance:
• ACTIVE: Attended 3 or more events
• PENDING: Attended 1-2 events  
• INACTIVE: No event attendance
- Click the calendar button (📅) to record attendance for a member
- Each attendance record includes event name and date
- Status updates in real-time as attendance is recorded`);
};

async function refreshusers() {
    try {
        await loadusers();
        showToast("Members refreshed", "success");
    } catch (error) {
        console.error(error);
        showToast("Failed to refresh members", "danger");
    }
};

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

function attachMemberRowListeners() {
    document.querySelectorAll('tr[data-member-id]').forEach(row => {
        const memberId = row.getAttribute('data-member-id');

        // UID copy
        const uidCopy = row.querySelector('.uid-copy');
        if (uidCopy) {
            uidCopy.addEventListener('click', () => {
                navigator.clipboard.writeText(memberId);
                showToast(`UID: ${memberId} copied to clipboard!`, 'success');
            });
        }

        // Avatar click → view user
        const avatar = row.querySelector('.users-avatar');
        if (avatar) {
            avatar.addEventListener('click', () => viewusers(memberId));
        }

        // Record Attendance button
        const recordBtn = row.querySelector('.record-attendance');
        if (recordBtn) {
            recordBtn.addEventListener('click', () => recordAttendance(memberId));
        }

        // View User button
        const viewBtn = row.querySelector('.view-user');
        if (viewBtn) {
            viewBtn.addEventListener('click', () => viewusers(memberId));
        }

        // Edit User button
        const editBtn = row.querySelector('.edit-user');
        if (editBtn) {
            editBtn.addEventListener('click', () => editusers(memberId));
        }

        // Optional: Delete User button
        // const deleteBtn = row.querySelector('.delete-user');
        // if (deleteBtn) {
        //     deleteBtn.addEventListener('click', () => deleteusers(memberId));
        // }
    });
}

document.getElementById("exportUsers").addEventListener("click", exportUsers);
document.getElementById("printUsers").addEventListener("click", printUsers);
document.getElementById("refreshusers").addEventListener("click", refreshusers);
document.getElementById("showHelp").addEventListener("click", showHelp);
document.getElementById("saveFieldConfig").addEventListener("click", saveFieldConfig);
document.getElementById("resetFieldConfig").addEventListener("click", resetFieldConfig);
document.getElementById("addusers").addEventListener("click", addusers);
document.getElementById("saveAttendance").addEventListener("click", saveAttendance);
document.getElementById("updateusers").addEventListener("click", updateusers);
document.getElementById("editFromView").addEventListener("click", editFromView);
document.getElementById("filterusers").addEventListener("change", filterusers);