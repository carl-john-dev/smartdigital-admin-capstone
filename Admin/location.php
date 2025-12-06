<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maps - Dashboard</title>
    <link rel="icon" type="icon" href="location.png"/>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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

        /* Add staggered animation for menu items */
        .sidebar-menu li:nth-child(1) span { transition-delay: 0.05s; }
        .sidebar-menu li:nth-child(2) span { transition-delay: 0.1s; }
        .sidebar-menu li:nth-child(3) span { transition-delay: 0.15s; }
        .sidebar-menu li:nth-child(4) span { transition-delay: 0.2s; }
        .sidebar-menu li:nth-child(5) span { transition-delay: 0.25s; }
        .sidebar-menu li:nth-child(6) span { transition-delay: 0.3s; }
        .sidebar-menu li:nth-child(7) span { transition-delay: 0.35s; }

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

        /* Map Container */
        .map-container {
            background: var(--card-bg);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .map-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .map-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary);
        }

        .map-controls {
            display: flex;
            gap: 10px;
        }

        .map-control-btn {
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

        .map-control-btn:hover {
            background: var(--secondary);
            transform: scale(1.05);
        }

        #map {
            height: 500px;
            width: 100%;
            border-radius: 8px;
            z-index: 1;
        }

        /* Location Cards */
        .location-card {
            background: var(--card-bg);
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 15px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .location-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--primary);
        }

        .location-card.active {
            border-left: 4px solid var(--primary);
            background: rgba(67, 97, 238, 0.05);
        }

        .location-title {
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--primary);
        }

        .location-address {
            font-size: 0.9rem;
            color: var(--gray);
            margin-bottom: 8px;
        }

        .location-type {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .type-government {
            background-color: #d1edff;
            color: #004085;
        }

        .type-education {
            background-color: #d1f7e4;
            color: #0f5132;
        }

        .type-commercial {
            background-color: #fff3cd;
            color: #856404;
        }

        .type-religious {
            background-color: #f8d7da;
            color: #721c24;
        }

        .dark-mode .type-government {
            background-color: #0d3c61;
            color: #7abfff;
        }

        .dark-mode .type-education {
            background-color: #1a3d2f;
            color: #75b798;
        }

        .dark-mode .type-commercial {
            background-color: #664d03;
            color: #ffda6a;
        }

        .dark-mode .type-religious {
            background-color: #5c1a22;
            color: #f1aeb5;
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
            
            #map {
                height: 400px;
            }
            
            .map-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .map-controls {
                width: 100%;
                justify-content: space-between;
            }
        }
        
        /* Leaflet Dark Mode */
        .dark-mode .leaflet-tile {
            filter: brightness(0.6) invert(1) contrast(3) hue-rotate(200deg) saturate(0.3) brightness(0.7);
        }
        
        .dark-mode .leaflet-container {
            background: #303030;
        }
        
        .dark-mode .leaflet-popup-content-wrapper {
            background: var(--card-bg);
            color: var(--text-color);
        }
        
        .dark-mode .leaflet-popup-tip {
            background: var(--card-bg);
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-tachometer-alt"></i> Dashboard</h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="index.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
            <li><a href="members.php"><i class="fas fa-users"></i> <span>Members</span></a></li>
            <li><a href="invoice.php"><i class="fas fa-file-invoice"></i> <span>Invoices</span></a></li>
            <li><a href="calendar.php"><i class="fas fa-calendar"></i> <span>Calendar</span></a></li>
            <li><a href="location.php"><i class="fas fa-map-marked-alt"></i><span>Location</span></a></li>
            <li><a href="request.php"><i class="fas fa-clipboard-list"></i> <span>Requests</span></a></li>
            <li><a href="login.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <h1>Maps & Locations</h1>
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
                <div class="stat-number">24</div>
                <div class="stat-label">Locations</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">5</div>
                <div class="stat-label">Barangays</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">12.3K</div>
                <div class="stat-label">Population</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">18.5</div>
                <div class="stat-label">Area (km²)</div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column - Map -->
            <div class="col-lg-8">
                <div class="map-container">
                    <div class="map-header">
                        <h2 class="map-title"><i class="fas fa-map-marker-alt"></i> Rosario, Cavite Map</h2>
                        <div class="map-controls">
                            <button class="map-control-btn" id="locateMe">
                                <i class="fas fa-location-arrow"></i> Locate Me
                            </button>
                            <button class="map-control-btn" id="resetView">
                                <i class="fas fa-sync-alt"></i> Reset View
                            </button>
                        </div>
                    </div>
                    <div id="map"></div>
                </div>
            </div>

            <!-- Right Column - Locations List -->
            <div class="col-lg-4">
                <div class="map-container">
                    <h3 class="section-title"><i class="fas fa-list-ul"></i> Key Locations</h3>
                    
                    <div class="location-card active" data-location="municipal-hall">
                        <div class="location-title">Rosario Municipal Hall</div>
                        <div class="location-address">J.P. Rizal Street, Rosario, Cavite</div>
                        <span class="location-type type-government">Government</span>
                    </div>
                    
                    <div class="location-card" data-location="public-market">
                        <div class="location-title">Rosario Public Market</div>
                        <div class="location-address">Tejeros Convention, Rosario, Cavite</div>
                        <span class="location-type type-commercial">Commercial</span>
                    </div>
                    
                    <div class="location-card" data-location="st-joseph">
                        <div class="location-title">St. Joseph Parish Church</div>
                        <div class="location-address">Poblacion, Rosario, Cavite</div>
                        <span class="location-type type-religious">Religious</span>
                    </div>
                    
                    <div class="location-card" data-location="rosario-national">
                        <div class="location-title">Rosario National High School</div>
                        <div class="location-address">Wawa II, Rosario, Cavite</div>
                        <span class="location-type type-education">Education</span>
                    </div>
                    
                    <div class="location-card" data-location="health-center">
                        <div class="location-title">Rosario Health Center</div>
                        <div class="location-address">Poblacion, Rosario, Cavite</div>
                        <span class="location-type type-government">Government</span>
                    </div>
                    
                    <div class="location-card" data-location="tejeros-convention">
                        <div class="location-title">Tejeros Convention Center</div>
                        <div class="location-address">Tejeros, Rosario, Cavite</div>
                        <span class="location-type type-government">Historical</span>
                    </div>
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
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- JavaScript for Map and Interactive Elements -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the map
            const map = L.map('map').setView([14.4160, 120.8541], 14);
            
            // Add tile layer (OpenStreetMap)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            
            // Define locations with coordinates and details
            const locations = {
                'municipal-hall': {
                    coords: [14.4160, 120.8541],
                    title: 'Rosario Municipal Hall',
                    description: 'Local government center of Rosario, Cavite',
                    type: 'government'
                },
                'public-market': {
                    coords: [14.4185, 120.8572],
                    title: 'Rosario Public Market',
                    description: 'Main public market in Rosario',
                    type: 'commercial'
                },
                'st-joseph': {
                    coords: [14.4145, 120.8532],
                    title: 'St. Joseph Parish Church',
                    description: 'Historical Catholic church in Rosario',
                    type: 'religious'
                },
                'rosario-national': {
                    coords: [14.4198, 120.8510],
                    title: 'Rosario National High School',
                    description: 'Public secondary school in Rosario',
                    type: 'education'
                },
                'health-center': {
                    coords: [14.4152, 120.8528],
                    title: 'Rosario Health Center',
                    description: 'Primary healthcare facility in Rosario',
                    type: 'government'
                },
                'tejeros-convention': {
                    coords: [14.4205, 120.8590],
                    title: 'Tejeros Convention Center',
                    description: 'Historical site of the 1897 Tejeros Convention',
                    type: 'government'
                }
            };
            
            // Create markers for each location
            const markers = {};
            Object.keys(locations).forEach(key => {
                const location = locations[key];
                const marker = L.marker(location.coords)
                    .addTo(map)
                    .bindPopup(`
                        <div>
                            <h4>${location.title}</h4>
                            <p>${location.description}</p>
                            <small>Type: ${location.type}</small>
                        </div>
                    `);
                
                markers[key] = marker;
            });
            
            // Location card click event
            document.querySelectorAll('.location-card').forEach(card => {
                card.addEventListener('click', function() {
                    // Remove active class from all cards
                    document.querySelectorAll('.location-card').forEach(c => {
                        c.classList.remove('active');
                    });
                    
                    // Add active class to clicked card
                    this.classList.add('active');
                    
                    // Get location key from data attribute
                    const locationKey = this.getAttribute('data-location');
                    
                    // Fly to the location on the map
                    if (locations[locationKey]) {
                        const location = locations[locationKey];
                        map.flyTo(location.coords, 16);
                        
                        // Open the popup for the marker
                        markers[locationKey].openPopup();
                    }
                });
            });
            
            // Locate Me button
            document.getElementById('locateMe').addEventListener('click', function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const userLocation = [position.coords.latitude, position.coords.longitude];
                            L.marker(userLocation)
                                .addTo(map)
                                .bindPopup('You are here!')
                                .openPopup();
                            map.flyTo(userLocation, 15);
                        },
                        function(error) {
                            alert('Unable to retrieve your location. Please ensure location services are enabled.');
                        }
                    );
                } else {
                    alert('Geolocation is not supported by this browser.');
                }
            });
            
            // Reset View button
            document.getElementById('resetView').addEventListener('click', function() {
                map.flyTo([14.4160, 120.8541], 14);
                
                // Remove active class from all cards
                document.querySelectorAll('.location-card').forEach(c => {
                    c.classList.remove('active');
                });
                
                // Add active class to municipal hall card
                document.querySelector('[data-location="municipal-hall"]').classList.add('active');
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

            // Add subtle animation to stats cards on page load
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('animate__animated', 'animate__fadeInUp');
            });
        });
    </script>
</body>
</html>