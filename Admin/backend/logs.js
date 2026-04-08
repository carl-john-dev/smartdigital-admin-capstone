/**
 * Dahil iba yung structure, di to pwede ilagay sa backend.js
 * -Arjon
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize data
    let activityLogs = JSON.parse(localStorage.getItem('cbocLogs')) || [];
    
    // Load initial data
    loadLogs();
    updateStats();
    
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        filterLogs(searchTerm);
    });
    
    // Filter changes
    document.getElementById('typeFilter').addEventListener('change', loadLogs);
    document.getElementById('userFilter').addEventListener('change', loadLogs);
    document.getElementById('moduleFilter').addEventListener('change', loadLogs);
    document.getElementById('startDate').addEventListener('change', loadLogs);
    document.getElementById('endDate').addEventListener('change', loadLogs);
    
    // Refresh button
    document.getElementById('refreshBtn').addEventListener('click', function() {
        loadLogs();
        updateStats();
        showNotification('Logs refreshed');
    });
    
    // Clear logs button
    document.getElementById('clearLogsBtn').addEventListener('click', function() {
        if (confirm('Clear all activity logs? This action cannot be undone.')) {
            activityLogs = [{
                id: Date.now(),
                timestamp: new Date().toISOString(),
                action: 'All activity logs cleared',
                user: 'Admin User',
                type: 'system',
                module: 'logs',
                details: 'User cleared all activity logs'
            }];
            localStorage.setItem('cbocLogs', JSON.stringify(activityLogs));
            loadLogs();
            updateStats();
            showNotification('Logs cleared');
        }
    });
    
    // Export logs button
    document.getElementById('exportLogsBtn').addEventListener('click', exportLogs);
    
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
    function loadLogs() {
        const logsList = document.getElementById('logsList');
        
        // Initialize sample data if empty
        if (activityLogs.length === 0) {
            initializeSampleLogs();
        }
        
        // Get filter values
        const typeFilter = document.getElementById('typeFilter').value;
        const userFilter = document.getElementById('userFilter').value;
        const moduleFilter = document.getElementById('moduleFilter').value;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        
        // Filter logs
        let filteredLogs = activityLogs;
        
        if (typeFilter !== 'all') {
            filteredLogs = filteredLogs.filter(log => log.type === typeFilter);
        }
        
        if (userFilter !== 'all') {
            filteredLogs = filteredLogs.filter(log => log.user.toLowerCase().includes(userFilter));
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
        
        // Sort by most recent
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
                <div class="log-header">
                    <span class="log-timestamp">${formatDateTime(log.timestamp)}</span>
                    <span class="log-type type-${log.type}">${log.type.toUpperCase()}</span>
                </div>
                <div class="log-action">${log.action}</div>
                <div class="log-details">
                    <span>User: <span class="log-user">${log.user}</span></span>
                    <span class="log-module">Module: ${log.module || 'General'}</span>
                </div>
                ${log.details ? `<small class="text-muted">${log.details}</small>` : ''}
            </div>
        `).join('');
    }
    
    function filterLogs(searchTerm) {
        const logs = document.querySelectorAll('.log-item');
        logs.forEach(log => {
            const text = log.textContent.toLowerCase();
            log.style.display = text.includes(searchTerm) ? 'block' : 'none';
        });
    }
    
    function updateStats() {
        const today = new Date().toDateString();
        const todayLogs = activityLogs.filter(log => 
            new Date(log.timestamp).toDateString() === today
        ).length;
        
        const userLogs = activityLogs.filter(log => log.type === 'user').length;
        const systemLogs = activityLogs.filter(log => log.type === 'system').length;
        
        document.getElementById('totalLogs').textContent = activityLogs.length;
        document.getElementById('todayLogs').textContent = todayLogs;
        document.getElementById('userLogs').textContent = userLogs;
        document.getElementById('systemLogs').textContent = systemLogs;
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
            },
            {
                id: 4,
                timestamp: new Date(Date.now() - 10800000).toISOString(),
                action: 'Request #45 status updated',
                user: 'Admin User',
                type: 'update',
                module: 'requests',
                details: 'Status changed from Pending to Approved'
            },
            {
                id: 5,
                timestamp: new Date(Date.now() - 14400000).toISOString(),
                action: 'Member archived: Jane Smith',
                user: 'Admin User',
                type: 'delete',
                module: 'archive',
                details: 'Archived due to resignation'
            },
            {
                id: 6,
                timestamp: new Date(Date.now() - 18000000).toISOString(),
                action: 'System backup completed',
                user: 'System',
                type: 'system',
                module: 'system',
                details: 'Daily backup completed successfully'
            },
            {
                id: 7,
                timestamp: new Date(Date.now() - 21600000).toISOString(),
                action: 'Password changed',
                user: 'Admin User',
                type: 'update',
                module: 'system',
                details: 'User changed their password'
            },
            {
                id: 8,
                timestamp: new Date(Date.now() - 25200000).toISOString(),
                action: 'Export report generated',
                user: 'Admin User',
                type: 'create',
                module: 'reports',
                details: 'Monthly report exported to PDF'
            }
        ];
        localStorage.setItem('cbocLogs', JSON.stringify(activityLogs));
    }
    
    function exportLogs() {
        const dataStr = JSON.stringify(activityLogs, null, 2);
        const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
        const exportFileDefaultName = 'cboc-activity-logs-export.json';
        
        const linkElement = document.createElement('a');
        linkElement.setAttribute('href', dataUri);
        linkElement.setAttribute('download', exportFileDefaultName);
        linkElement.click();
        
        showNotification('Logs exported successfully');
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
        // Create notification element
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
        
        // Remove after 3 seconds
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
