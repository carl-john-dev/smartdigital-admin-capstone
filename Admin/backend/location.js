/**
 * Dahil iba yung structure, di to pwede ilagay sa backend.js
 * -Arjon
 */
import { getDefaultProfilePic } from "./backend.js";
import { db } from "../Firebase/firebase_conn.js";
import { 
    doc, 
    collection, 
    addDoc, 
    updateDoc, 
    serverTimestamp, 
    onSnapshot 
} from "https://www.gstatic.com/firebasejs/12.9.0/firebase-firestore.js";
// ─────────────────────────────────────────────────────────────────────

// ── Cloudinary config — same cloud name / preset as the Flutter app ──
const CLOUDINARY_CLOUD  = 'dfwe9loex';
const CLOUDINARY_PRESET = 'smartcard';

document.addEventListener('DOMContentLoaded', function () {

    // ── Inititialize ─────────────────────────────────────────────────────────
    let users              = [];   // all businesses from 'businesses' collection
    let userMarkers        = [];
    let userMarkersVisible = true;
    let userToDelete       = null;
    let userToEdit         = null;
    let uploadedImages     = {};
    let previewMarker      = null;
    let activeFilter       = 'all';   // NEW: which filter pill is active
    let pendingApproveId   = null;    // NEW: business id awaiting approval
    let pendingRejectId    = null;    // NEW: business id awaiting rejection
    const defPFP = getDefaultProfilePic();

    // NEW: load from the 'businesses' collection (not users.businesses array)
    loadBusinessFromFirebase();
    renderUserCards();

    // Three Dots Menu Functions
    function exportLocations() {
        const usersData = users.map(u => ({
            name: u.name,
            email: u.email,
            address: u.address,
            coordinates: u.coords,
            status: u.status,
            role: u.role
        }));
        
        const dataStr = JSON.stringify(usersData, null, 2);
        const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
        const exportFileDefaultName = 'cboc-locations-export.json';
        
        const linkElement = document.createElement('a');
        linkElement.setAttribute('href', dataUri);
        linkElement.setAttribute('download', exportFileDefaultName);
        linkElement.click();
        
        showNotification('Locations exported successfully!', 'success');
    };

    function printMap() {
        window.print();
    };

    function refreshMap() {
        location.reload();
    };

    function showMapHelp() {
        alert(`
Location Map Help:
- Click on user markers to view details
- Use "Locate Me" to find your position
- Search users by name, email, or address
- Click on user cards to focus on map
- Use + button to add new users
- Toggle dark mode using moon/sun button
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

    // ── Map setup ─────────────────────────────────────────────────────
    const mapEl = document.getElementById('map');
    if (!mapEl.style.height) mapEl.style.height = '600px';

    const map = L.map('map', { center: [14.4160, 120.8541], zoom: 14, zoomControl: true });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map);

    // Force Leaflet to recalculate container size after DOM paint
    setTimeout(() => map.invalidateSize(), 300);

    // ── Marker icon factory ───────────────────────────────────────────
    // NOTE: pin border color now reflects business approval status
    // so admin can distinguish approved (green) / pending (amber) / rejected (red) at a glance
    const markerIcon = (status, picUrl) => {
        const borderColor = status === 'approved' ? '#10b981'
                            : status === 'rejected' ? '#ef4444'
                            : '#f59e0b'; // pending = amber
        return L.divIcon({
            html: `<div class="profile-pic-marker ${status.toLowerCase()}" style="background-image:url('${picUrl}');border-color:${borderColor}"></div>`,
            className: 'custom-div-icon',
            iconSize: [40, 40],
            iconAnchor: [20, 20]
        });
    };

    // ── Subscribe to real-time Firestore updates ──────────────────────
    // subscribeToUsers((updatedUsers) => {
    //     users = updatedUsers;
    //     renderMarkers();
    //     renderUserCards();
    //     updateStatistics();
    // });

    // ── Loads businesses from Firebase DB ─────────────────────────────
    // CHANGED: now reads from the top-level 'businesses' collection.
    // The Flutter app writes each submitted business as a separate document
    // with fields: name, desc, address, phone, logoUrl, dtiDocumentUrl,
    // dtiFileName, lat, lng, status ('pending'|'approved'|'rejected'), uid.
    // Previously this read from users.businesses (array inside user docs)
    // which was the wrong collection for the new data model.
    function loadBusinessFromFirebase() {
        onSnapshot(collection(db, "businesses"), (snapshot) => {
            try {
                users.length = 0; // clear array before repopulating

                snapshot.forEach((bizDoc) => {
                    const data = bizDoc.data();

                    // Only plot businesses that have valid lat/lng coordinates
                    if (
                        typeof data.lat !== "number" ||
                        typeof data.lng !== "number"
                    ) return;

                    users.push({
                        id:              bizDoc.id,
                        uid:             data.uid             || '',
                        name:            data.name            || "Unnamed Business",
                        address:         data.address         || "",
                        description:     data.desc            || "",
                        phone:           data.phone           || "",
                        logoUrl:         data.logoUrl         || "",
                        dtiUrl:          data.dtiDocumentUrl  || null,  // Cloudinary secure_url
                        dtiFileName:     data.dtiFileName     || null,
                        status:          data.status          || "pending",
                        rejectionReason: data.rejectionReason || null,
                        userName:        data.userName        || "",
                        submittedAt:     data.submittedAt     || null,
                        coords: [data.lat, data.lng]
                    });
                });

                renderMarkers();
                renderUserCards();
                updateStatistics();
                console.log("Loaded businesses:", users);

            } catch (error) {
                console.error("Error loading businesses:", error);
            }
        });
    }

    // ── Render map markers ────────────────────────────────────────────
    function renderMarkers() {
        // Remove old markers
        userMarkers.forEach(({ marker }) => {
            if (marker) map.removeLayer(marker);
        });
        userMarkers = [];

        users.forEach(business => {
            if (!business.coords || business.coords.length !== 2) return;

            // Human-readable status label shown in the popup
            const statusLabel = business.status === 'approved' ? '✅ Approved'
                                : business.status === 'rejected' ? '❌ Rejected'
                                : '⏳ Pending Review';

            const marker = L.marker(business.coords, {
                icon: markerIcon(business.status || "pending", business.logoUrl || defPFP)
            }).bindPopup(`
                <div class="user-popup" data-biz-id="${business.id}">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="user-profile-pic"
                            style="background-image:url('${business.logoUrl || defPFP}')">
                        </div>
                        <div>
                            <h5 class="mb-0">${business.name}</h5>
                            <small>${business.phone || "No phone available"}</small>
                            <div style="margin-top:3px;font-size:.78rem;">${statusLabel}</div>
                        </div>
                    </div>

                    <hr class="my-2">

                    <p class="mb-1">
                        <strong>Address:</strong> ${business.address || "No address provided"}
                    </p>

                    <p class="mb-1">
                        <strong>Description:</strong><br>
                        ${business.description || "No description available"}
                    </p>

                    <div class="d-flex gap-2 mt-2">
                        <button class="btn btn-sm btn-primary w-100 btn-view-map"> 
                            <i class="fas fa-map-marker-alt"></i> View
                        </button>
                        <button class="btn btn-sm btn-outline-secondary w-100 btn-details">
                            <i class="fas fa-info-circle"></i> Details
                        </button>
                    </div>
                </div>
            `);

            // Attach event listeners when popup opens
            marker.on('popupopen', function(e) {
                const popupEl = e.popup.getElement();
                const bizId = business.id;

                const viewBtn = popupEl.querySelector('.btn-view-map');
                const detailBtn = popupEl.querySelector('.btn-details');

                if (viewBtn) viewBtn.addEventListener('click', () => focusUserOnMap(bizId));
                if (detailBtn) detailBtn.addEventListener('click', () => window._openBizDetail(bizId));
            });

            if (userMarkersVisible) marker.addTo(map);

            userMarkers.push({ id: business.id, marker, user: business });
        });
    }

    // ── Render sidebar user cards ─────────────────────────────────────
    function renderUserCards() {
        const userListContainer = document.getElementById('userLocationsList');
        userListContainer.innerHTML = '';

        const searchTerm = document.getElementById('searchUsers').value.toLowerCase();

        // Filter by active pill AND search term
        const filtered = users.filter(b => {
            const matchFilter = activeFilter === 'all' || b.status === activeFilter;
            const matchSearch = !searchTerm
                || b.name.toLowerCase().includes(searchTerm)
                || b.address.toLowerCase().includes(searchTerm)
                || b.phone.includes(searchTerm);
            return matchFilter && matchSearch;
        });

        if (filtered.length === 0) {
            userListContainer.innerHTML = '<div class="text-center text-muted py-4" style="font-size:.9rem;">No businesses match.</div>';
            return;
        }

        filtered.forEach(business => {
            const card = document.createElement('div');
            card.className = 'user-card';
            card.setAttribute('data-user-id', business.id);

            // Status badge
            const badge = business.status === 'approved'
                ? '<span class="badge-approved">Approved</span>'
                : business.status === 'rejected'
                ? '<span class="badge-rejected">Rejected</span>'
                : '<span class="badge-pending">Pending</span>';

            card.innerHTML = `
                <div class="user-card-actions">
                </div>

                <div class="d-flex justify-content-between align-items-start">
                    <div style="flex:1;min-width:0;">
                        <div class="user-card-title">${business.name}</div>
                        <div class="user-card-address">${business.address || "No address provided"}</div>

                        <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                            <span class="role-badge">
                                ${business.phone || "No phone"}
                            </span>
                            ${badge}
                        </div>
                    </div>

                    <div class="user-profile-pic"
                        style="background-image: url('${business.logoUrl || defPFP}');flex-shrink:0;">
                    </div>
                </div>

                <div class="mt-2">
                    <small class="text-muted">
                        ${business.description || "No description available"}
                    </small>

                    <div class="d-flex gap-1 mt-2">
                        <button class="btn btn-sm btn-outline-primary flex-fill btn-view-map">
                            <i class="fas fa-map-marker-alt"></i> View on Map
                        </button>
                        <button class="btn btn-sm btn-outline-secondary flex-fill btn-details">
                            <i class="fas fa-info-circle"></i> Details
                        </button>
                    </div>
                </div>
            `;

            // Attach event listeners after the card is added to the DOM
            const viewBtn = card.querySelector('.btn-view-map');
            const detailBtn = card.querySelector('.btn-details');

            if (viewBtn) viewBtn.addEventListener('click', () => focusUserOnMap(business.id));
            if (detailBtn) detailBtn.addEventListener('click', () => window._openBizDetail(business.id));

            card.addEventListener('click', function(e) {
                if (!e.target.closest('button')) {
                    focusUserOnMap(business.id);
                }
            });

            userListContainer.appendChild(card);
        });
    }

    // ── Update stats counters ─────────────────────────────────────────
    function updateStatistics() {
        document.getElementById('totalUsers').textContent    = users.length;
        document.getElementById('activeUsers').textContent   = users.filter(u => u.status === 'approved').length;
        document.getElementById('inactiveUsers').textContent = users.filter(u => u.status === 'rejected').length;
        document.getElementById('pendingUsers').textContent  = users.filter(u => u.status === 'pending').length;
    }

    // ── Focus map on a user ───────────────────────────────────────────
    window.focusUserOnMap = async function (userId) {
        const user = users.find(u => u.id === userId);
        if (!user || !user.coords || user.coords.length !== 2) return;

        // Move map
        map.flyTo(user.coords, 16, { animate: true });

        // Open marker popup
        const userMarker = userMarkers.find(m => m.id === userId);
        if (userMarker?.marker) {
            userMarker.marker.openPopup();
        }

        // Highlight user card (if exists)
        document.querySelectorAll('.user-card').forEach(card => {
            card.classList.remove('active');
        });

        const card = document.querySelector(`[data-user-id="${userId}"]`);
        if (card) card.classList.add('active');
    };

    // ── NEW: Open business detail modal ───────────────────────────────
    // Renders full business info, DTI document link (Cloudinary URL opens
    // in a new tab), and Approve / Reject / Revoke buttons for admin.
    window._openBizDetail = function(bizId) {
        const biz = users.find(b => b.id === bizId);
        if (!biz) return;

        const statusBadge = biz.status === 'approved'
            ? '<span class="badge-approved" style="font-size:.85rem;padding:4px 12px;">✅ Approved</span>'
            : biz.status === 'rejected'
            ? '<span class="badge-rejected" style="font-size:.85rem;padding:4px 12px;">❌ Rejected</span>'
            : '<span class="badge-pending" style="font-size:.85rem;padding:4px 12px;">⏳ Pending</span>';

        const logoHtml = biz.logoUrl
            ? `<img src="${biz.logoUrl}" style="width:64px;height:64px;border-radius:50%;object-fit:cover;border:3px solid #B71C1C;">`
            : `<div style="width:64px;height:64px;border-radius:50%;background:#fce4e4;display:flex;align-items:center;justify-content:center;border:3px solid #B71C1C;font-size:24px;font-weight:700;color:#B71C1C;">${biz.name.charAt(0)}</div>`;

        // DTI document is stored as a Cloudinary secure_url —
        // no download needed, just open in a new browser tab
        const dtiHtml = biz.dtiUrl
            ? `<a href="${biz.dtiUrl}" target="_blank" class="dti-btn">
                    <i class="fas fa-file-alt"></i> View DTI Document${biz.dtiFileName ? ' — ' + biz.dtiFileName : ''}
                </a>`
            : `<span class="text-muted" style="font-size:.85rem;"><i class="fas fa-times-circle text-danger me-1"></i>No DTI document attached</span>`;

        const submittedDate = biz.submittedAt
            ? new Date(biz.submittedAt.seconds * 1000).toLocaleDateString('en-PH', {year:'numeric',month:'long',day:'numeric'})
            : '—';

        document.getElementById('bizDetailTitle').textContent = biz.name;
        document.getElementById('bizDetailBody').innerHTML = `
            <div class="d-flex align-items-center gap-3 mb-3">
                ${logoHtml}
                <div>
                    <div style="font-size:1.1rem;font-weight:700;">${biz.name}</div>
                    <div class="text-muted" style="font-size:.85rem;">Owner: ${biz.userName || '—'}</div>
                    <div class="mt-1">${statusBadge}</div>
                </div>
            </div>
            <div class="mb-2"><i class="fas fa-align-left me-2" style="color:#B71C1C;"></i>${biz.description || '—'}</div>
            <div class="mb-2"><i class="fas fa-map-marker-alt me-2" style="color:#B71C1C;"></i>${biz.address || '—'}</div>
            <div class="mb-2"><i class="fas fa-phone me-2" style="color:#B71C1C;"></i>${biz.phone || '—'}</div>
            <div class="mb-3"><i class="fas fa-calendar me-2" style="color:#B71C1C;"></i>Submitted: ${submittedDate}</div>
            <hr>
            <div class="fw-bold mb-2"><i class="fas fa-file-contract me-2" style="color:#B71C1C;"></i>DTI Registration Document</div>
            ${dtiHtml}
            ${biz.status === 'rejected' && biz.rejectionReason ? `
                <div style="margin-top:12px;background:#fee2e2;border:1px solid #fca5a5;border-radius:8px;padding:10px;">
                    <div style="font-weight:600;color:#991b1b;margin-bottom:4px;"><i class="fas fa-exclamation-circle me-1"></i>Rejection Reason</div>
                    <div style="color:#991b1b;">${biz.rejectionReason}</div>
                </div>` : ''}
        `;

        // Footer buttons depend on the current status. Start with the Close button
        const footer = document.getElementById('bizDetailFooter');
        footer.innerHTML = `<button class="btn btn-secondary btn-close-modal" data-bs-dismiss="modal">Close</button>`;

        // Add other buttons dynamically based on status
        if (biz.status === 'pending') {
            const rejectBtn = document.createElement('button');
            rejectBtn.className = 'btn btn-danger';
            rejectBtn.innerHTML = `<i class="fas fa-times me-1"></i> Reject`;
            rejectBtn.addEventListener('click', () => {
                bootstrap.Modal.getInstance(document.getElementById('bizDetailModal')).hide();
                setTimeout(() => window._promptReject(biz.id, biz.name), 350);
            });

            const approveBtn = document.createElement('button');
            approveBtn.className = 'btn btn-success';
            approveBtn.innerHTML = `<i class="fas fa-check me-1"></i> Approve`;
            approveBtn.addEventListener('click', () => {
                bootstrap.Modal.getInstance(document.getElementById('bizDetailModal')).hide();
                setTimeout(() => window._promptApprove(biz.id, biz.name), 350);
            });

            footer.appendChild(rejectBtn);
            footer.appendChild(approveBtn);

        } else if (biz.status === 'approved') {
            const revokeBtn = document.createElement('button');
            revokeBtn.className = 'btn btn-outline-danger';
            revokeBtn.innerHTML = `<i class="fas fa-ban me-1"></i> Revoke Approval`;
            revokeBtn.addEventListener('click', () => {
                bootstrap.Modal.getInstance(document.getElementById('bizDetailModal')).hide();
                setTimeout(() => window._promptReject(biz.id, biz.name), 350);
            });
            footer.appendChild(revokeBtn);

        } else { // rejected
            const approveInsteadBtn = document.createElement('button');
            approveInsteadBtn.className = 'btn btn-success';
            approveInsteadBtn.innerHTML = `<i class="fas fa-check me-1"></i> Approve Instead`;
            approveInsteadBtn.addEventListener('click', () => {
                bootstrap.Modal.getInstance(document.getElementById('bizDetailModal')).hide();
                setTimeout(() => window._promptApprove(biz.id, biz.name), 350);
            });
            footer.appendChild(approveInsteadBtn);
        }

        // Finally, show the modal
        new bootstrap.Modal(document.getElementById('bizDetailModal')).show();
    };

    // ── NEW: Approve / Reject prompt helpers ──────────────────────────
    window._promptApprove = function(id, name) {
        pendingApproveId = id;
        document.getElementById('approveBusinessName').textContent = name;
        new bootstrap.Modal(document.getElementById('approveModal')).show();
    };

    window._promptReject = function(id, name) {
        pendingRejectId = id;
        document.getElementById('rejectBusinessName').textContent = name;
        document.getElementById('rejectReason').value = '';
        new bootstrap.Modal(document.getElementById('rejectModal')).show();
    };

    // ── NEW: Confirm Approve ───────────────────────────────────────────
    document.getElementById('confirmApproveBtn').addEventListener('click', async () => {
        if (!pendingApproveId) return;
        try {
            // Flip status to 'approved' in the businesses collection
            await updateDoc(doc(db, 'businesses', pendingApproveId), {
                status:          'approved',
                approvedAt:      serverTimestamp(),
                rejectionReason: null
            });
            // Notify the business owner via user_notifications
            // (matches what the Flutter app's userNotificationsStream listens to)
            const biz = users.find(b => b.id === pendingApproveId);
            if (biz?.uid) {
                await addDoc(collection(db, 'user_notifications'), {
                    uid:        biz.uid,
                    type:       'business_approved',
                    title:      'Business Approved! 🎉',
                    body:       `"${biz.name}" has been approved and is now visible on the map.`,
                    businessId: pendingApproveId,
                    read:       false,
                    createdAt:  serverTimestamp()
                });
            }
            bootstrap.Modal.getInstance(document.getElementById('approveModal')).hide();
            showNotification('Business approved!', 'success');
        } catch(e) {
            console.error(e);
            showNotification('Error approving: ' + e.message, 'error');
        }
        pendingApproveId = null;
    });

    // ── NEW: Confirm Reject ────────────────────────────────────────────
    document.getElementById('confirmRejectBtn').addEventListener('click', async () => {
        const reason = document.getElementById('rejectReason').value.trim();
        if (!reason) {
            document.getElementById('rejectReason').classList.add('is-invalid');
            return;
        }
        document.getElementById('rejectReason').classList.remove('is-invalid');
        if (!pendingRejectId) return;
        try {
            // Flip status to 'rejected' and save the reason
            await updateDoc(doc(db, 'businesses', pendingRejectId), {
                status:          'rejected',
                rejectionReason: reason,
                rejectedAt:      serverTimestamp()
            });
            // Notify the business owner via user_notifications
            const biz = users.find(b => b.id === pendingRejectId);
            if (biz?.uid) {
                await addDoc(collection(db, 'user_notifications'), {
                    uid:        biz.uid,
                    type:       'business_rejected',
                    title:      'Business Submission Rejected',
                    body:       `"${biz.name}" was not approved. Reason: ${reason}`,
                    businessId: pendingRejectId,
                    read:       false,
                    createdAt:  serverTimestamp()
                });
            }
            bootstrap.Modal.getInstance(document.getElementById('rejectModal')).hide();
            showNotification('Business rejected.', 'warning');
        } catch(e) {
            console.error(e);
            showNotification('Error rejecting: ' + e.message, 'error');
        }
        pendingRejectId = null;
    });

    // ── Open edit modal ───────────────────────────────────────────────
    // window.editUserProfile = function (userId) {
    //     event.stopPropagation();
    //     const user = users.find(u => u.id === userId);
    //     if (!user) return;

    //     userToEdit = user;

    //     document.getElementById('editUserId').value      = user.id;
    //     document.getElementById('editUserName').value    = user.name;
    //     document.getElementById('editUserEmail').value   = user.email;
    //     document.getElementById('editUserRole').value    = user.role;
    //     document.getElementById('editUserStatus').value  = user.status;
    //     document.getElementById('editUserAddress').value = user.address;
    //     document.getElementById('editUserLat').value     = user.coords[0];
    //     document.getElementById('editUserLng').value     = user.coords[1];
    //     document.getElementById('editUserAvatar').value  = user.avatar || getInitials(user.name);
    //     document.getElementById('editUserLastSeen').value = user.lastSeen;
    //     document.getElementById('editUserProfilePicUrl').value = uploadedImages[user.id] ? '' : (user.profilePic || '');

    //     updateEditProfilePreview();
    //     new bootstrap.Modal(document.getElementById('editUserModal')).show();

    //     map.flyTo(user.coords, 16);
    //     const entry = userMarkers.find(m => m.id === userId);
    //     if (entry?.marker) setTimeout(() => entry.marker.openPopup(), 1000);

    //     // Live geocode while typing address
    //     const addressInput = document.getElementById('editUserAddress');
    //     let addressTimeout;
    //     addressInput.addEventListener('input', function () {
    //         clearTimeout(addressTimeout);
    //         addressTimeout = setTimeout(async () => {
    //             if (this.value.length <= 3) return;
    //             const coords = await geocodeAddress(this.value);
    //             if (!coords) return;
    //             document.getElementById('editUserLat').value = coords.lat.toFixed(6);
    //             document.getElementById('editUserLng').value = coords.lng.toFixed(6);
    //             map.flyTo([coords.lat, coords.lng], 16);
    //             if (previewMarker) map.removeLayer(previewMarker);
    //             previewMarker = L.marker([coords.lat, coords.lng], {
    //                 icon: L.divIcon({
    //                     html: '<div style="width:40px;height:40px;background:rgba(255,0,0,0.5);border:3px solid red;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:bold;font-size:12px;">?</div>',
    //                     className: 'custom-div-icon',
    //                     iconSize: [40, 40],
    //                     iconAnchor: [20, 20]
    //                 })
    //             }).addTo(map).bindPopup('Preview of new location<br>Click "Save Changes" to confirm').openPopup();
    //         }, 800);
    //     });
    // };

    // ── Show delete confirmation ──────────────────────────────────────
    // window.showDeleteConfirmation = function (userId) {
    //     event.stopPropagation();
    //     const user = users.find(u => u.id === userId);
    //     if (!user) return;
    //     userToDelete = user;
    //     document.getElementById('deleteUserName').textContent = user.name;
    //     new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
    // };

    // ── Profile preview helpers ───────────────────────────────────────
    // function updateNewProfilePreview() {
    //     const name   = document.getElementById('newUserName').value || 'John Doe';
    //     const role   = document.getElementById('newUserRole').value || 'User';
    //     const status = document.getElementById('newUserStatus').value || 'Active';
    //     const avatar = document.getElementById('newUserAvatar').value || getInitials(name) || 'JD';
    //     const url    = document.getElementById('newUserProfilePicUrl').value;

    //     document.getElementById('newProfileNamePreview').textContent = name;
    //     document.getElementById('newProfileRolePreview').textContent = role;

    //     const statusEl = document.getElementById('newProfileStatusPreview');
    //     statusEl.textContent = status;
    //     statusEl.className = `user-status status-${status.toLowerCase()}`;

    //     const preview = document.getElementById('newProfilePicPreview');
    //     const text    = document.getElementById('newProfilePicText');
    //     if (url) {
    //         preview.style.backgroundImage = `url('${url}')`;
    //         text.style.display = 'none';
    //     } else {
    //         preview.style.backgroundImage = '';
    //         preview.style.backgroundColor = '#4361ee';
    //         text.textContent = avatar;
    //         text.style.display = 'flex';
    //     }
    // }

    // function updateEditProfilePreview() {
    //     const name   = document.getElementById('editUserName').value;
    //     const role   = document.getElementById('editUserRole').value;
    //     const status = document.getElementById('editUserStatus').value;
    //     const avatar = document.getElementById('editUserAvatar').value || getInitials(name);
    //     const url    = document.getElementById('editUserProfilePicUrl').value;
    //     const userId = document.getElementById('editUserId').value;

    //     document.getElementById('editProfileNamePreview').textContent = name;
    //     document.getElementById('editProfileRolePreview').textContent = role;

    //     const statusEl = document.getElementById('editProfileStatusPreview');
    //     statusEl.textContent = status;
    //     statusEl.className = `user-status status-${status.toLowerCase()}`;

    //     const preview = document.getElementById('editProfilePicPreview');
    //     const text    = document.getElementById('editProfilePicText');
    //     if (uploadedImages[userId]) {
    //         preview.style.backgroundImage = `url('${uploadedImages[userId]}')`;
    //         text.style.display = 'none';
    //     } else if (url) {
    //         preview.style.backgroundImage = `url('${url}')`;
    //         text.style.display = 'none';
    //     } else {
    //         preview.style.backgroundImage = '';
    //         preview.style.backgroundColor = '#4361ee';
    //         text.textContent = avatar;
    //         text.style.display = 'flex';
    //     }
    // }

    // ── Image source toggle (Upload / URL) ────────────────────────────
    function initImageSourceOptions() {
        [
            { optionsId: 'newImageSourceOptions', uploadId: 'newUploadSection', urlId: 'newUrlSection' },
            { optionsId: 'editImageSourceOptions', uploadId: 'editUploadSection', urlId: 'editUrlSection' }
        ].forEach(({ optionsId, uploadId, urlId }) => {
            document.querySelectorAll(`#${optionsId} .image-source-btn`).forEach(btn => {
                btn.addEventListener('click', function () {
                    document.querySelectorAll(`#${optionsId} .image-source-btn`).forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    const isUpload = this.getAttribute('data-source') === 'upload';
                    document.getElementById(uploadId).classList.toggle('d-none', !isUpload);
                    document.getElementById(urlId).classList.toggle('d-none', isUpload);
                });
            });
        });
    }

    // ── File upload handlers ──────────────────────────────────────────
    // function initFileUploadHandlers() {
    //     function handleUpload(inputId, previewId, profilePreviewId, profileTextId, tempKey, type) {
    //         document.getElementById(inputId).addEventListener('change', function (e) {
    //             const file = e.target.files[0];
    //             if (!file) return;
    //             if (file.size > 5 * 1024 * 1024) { showNotification('File size must be less than 5MB', 'error'); this.value = ''; return; }
    //             if (!file.type.match('image.*')) { showNotification('Please select an image file', 'error'); this.value = ''; return; }

    //             const reader = new FileReader();
    //             reader.onload = (evt) => {
    //                 const imageData = evt.target.result;
    //                 const key = tempKey || document.getElementById('editUserId').value;
    //                 uploadedImages[key] = imageData;

    //                 const previewContainer = document.getElementById(previewId);
    //                 const img = Object.assign(document.createElement('img'), { src: imageData, alt: 'Profile Preview' });
    //                 const removeBtn = document.createElement('button');
    //                 removeBtn.type = 'button';
    //                 removeBtn.className = 'remove-image-btn';
    //                 removeBtn.innerHTML = '<i class="fas fa-trash"></i> Remove Image';
    //                 removeBtn.onclick = () => {
    //                     delete uploadedImages[key];
    //                     previewContainer.innerHTML = '';
    //                     document.getElementById(inputId).value = '';
    //                     type === 'new' ? updateNewProfilePreview() : updateEditProfilePreview();
    //                 };

    //                 previewContainer.innerHTML = '';
    //                 previewContainer.append(img, removeBtn);

    //                 document.getElementById(profilePreviewId).style.backgroundImage = `url('${imageData}')`;
    //                 document.getElementById(profileTextId).style.display = 'none';
    //             };
    //             reader.readAsDataURL(file);
    //         });
    //     }

    //     handleUpload('newUserProfilePicUpload', 'newUploadPreview', 'newProfilePicPreview', 'newProfilePicText', `temp-${Date.now()}`, 'new');
    //     handleUpload('editUserProfilePicUpload', 'editUploadPreview', 'editProfilePicPreview', 'editProfilePicText', null, 'edit');
    // }

    // ── Save NEW user ─────────────────────────────────────────────────
    // document.getElementById('saveNewUser').addEventListener('click', async function () {
    //     const name    = document.getElementById('newUserName').value.trim();
    //     const email   = document.getElementById('newUserEmail').value.trim();
    //     const role    = document.getElementById('newUserRole').value;
    //     const status  = document.getElementById('newUserStatus').value;
    //     const address = document.getElementById('newUserAddress').value.trim();
    //     const lat     = parseFloat(document.getElementById('newUserLat').value);
    //     const lng     = parseFloat(document.getElementById('newUserLng').value);
    //     const avatar  = document.getElementById('newUserAvatar').value;
    //     const url     = document.getElementById('newUserProfilePicUrl').value;

    //     const tempKeys     = Object.keys(uploadedImages).filter(k => k.startsWith('temp-'));
    //     const uploadedImage = tempKeys.length ? uploadedImages[tempKeys[0]] : null;

    //     if (!name || !email || !role || !status || !address || isNaN(lat) || isNaN(lng)) {
    //         showNotification('Please fill all required fields correctly.', 'error');
    //         return;
    //     }

    //     try {
    //         // ── BACKEND CALL ─────────────────────────────────────────
    //         await addUser({
    //             name, email, role, status, address,
    //             coords: [lat, lng],
    //             avatar: avatar || getInitials(name),
    //             profilePic: uploadedImage || url || getDefaultProfilePic()
    //         });
    //         // ─────────────────────────────────────────────────────────

    //         if (uploadedImage) tempKeys.forEach(k => delete uploadedImages[k]);

    //         bootstrap.Modal.getInstance(document.getElementById('addUserModal')).hide();
    //         document.getElementById('addUserForm').reset();
    //         document.getElementById('newUploadPreview').innerHTML = '';
    //         updateNewProfilePreview();
    //         showNotification(`User "${name}" added successfully!`, 'success');

    //     } catch (err) {
    //         showNotification('Failed to save user. Check console.', 'error');
    //     }
    // });

    // ── Save EDITED user ──────────────────────────────────────────────
    // document.getElementById('saveEditUser').addEventListener('click', async function () {
    //     const userId = document.getElementById('editUserId').value;
    //     if (!userId) return;

    //     const profilePic = uploadedImages[userId]
    //         || document.getElementById('editUserProfilePicUrl').value.trim()
    //         || null;

    //     try {
    //         // ── BACKEND CALL ─────────────────────────────────────────
    //         await updateUser(userId, {
    //             name:       document.getElementById('editUserName').value.trim(),
    //             email:      document.getElementById('editUserEmail').value.trim(),
    //             role:       document.getElementById('editUserRole').value,
    //             status:     document.getElementById('editUserStatus').value,
    //             address:    document.getElementById('editUserAddress').value.trim(),
    //             coords: [
    //                 parseFloat(document.getElementById('editUserLat').value),
    //                 parseFloat(document.getElementById('editUserLng').value)
    //             ],
    //             avatar:      document.getElementById('editUserAvatar').value.toUpperCase(),
    //             profilePic
    //         });
    //         // ─────────────────────────────────────────────────────────

    //         bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
    //         if (previewMarker) { map.removeLayer(previewMarker); previewMarker = null; }
    //         showNotification('User updated successfully!', 'success');

    //     } catch (err) {
    //         showNotification('Failed to save changes. Check console.', 'error');
    //     }
    // });

    // ── Confirm DELETE ────────────────────────────────────────────────
    // document.getElementById('confirmDeleteBtn').addEventListener('click', async function () {
    //     if (!userToDelete) return;
    //     try {
    //         // ── BACKEND CALL ─────────────────────────────────────────
    //         await deleteUser(userToDelete.id);
    //         // ─────────────────────────────────────────────────────────

    //         bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal')).hide();
    //         showNotification(`User "${userToDelete.name}" deleted successfully!`, 'success');
    //         userToDelete = null;

    //     } catch (err) {
    //         showNotification('Failed to delete user. Check console.', 'error');
    //     }
    // });

    // ── Map controls ──────────────────────────────────────────────────
    document.getElementById('locateMe').addEventListener('click', function () {
        if (!navigator.geolocation) { alert('Geolocation not supported.'); return; }
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const loc = [pos.coords.latitude, pos.coords.longitude];
                L.marker(loc, {
                    icon: L.divIcon({
                        html: '<div class="current-user-marker"><i class="fas fa-user-circle"></i></div>',
                        className: 'custom-div-icon',
                        iconSize: [45, 45],
                        iconAnchor: [22, 22]
                    })
                }).addTo(map).bindPopup('You are here!').openPopup();
                map.flyTo(loc, 15);
            },
            () => alert('Unable to retrieve your location.')
        );
    });

    document.getElementById('resetView').addEventListener('click', function () {
        map.flyTo([14.4160, 120.8541], 14);
        document.querySelectorAll('.user-card').forEach(c => c.classList.remove('active'));
        if (previewMarker) { map.removeLayer(previewMarker); previewMarker = null; }
    });

    document.getElementById('toggleUsers').addEventListener('click', function () {
        userMarkersVisible = !userMarkersVisible;
        userMarkers.forEach(({ marker }) => {
            userMarkersVisible ? marker.addTo(map) : map.removeLayer(marker);
        });
        this.innerHTML = userMarkersVisible
            ? '<i class="fas fa-users"></i> Hide Businesses'
            : '<i class="fas fa-users"></i> Show Businesses';
    });

    // ── Search ────────────────────────────────────────────────────────
    document.getElementById('searchUsers').addEventListener('input', function () {
        renderUserCards(); // re-render cards with current search + active filter

        // Also hide/show map markers that don't match the search term
        const term = this.value.toLowerCase();
        users.forEach(user => {
            const matches =
                user.name.toLowerCase().includes(term) ||
                (user.address || '').toLowerCase().includes(term) ||
                (user.phone || '').toLowerCase().includes(term);

            const entry = userMarkers.find(m => m.id === user.id);

            if (entry) {
                (!term || matches) && userMarkersVisible ? entry.marker.addTo(map) : map.removeLayer(entry.marker);
            }
        });
    });

    document.getElementById('searchButton').addEventListener('click', () => document.getElementById('searchUsers').focus());

    // ── NEW: Filter pill click handler ────────────────────────────────
    document.querySelectorAll('.filter-pill').forEach(pill => {
        pill.addEventListener('click', function() {
            document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            activeFilter = this.dataset.filter;
            renderUserCards();
        });
    });

    // ── Map click → update lat/lng in forms ───────────────────────────
    map.on('click', function(e) {
        const latInput = document.getElementById('newUserLat');
        const lngInput = document.getElementById('newUserLng');
        const editLatInput = document.getElementById('editUserLat');
        const editLngInput = document.getElementById('editUserLng');
        
        // Update add form if visible
        if (latInput && lngInput) {
            latInput.value = e.latlng.lat.toFixed(6);
            lngInput.value = e.latlng.lng.toFixed(6);
        }
        
        // Update edit form if visible
        if (editLatInput && editLngInput && document.getElementById('editUserModal').classList.contains('show')) {
            editLatInput.value = e.latlng.lat.toFixed(6);
            editLngInput.value = e.latlng.lng.toFixed(6);
            
            // Also update preview if coordinates changed
            updateEditProfilePreview();
        }
    });

    // ── Modal reset handlers ──────────────────────────────────────────
    // document.getElementById('addUserModal').addEventListener('hidden.bs.modal', function () {
    //     document.getElementById('addUserForm').reset();
    //     document.getElementById('newUploadPreview').innerHTML = '';
    //     updateNewProfilePreview();
    // });

    // document.getElementById('editUserModal').addEventListener('hidden.bs.modal', function () {
    //     document.getElementById('editUploadPreview').innerHTML = '';
    //     if (previewMarker) { map.removeLayer(previewMarker); previewMarker = null; }
    // });

    // ── Real-time form previews ───────────────────────────────────────
    // ['newUserName', 'newUserRole', 'newUserStatus', 'newUserAvatar'].forEach(id =>
    //     document.getElementById(id).addEventListener('input', updateNewProfilePreview)
    // );
    // ['editUserName', 'editUserRole', 'editUserStatus', 'editUserAvatar'].forEach(id =>
    //     document.getElementById(id).addEventListener('input', updateEditProfilePreview)
    // );
    // document.getElementById('newUserProfilePicUrl').addEventListener('input', updateNewProfilePreview);
    // document.getElementById('editUserProfilePicUrl').addEventListener('input', updateEditProfilePreview);

    // ── Dark mode ─────────────────────────────────────────────────────
    const darkIcon = document.getElementById('darkModeIcon');
    if (localStorage.getItem('darkMode') === 'enabled') {
        document.body.classList.add('dark-mode');
        darkIcon.classList.replace('fa-moon', 'fa-sun');
    }

    document.getElementById('darkModeToggle').addEventListener('click', function () {
        document.body.classList.toggle('dark-mode');
        const isDark = document.body.classList.contains('dark-mode');
        darkIcon.classList.replace(isDark ? 'fa-moon' : 'fa-sun', isDark ? 'fa-sun' : 'fa-moon');
        localStorage.setItem('darkMode', isDark ? 'enabled' : 'disabled');
    });

    // ── Notification helper ───────────────────────────────────────────
    function showNotification(message, type) {
        const icons = { success: 'check-circle', error: 'exclamation-circle', warning: 'exclamation-triangle', info: 'info-circle' };
        const el = document.createElement('div');
        el.className = `notification ${type}`;
        el.innerHTML = `<i class="fas fa-${icons[type] || 'info-circle'}"></i><span>${message}</span>`;
        document.body.appendChild(el);
        setTimeout(() => {
            el.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => el.parentNode?.removeChild(el), 300);
        }, 3000);
    }

    // ── Init ──────────────────────────────────────────────────────────
    initImageSourceOptions();
    // initFileUploadHandlers();
    // updateNewProfilePreview();

    document.getElementById("exportLocations").addEventListener("click", exportLocations);
    document.getElementById("printMap").addEventListener("click", printMap);
    document.getElementById("refreshMap").addEventListener("click", refreshMap);
    document.getElementById("showMapHelp").addEventListener("click", showMapHelp);
});