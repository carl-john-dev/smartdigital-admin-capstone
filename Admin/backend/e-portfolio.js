/**
 * Dahil iba yung structure, di to pwede ilagay sa backend.js
 * -Arjon
 */
document.addEventListener('DOMContentLoaded', function() {
    // Three Dots Menu Functions
    function exportPortfolio() {
        const portfolioData = {
            name: document.getElementById('userName').textContent,
            title: document.getElementById('userTitle').textContent,
            company: document.getElementById('userCompany').textContent.replace('TechVision Inc.', '').trim(),
            email: document.querySelector('#contactEmail .text-muted').textContent,
            phone: document.querySelector('#contactPhone .text-muted').textContent,
            location: document.querySelector('#contactLocation .text-muted').textContent,
            stats: {
                projects: document.getElementById('statProjects').textContent,
                clients: document.getElementById('statClients').textContent,
                experience: document.getElementById('statExperience').textContent,
                rating: document.getElementById('statRating').textContent
            }
        };
        
        const dataStr = JSON.stringify(portfolioData, null, 2);
        const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
        const exportFileDefaultName = 'cboc-portfolio-export.json';
        
        const linkElement = document.createElement('a');
        linkElement.setAttribute('href', dataUri);
        linkElement.setAttribute('download', exportFileDefaultName);
        linkElement.click();
        
        showNotification('Portfolio exported successfully!', 'success');
    };

    function printPortfolio() {
        window.print();
    };

    function refreshPortfolio() {
        location.reload();
    };

    function showPortfolioHelp() {
        alert(`
E-Portfolio Help:
- Digital business card with NFC capability
- Click QR code to open portfolio URL
- Use color themes to customize card appearance
- Update info using the control panel
- Simulate NFC tap to test data transfer
- Download as PDF to save your card
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

    // Initialize animations
    const elements = document.querySelectorAll('.profile-header, .stats-grid, .contact-info, .portfolio-gallery, .skills-section, .social-links');
    elements.forEach((el, index) => {
        el.style.animationDelay = `${index * 0.1}s`;
        el.classList.add('animate-fade-in-up');
    });

    // Initialize custom color picker
    const customColor1 = document.getElementById('customColor1');
    const customColor2 = document.getElementById('customColor2');
    const customColorPreview = document.getElementById('customColorPreview');

    function updateCustomColorPreview() {
        customColorPreview.style.setProperty('--preview-color-1', customColor1.value);
        customColorPreview.style.setProperty('--preview-color-2', customColor2.value);
        customColorPreview.style.background = `linear-gradient(135deg, ${customColor1.value}, ${customColor2.value})`;
    }

    customColor1.addEventListener('input', updateCustomColorPreview);
    customColor2.addEventListener('input', updateCustomColorPreview);
    updateCustomColorPreview();

    // Apply custom color function
    function applyCustomColor() {
        const color1 = customColor1.value;
        const color2 = customColor2.value;
        
        // Remove active class from all theme options
        document.querySelectorAll('.card-color-option').forEach(option => {
            option.classList.remove('active');
        });
        
        // Apply custom color to NFC card
        const nfcCard = document.getElementById('nfcCard');
        nfcCard.style.background = `linear-gradient(135deg, ${color1}, ${color2})`;
        
        showNotification('Custom color applied successfully!', 'success');
    };

    // Simulate NFC functionality
    function simulateNFCTap() {
        const nfcTap = document.getElementById('nfcTap');
        const nfcCard = document.getElementById('nfcCard');
        
        // Show tap animation
        nfcTap.classList.add('active');
        nfcCard.style.transform = 'scale(0.98)';
        
        // Add glow effect
        nfcCard.style.boxShadow = '0 0 30px rgba(67, 97, 238, 0.5)';
        
        // Simulate data transfer
        setTimeout(() => {
            // Add a subtle shake effect
            nfcCard.style.animation = 'shake 0.5s';
            
            // Show success message
            showNotification('Portfolio data transferred successfully!', 'success');
            
            // Reset animations
            setTimeout(() => {
                nfcCard.style.animation = '';
                nfcCard.style.transform = '';
                nfcCard.style.boxShadow = '';
            }, 500);
        }, 1000);
        
        // Hide tap animation
        setTimeout(() => {
            nfcTap.classList.remove('active');
        }, 1500);
    };

    // Update portfolio information
    function updatePortfolio() {
        // Get values from form
        const name = document.getElementById('editName').value;
        const title = document.getElementById('editTitle').value;
        const company = document.getElementById('editCompany').value;
        const email = document.getElementById('editEmail').value;
        const phone = document.getElementById('editPhone').value;
        const avatar = document.getElementById('editAvatar').value;
        const qrData = document.getElementById('editQRCode').value;
        
        // Update card
        document.getElementById('userName').textContent = name;
        document.getElementById('userTitle').textContent = title;
        document.getElementById('userCompany').innerHTML = `
            <i class="fas fa-building"></i>
            ${company}
        `;
        document.getElementById('userAvatar').textContent = avatar;
        
        // Update contact info
        document.querySelector('#contactEmail .text-muted').textContent = email;
        document.querySelector('#contactPhone .text-muted').textContent = phone;
        
        // Update QR code
        if (qrData) {
            document.getElementById('qrCode').innerHTML = `
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(qrData)}&format=svg" alt="QR Code">
            `;
        }
        
        // Show success message
        showNotification('Portfolio updated successfully!', 'success');
        
        // Simulate NFC update
        setTimeout(() => {
            showNotification('NFC card data synchronized', 'info');
        }, 500);
    };

    // Download card as PDF (simulated)
    function downloadCard() {
        showNotification('Preparing PDF download...', 'info');
        setTimeout(() => {
            showNotification('Portfolio downloaded as PDF!', 'success');
        }, 1500);
    };

    // Card color theme selection
    document.querySelectorAll('.card-color-option').forEach(option => {
        option.addEventListener('click', function() {
            // Remove active class from all options
            document.querySelectorAll('.card-color-option').forEach(opt => {
                opt.classList.remove('active');
            });
            
            // Add active class to clicked option
            this.classList.add('active');
            
            // Apply theme
            const theme = this.dataset.theme;
            const color1 = this.dataset.color1;
            const color2 = this.dataset.color2;
            const nfcCard = document.getElementById('nfcCard');
            
            // Update custom color inputs to match selected theme
            if (color1 && color2) {
                document.getElementById('customColor1').value = color1;
                document.getElementById('customColor2').value = color2;
                updateCustomColorPreview();
            }
            
            // Apply the gradient
            nfcCard.style.background = `linear-gradient(135deg, ${color1}, ${color2})`;
            
            showNotification(`Card theme changed to ${theme}`, 'info');
        });
    });

    // QR code click handler
    document.getElementById('qrCode').addEventListener('click', function() {
        const qrData = document.getElementById('editQRCode').value;
        showNotification('QR Code scanned! Opening portfolio...', 'info');
        setTimeout(() => {
            if (qrData && qrData.startsWith('http')) {
                window.open(qrData, '_blank');
            }
        }, 1000);
    });
    
    // Social link handlers
    document.querySelectorAll('.social-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const platform = this.id.replace('social', '').toLowerCase();
            const platforms = {
                'linkedin': 'LinkedIn',
                'github': 'GitHub',
                'twitter': 'Twitter',
                'dribbble': 'Dribbble',
                'behance': 'Behance'
            };
            showNotification(`Opening ${platforms[platform] || platform} profile...`, 'info');
        });
    });

    // Add shake animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
    `;
    document.head.appendChild(style);

    // Show notification
    function showNotification(message, type) {
        const icons = { 
            success: 'fa-check-circle', 
            error: 'fa-exclamation-circle', 
            warning: 'fa-exclamation-triangle', 
            info: 'fa-info-circle' 
        };
        
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `<i class="fas ${icons[type]}"></i><span>${message}</span>`;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Add notification styles if not already present
    if (!document.querySelector('#notification-styles')) {
        const notificationStyle = document.createElement('style');
        notificationStyle.id = 'notification-styles';
        notificationStyle.textContent = `
            .notification {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                min-width: 300px;
                animation: slideIn 0.3s ease;
                border-radius: 8px;
                padding: 15px 20px;
                color: white;
                display: flex;
                align-items: center;
                gap: 10px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            }
            .notification.success { background: linear-gradient(135deg, #10b981, #059669); }
            .notification.error { background: linear-gradient(135deg, #ef4444, #dc2626); }
            .notification.info { background: linear-gradient(135deg, #3b82f6, #2563eb); }
            .notification.warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
            
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(notificationStyle);
    }

    // NFC simulation for mobile devices
    if ('NDEFReader' in window) {
        // Web NFC API is available
        document.querySelector('.nfc-status').innerHTML = `
            <div class="status-dot" style="background: #10b981;"></div>
            <span>Web NFC Ready</span>
        `;
        
        async function startNFCScan() {
            try {
                const ndef = new NDEFReader();
                await ndef.scan();
                
                ndef.addEventListener("readingerror", () => {
                    showNotification("NFC read error", "danger");
                });
                
                ndef.addEventListener("reading", ({ message, serialNumber }) => {
                    simulateNFCTap();
                    showNotification(`Read NFC tag: ${serialNumber}`, "success");
                });
            } catch (error) {
                console.error("NFC error:", error);
            }
        }
        
        // Start NFC scanning if supported
        startNFCScan();
    }

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

    // Add hover effect to card
    const nfcCard = document.getElementById('nfcCard');
    nfcCard.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-10px) rotateX(2deg)';
    });
    
    nfcCard.addEventListener('mouseleave', function() {
        this.style.transform = '';
    });

    // Initialize skill bars animation
    setTimeout(() => {
        const skillBars = document.querySelectorAll('.skill-progress');
        skillBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0';
            setTimeout(() => {
                bar.style.width = width;
            }, 500);
        });
    }, 1000);

    document.getElementById("exportPortfolio").addEventListener("click", exportPortfolio);
    document.getElementById("printPortfolio").addEventListener("click", printPortfolio);
    document.getElementById("refreshPortfolio").addEventListener("click", refreshPortfolio);
    document.getElementById("showPortfolioHelp").addEventListener("click", showPortfolioHelp);
    document.getElementById("applyCustomColor").addEventListener("click", applyCustomColor);
    document.getElementById("updatePortfolio").addEventListener("click", updatePortfolio);
    document.getElementById("simulateNFCTap").addEventListener("click", simulateNFCTap);
    document.getElementById("downloadCard").addEventListener("click", downloadCard);
});
