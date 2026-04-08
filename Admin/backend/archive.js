/**
 * Dahil iba yung structure, di to pwede ilagay sa backend.js
 * -Arjon
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize data
    let archiveData = JSON.parse(localStorage.getItem('cbocArchive')) || [];
    let activityLogs = JSON.parse(localStorage.getItem('cbocLogs')) || [];
    let currentSection = 'archive';
    
    // Load initial data
    loadArchiveData();
    loadLogs();
    updateStats('archive');
    
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
    
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        if (currentSection === 'archive') {
            filterArchiveItems(searchTerm);
        } else {
            filterLogs(searchTerm);
        }
    });
    
    // Archive filter tabs
    document.querySelectorAll('#archiveSection .filter-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('#archiveSection .filter-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            loadArchiveData(this.dataset.filter);
        });
    });
    
    // Logs filters
    document.getElementById('typeFilter').addEventListener('change', loadLogs);
    document.getElementById('moduleFilter').addEventListener('change', loadLogs);
    document.getElementById('startDate').addEventListener('change', loadLogs);
    document.getElementById('endDate').addEventListener('change', loadLogs);
    
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
    
    // Functions
    window.switchSection = function(section) {
        currentSection = section;
        
        // Hide all sections
        document.getElementById('archiveSection').classList.remove('active');
        document.getElementById('logsSection').classList.remove('active');
        
        // Show selected section
        document.getElementById(section + 'Section').classList.add('active');
        
        // Update stats
        updateStats(section);
        
        // Clear search
        document.getElementById('searchInput').value = '';
        
        // Reload data
        if (section === 'archive') {
            loadArchiveData();
        } else {
            loadLogs();
        }
    };
    
    window.exportCurrentSection = function() {
        if (currentSection === 'archive') {
            exportArchiveData();
        } else {
            exportLogs();
        }
    };
    
    window.clearCurrentSection = function() {
        if (currentSection === 'archive') {
            if (confirm('Clear all archived items? This action cannot be undone.')) {
                archiveData = [];
                localStorage.setItem('cbocArchive', JSON.stringify(archiveData));
                loadArchiveData();
                updateStats('archive');
                showNotification('Archive cleared');
            }
        } else {
            if (confirm('Clear all activity logs? This action cannot be undone.')) {
                activityLogs = [];
                localStorage.setItem('cbocLogs', JSON.stringify(activityLogs));
                loadLogs();
                updateStats('logs');
                showNotification('Logs cleared');
            }
        }
    };
    
    window.refreshCurrentSection = function() {
        if (currentSection === 'archive') {
            loadArchiveData();
            updateStats('archive');
        } else {
            loadLogs();
            updateStats('logs');
        }
        showNotification('Refreshed');
    };
    
    function loadArchiveData(filter = 'all') {
        const archiveList = document.getElementById('archiveList');
        
        // Initialize sample data if empty
        if (archiveData.length === 0) {
            initializeSampleArchiveData();
        }
        
        let filteredData = archiveData;
        
        // Apply filters
        switch(filter) {
            case 'member':
                filteredData = archiveData.filter(item => item.type === 'member');
                break;
            case 'event':
                filteredData = archiveData.filter(item => item.type === 'event');
                break;
            case 'request':
                filteredData = archiveData.filter(item => item.type === 'request');
                break;
            case 'recent':
                filteredData = archiveData
                    .sort((a, b) => new Date(b.archivedAt) - new Date(a.archivedAt))
                    .slice(0, 10);
                break;
        }
        
        if (filteredData.length === 0) {
            archiveList.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <h4>No Archived Items</h4>
                    <p>No items found for this filter</p>
                </div>
            `;
            return;
        }
        
        filteredData.sort((a, b) => new Date(b.archivedAt) - new Date(a.archivedAt));
        
        archiveList.innerHTML = filteredData.map(item => `
            <div class="archive-item">
                <div class="item-header">
                    <div>
                        <span class="item-type type-${item.type}">${item.type.toUpperCase()}</span>
                        <span class="item-title">${item.name}</span>
                    </div>
                    <small class="text-muted">${formatDate(item.archivedAt)}</small>
                </div>
                <div class="item-meta">
                    Archived by: <strong>${item.archivedBy}</strong> | 
                    Reason: <em>${item.reason}</em>
                </div>
                ${item.description ? `<div class="item-reason">${item.description}</div>` : ''}
                <div class="item-actions">
                    <button class="btn-restore" onclick="restoreItem(${item.id})">
                        <i class="fas fa-undo me-1"></i> Restore
                    </button>
                    <button class="btn-delete" onclick="deleteItem(${item.id})">
                        <i class="fas fa-trash me-1"></i> Delete
                    </button>
                </div>
            </div>
        `).join('');
    }
    
    function loadLogs() {
        const logsList = document.getElementById('logsList');
        
        // Initialize sample data if empty
        if (activityLogs.length === 0) {
            initializeSampleLogs();
        }
        
        // Get filter values
        const typeFilter = document.getElementById('typeFilter').value;
        const moduleFilter = document.getElementById('moduleFilter').value;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        
        // Filter logs
        let filteredLogs = activityLogs;
        
        if (typeFilter !== 'all') {
            filteredLogs = filteredLogs.filter(log => log.type === typeFilter);
        }
        
        if (moduleFilter !== 'all') {
            filteredLogs = filteredLogs.filter(log => log.module === moduleFilter);
        }
        
        if (startDate) {
            filteredLogs = filteredLogs.filter(log => new Date(log.timestamp) >= new Date(startDate));
        }
        
        if (endDate) {
            const end = new Date(endDate);
            end.setHours(23, 59, 59, 999);
            filteredLogs = filteredLogs.filter(log => new Date(log.timestamp) <= end);
        }
        
        filteredLogs.sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp));
        
        if (filteredLogs.length === 0) {
            logsList.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-clipboard-list"></i>
                    <h4>No Activity Logs</h4>
                    <p>No logs found for the selected filters</p>
                </div>
            `;
            return;
        }
        
        logsList.innerHTML = filteredLogs.map(log => `
            <div class="log-item">
                <div class="item-header">
                    <span class="log-timestamp">${formatDateTime(log.timestamp)}</span>
                    <span class="item-type type-${log.type}">${log.type.toUpperCase()}</span>
                </div>
                <div class="item-title">${log.action}</div>
                <div class="item-meta">
                    User: <strong>${log.user}</strong> | Module: ${log.module || 'General'}
                </div>
                ${log.details ? `<div class="item-reason">${log.details}</div>` : ''}
                <div class="item-actions">
                    <button class="btn-view" onclick="viewLogDetails(${log.id})">
                        <i class="fas fa-eye me-1"></i> View Details
                    </button>
                </div>
            </div>
        `).join('');
    }
    
    function updateStats(section) {
        const statsSection = document.getElementById('statsSection');
        
        if (section === 'archive') {
            const members = archiveData.filter(item => item.type === 'member').length;
            const events = archiveData.filter(item => item.type === 'event').length;
            const requests = archiveData.filter(item => item.type === 'request').length;
            
            statsSection.innerHTML = `
                <div class="stat-card">
                    <div class="stat-number">${archiveData.length}</div>
                    <div class="stat-label">Total Archived</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${members}</div>
                    <div class="stat-label">Archived Members</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${events}</div>
                    <div class="stat-label">Archived Events</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${requests}</div>
                    <div class="stat-label">Archived Requests</div>
                </div>
            `;
        } else {
            const today = new Date().toDateString();
            const todayLogs = activityLogs.filter(log => 
                new Date(log.timestamp).toDateString() === today
            ).length;
            
            const userLogs = activityLogs.filter(log => log.type === 'user').length;
            const systemLogs = activityLogs.filter(log => log.type === 'system').length;
            
            statsSection.innerHTML = `
                <div class="stat-card">
                    <div class="stat-number">${activityLogs.length}</div>
                    <div class="stat-label">Total Logs</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${todayLogs}</div>
                    <div class="stat-label">Today's Activities</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${userLogs}</div>
                    <div class="stat-label">User Actions</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${systemLogs}</div>
                    <div class="stat-label">System Events</div>
                </div>
            `;
        }
    }
    
    function filterArchiveItems(searchTerm) {
        const items = document.querySelectorAll('#archiveList .archive-item');
        items.forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(searchTerm) ? 'block' : 'none';
        });
    }
    
    function filterLogs(searchTerm) {
        const items = document.querySelectorAll('#logsList .log-item');
        items.forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(searchTerm) ? 'block' : 'none';
        });
    }
    
    function initializeSampleArchiveData() {
        archiveData = [
            {
                id: 1,
                type: 'member',
                name: 'John Doe',
                reason: 'Inactive for 6 months',
                description: 'Member has not logged in or participated in any activities for over 6 months.',
                archivedBy: 'Admin User',
                archivedAt: '2024-01-15T10:30:00Z'
            },
            {
                id: 2,
                type: 'event',
                name: 'Annual Meeting 2023',
                reason: 'Completed',
                description: 'Annual general meeting held on December 15, 2023. All minutes and documents archived.',
                archivedBy: 'Admin User',
                archivedAt: '2023-12-20T14:00:00Z'
            },
            {
                id: 3,
                type: 'request',
                name: 'Equipment Request #45',
                reason: 'Denied - Budget constraints',
                description: 'Request for new laptops denied due to budget limitations.',
                archivedBy: 'Admin User',
                archivedAt: '2024-01-10T09:15:00Z'
            }
        ];
        localStorage.setItem('cbocArchive', JSON.stringify(archiveData));
    }
    
    function initializeSampleLogs() {
        activityLogs = [
            {
                id: 1,
                timestamp: new Date().toISOString(),
                action: 'User logged in to the system',
                user: 'Admin User',
                type: 'user',
                module: 'system',
                details: 'Successful login from Chrome browser'
            },
            {
                id: 2,
                timestamp: new Date(Date.now() - 3600000).toISOString(),
                action: 'New member added: John Doe',
                user: 'Admin User',
                type: 'create',
                module: 'members',
                details: 'Added new member with ID: MEM001'
            },
            {
                id: 3,
                timestamp: new Date(Date.now() - 7200000).toISOString(),
                action: 'Event created: Annual Meeting 2024',
                user: 'Admin User',
                type: 'create',
                module: 'events',
                details: 'Scheduled for February 15, 2024'
            }
        ];
        localStorage.setItem('cbocLogs', JSON.stringify(activityLogs));
    }
    
    window.restoreItem = function(id) {
        if (confirm('Restore this item?')) {
            const index = archiveData.findIndex(item => item.id === id);
            if (index > -1) {
                archiveData.splice(index, 1);
                localStorage.setItem('cbocArchive', JSON.stringify(archiveData));
                loadArchiveData();
                updateStats('archive');
                showNotification('Item restored successfully!');
            }
        }
    };
    
    window.deleteItem = function(id) {
        if (confirm('Permanently delete this item?')) {
            const index = archiveData.findIndex(item => item.id === id);
            if (index > -1) {
                archiveData.splice(index, 1);
                localStorage.setItem('cbocArchive', JSON.stringify(archiveData));
                loadArchiveData();
                updateStats('archive');
                showNotification('Item deleted permanently!');
            }
        }
    };
    
    window.viewLogDetails = function(id) {
        const log = activityLogs.find(l => l.id === id);
        if (log) {
            alert(JSON.stringify(log, null, 2));
        }
    };
    
    function exportArchiveData() {
        const dataStr = JSON.stringify(archiveData, null, 2);
        downloadFile(dataStr, 'cboc-archive-export.json');
    }
    
    function exportLogs() {
        const dataStr = JSON.stringify(activityLogs, null, 2);
        downloadFile(dataStr, 'cboc-logs-export.json');
    }
    
    function downloadFile(data, filename) {
        const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(data);
        const linkElement = document.createElement('a');
        linkElement.setAttribute('href', dataUri);
        linkElement.setAttribute('download', filename);
        linkElement.click();
        showNotification('Export completed');
    }
    
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    
    function formatDateTime(dateString) {
        const date = new Date(dateString);
        return date.toLocaleString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
    }
    
    function showNotification(message) {
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--primary);
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 9999;
            animation: slideIn 0.3s ease;
        `;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
    
    // Add CSS for animations
    const style = document.createElement('style');
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
});
