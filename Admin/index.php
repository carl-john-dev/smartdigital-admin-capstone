<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cavite Business Owners Club - Welcome Page</title>
    <link rel="icon" type="icon" href="CBOC LOGO.jpg"/>
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --accent: #e74c3c;
            --light: #ecf0f1;
            --dark: #2c3e50;
            --success: #27ae60;
            --warning: #f39c12;
            --announcements: #9b59b6;
            --messages: #1abc9c;
            --aboutus: #e67e22;
            --portfolio: #8e44ad;
            --qrcode: #16a085;
            --analytics: #d35400;
            --settings: #7f8c8d;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--dark);
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Animated Background */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .bg-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(52, 152, 219, 0.1);
            animation: float 15s infinite ease-in-out;
        }

        .shape:nth-child(1) {
            width: 400px;
            height: 400px;
            top: -100px;
            left: -100px;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 300px;
            height: 300px;
            top: 50%;
            right: -50px;
            animation-delay: 5s;
            background: rgba(231, 76, 60, 0.1);
        }

        .shape:nth-child(3) {
            width: 250px;
            height: 250px;
            bottom: -50px;
            left: 30%;
            animation-delay: 10s;
            background: rgba(46, 204, 113, 0.1);
        }

        .shape:nth-child(4) {
            width: 200px;
            height: 200px;
            top: 20%;
            left: 60%;
            animation-delay: 7s;
            background: rgba(230, 126, 34, 0.1);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        /* Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1.5rem 5%;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: var(--shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: var(--transition);
        }

        .navbar:hover {
            background: rgba(255, 255, 255, 1);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            transition: var(--transition);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .navbar:hover .logo-icon {
            transform: rotate(360deg) scale(1.1);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.3);
        }

        .navbar:hover .logo-icon img {
            transform: scale(1.1);
        }

        .logo-text h1 {
            font-size: 1.8rem;
            color: var(--primary);
            transition: var(--transition);
        }

        .navbar:hover .logo-text h1 {
            color: var(--secondary);
        }

        .logo-text span {
            color: var(--accent);
        }

        .logo-text small {
            display: block;
            font-size: 0.9rem;
            color: #666;
            margin-top: 2px;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: var(--transition);
            position: relative;
        }

        .nav-links a:hover {
            color: var(--secondary);
            background: rgba(52, 152, 219, 0.1);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--secondary);
            transition: var(--transition);
            transform: translateX(-50%);
        }

        .nav-links a:hover::after {
            width: 80%;
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 0 5%;
            margin-top: 80px;
        }

        .hero-content {
            max-width: 600px;
            animation: slideIn 1s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .hero h2 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            color: var(--primary);
            line-height: 1.2;
        }

        .hero h2 span {
            color: var(--accent);
            position: relative;
        }

        .hero h2 span::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--accent);
            transform: scaleX(0);
            transition: transform 0.5s ease;
            transform-origin: right;
        }

        .hero:hover h2 span::after {
            transform: scaleX(1);
            transform-origin: left;
        }

        .hero p {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 2rem;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            padding: 1rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: var(--secondary);
            color: white;
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }

        .btn-primary:hover {
            background: var(--primary);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(52, 152, 219, 0.4);
        }

        .btn-secondary {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-secondary:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-3px);
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
        }

        /* Features Section Title */
        .features {
            padding: 5rem 5% 2rem 5%;
            background: white;
            position: relative;
        }

        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title h3 {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .section-title p {
            color: #666;
            max-width: 600px;
            margin: 0 auto;
            font-size: 1.1rem;
        }

        /* ----- Slideshow Carousel ----- */
        .carousel-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 5%;
            position: relative;
        }

        .carousel {
            position: relative;
            width: 100%;
            height: 450px;
            overflow: hidden;
            border-radius: 20px;
            box-shadow: var(--shadow);
        }

        .carousel-inner {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .carousel-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.5s ease, visibility 0.5s ease;
            padding: 20px;
        }

        .carousel-slide.active {
            opacity: 1;
            visibility: visible;
        }

        /* card style matching original feature cards */
        .carousel-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: var(--shadow);
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid #eee;
            transition: var(--transition);
            overflow-y: auto;
        }
        .carousel-card::-webkit-scrollbar {
            width: 4px;
        }
        .carousel-card::-webkit-scrollbar-thumb {
            background: var(--secondary);
            border-radius: 10px;
        }

        .carousel-card .feature-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: white;
            font-size: 2rem;
            flex-shrink: 0;
        }

        .carousel-card h4 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }

        .carousel-card p {
            font-size: 1rem;
            color: #666;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        .carousel-card .feature-list {
            list-style: none;
            padding-left: 0;
            margin-top: 0;
            font-size: 1rem;
        }

        .carousel-card .feature-list li {
            margin-bottom: 0.6rem;
            position: relative;
            padding-left: 1.8rem;
            color: #555;
        }

        .carousel-card .feature-list li:before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--secondary);
            font-weight: bold;
            font-size: 1.1rem;
        }

        /* Carousel navigation */
        .carousel-nav {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
        }

        .carousel-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #ccc;
            cursor: pointer;
            transition: var(--transition);
            border: none;
        }

        .carousel-dot.active {
            background: var(--secondary);
            transform: scale(1.3);
        }

        .carousel-dot:hover {
            background: var(--primary);
        }

        .carousel-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: white;
            border: none;
            box-shadow: var(--shadow);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--primary);
            transition: var(--transition);
            z-index: 10;
        }

        .carousel-btn:hover {
            background: var(--secondary);
            color: white;
        }

        .carousel-btn.prev {
            left: 20px;
        }

        .carousel-btn.next {
            right: 20px;
        }

        /* Footer */
        footer {
            background: var(--dark);
            color: white;
            padding: 3rem 5%;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h4 {
            margin-bottom: 1rem;
            color: var(--light);
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-links i {
            width: 20px;
            color: var(--secondary);
        }

        .footer-links a {
            color: #ccc;
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: var(--secondary);
            padding-left: 5px;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-link {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: var(--transition);
        }

        .social-link:hover {
            background: var(--secondary);
            transform: rotate(15deg) scale(1.1);
        }

        /* Hidden login link in footer - subtle */
        .footer-login {
            margin-top: 1rem;
            text-align: center;
            font-size: 0.8rem;
            opacity: 0.5;
            transition: opacity 0.3s;
        }
        .footer-login:hover {
            opacity: 1;
        }
        .footer-login a {
            color: #aaa;
            text-decoration: none;
            border-bottom: 1px dotted #555;
        }
        .footer-login a:hover {
            color: var(--secondary);
            border-bottom-color: var(--secondary);
        }

        .copyright {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #ccc;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
            }
            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
                gap: 1rem;
            }
            .hero h2 {
                font-size: 2.5rem;
            }
            .cta-buttons {
                flex-direction: column;
                align-items: flex-start;
            }
            .btn {
                width: 100%;
                justify-content: center;
            }
            .carousel {
                height: 500px;
            }
            .carousel-btn {
                width: 40px;
                height: 40px;
            }
        }

        /* Loading animation */
        .loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            animation: fadeOut 1s ease 2s forwards;
        }

        .loader-content {
            text-align: center;
            color: white;
        }

        .spinner {
            width: 60px;
            height: 60px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            margin: 0 auto 1rem;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @keyframes fadeOut {
            to { opacity: 0; visibility: hidden; }
        }

        /* Scroll indicator */
        #scrollTop {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            background: var(--secondary);
            color: white;
            border-radius: 50%;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
            text-decoration: none;
        }
        
        #scrollTop:hover {
            background: var(--primary);
            transform: scale(1.1);
        }
        
        #scrollTop.show {
            opacity: 1;
            visibility: visible;
        }

        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body>
    <!-- Loading Screen -->
    <div class="loader">
        <div class="loader-content">
            <div class="spinner"></div>
            <h2>Loading</h2>
            <p>Please wait...</p>
        </div>
    </div>

    <!-- Animated Background -->
    <div class="bg-animation">
        <div class="bg-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
    </div>

    <!-- Navigation (Login button removed from here) -->
    <nav class="navbar">
        <div class="logo">
            <div class="logo-icon">
                <img src="CBOC LOGO.jpg" alt="CBOC Logo">
            </div>
            <div class="logo-text">
                <h1>CBOC <span></span></h1>
                <small>Cavite Business Owners Club</small>
            </div>
        </div>
        <div class="nav-links">
            <a href="#home">Home</a>
            <a href="#features">Features</a>
            <a href="#about">About</a>
            <a href="#contact">Contact</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h2>Welcome to <span>Cavite Business Owners Club</span></h2>
            <p>Streamline your community operations with our comprehensive management system. Efficiently handle announcements, messages, and showcase business portfolios all in one place.</p>
            
            <div class="cta-buttons">
                <a href="login.php" class="btn btn-primary btn-pulse">
                    <i class="fas fa-sign-in-alt"></i>
                    Get Started
                </a>
                <a href="#features" class="btn btn-secondary">
                    <i class="fas fa-play-circle"></i>
                    Explore Features
                </a>
            </div>

            <div class="quick-stats" style="margin-top: 3rem;">
                <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
                    <div>
                        <h3 style="color: var(--secondary); font-size: 2rem;">500+</h3>
                        <p>Active Members</p>
                    </div>
                    <div>
                        <h3 style="color: var(--announcements); font-size: 2rem;">50+</h3>
                        <p>Events Monthly</p>
                    </div>
                    <div>
                        <h3 style="color: var(--portfolio); font-size: 2rem;">100+</h3>
                        <p>Business Portfolios</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section Title -->
    <section class="features" id="features">
        <div class="section-title">
            <h3>Complete System Features</h3>
            <p>Discover all the powerful features that make our system the ultimate solution for business community management</p>
        </div>
    </section>

    <!-- Slideshow Carousel -->
    <div class="carousel-container">
        <div class="carousel">
            <button class="carousel-btn prev" onclick="prevSlide()">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="carousel-btn next" onclick="nextSlide()">
                <i class="fas fa-chevron-right"></i>
            </button>
            
            <div class="carousel-inner" id="carouselInner">
                <!-- Slide 1: Announcements -->
                <div class="carousel-slide active">
                    <div class="carousel-card">
                        <div class="feature-icon" style="background: linear-gradient(135deg, var(--announcements), #8e44ad);">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <h4 style="color: var(--announcements);">Announcements</h4>
                        <p>Broadcast important updates, events, and news instantly to all members.</p>
                        <ul class="feature-list">
                            <li>Real-time notifications</li>
                            <li>Scheduled announcements</li>
                            <li>Targeted messaging</li>
                            <li>Push notifications</li>
                        </ul>
                    </div>
                </div>
                <!-- Slide 2: Messages -->
                <div class="carousel-slide">
                    <div class="carousel-card">
                        <div class="feature-icon" style="background: linear-gradient(135deg, var(--messages), #16a085);">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h4 style="color: var(--messages);">Messaging</h4>
                        <p>Secure messaging for seamless communication between members.</p>
                        <ul class="feature-list">
                            <li>One-on-one chats</li>
                            <li>Group conversations</li>
                            <li>File sharing</li>
                            <li>Message history</li>
                        </ul>
                    </div>
                </div>
                <!-- Slide 3: About Us -->
                <div class="carousel-slide">
                    <div class="carousel-card">
                        <div class="feature-icon" style="background: linear-gradient(135deg, var(--aboutus), #d35400);">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <h4 style="color: var(--aboutus);">About Us</h4>
                        <p>Learn about CBOC's mission, vision, and rich history.</p>
                        <ul class="feature-list">
                            <li>Club history</li>
                            <li>Mission & vision</li>
                            <li>Team members</li>
                            <li>Achievements</li>
                        </ul>
                    </div>
                </div>
                <!-- Slide 4: E-Portfolio -->
                <div class="carousel-slide">
                    <div class="carousel-card">
                        <div class="feature-icon" style="background: linear-gradient(135deg, var(--portfolio), #8e44ad);">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <h4 style="color: var(--portfolio);">E-Portfolio</h4>
                        <p>Digital portfolio with NFC & QR codes for modern networking.</p>
                        <ul class="feature-list">
                            <li>Digital business cards</li>
                            <li>NFC technology support</li>
                            <li>QR code generation</li>
                            <li>Portfolio customization</li>
                        </ul>
                    </div>
                </div>
                <!-- Slide 5: QR Code -->
                <div class="carousel-slide">
                    <div class="carousel-card">
                        <div class="feature-icon" style="background: linear-gradient(135deg, var(--qrcode), #1abc9c);">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <h4 style="color: var(--qrcode);">QR Code</h4>
                        <p>Generate and manage QR codes for various assets and portfolios.</p>
                        <ul class="feature-list">
                            <li>Dynamic QR generation</li>
                            <li>Scan analytics</li>
                            <li>Custom designs</li>
                            <li>Bulk creation</li>
                        </ul>
                    </div>
                </div>
                <!-- Slide 6: Analytics -->
                <div class="carousel-slide">
                    <div class="carousel-card">
                        <div class="feature-icon" style="background: linear-gradient(135deg, var(--analytics), #e67e22);">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h4 style="color: var(--analytics);">Analytics</h4>
                        <p>Comprehensive insights and reports for data-driven decisions.</p>
                        <ul class="feature-list">
                            <li>Member engagement</li>
                            <li>Event analytics</li>
                            <li>Performance metrics</li>
                            <li>Custom reports</li>
                        </ul>
                    </div>
                </div>
                <!-- Slide 7: Settings -->
                <div class="carousel-slide">
                    <div class="carousel-card">
                        <div class="feature-icon" style="background: linear-gradient(135deg, var(--settings), #95a5a6);">
                            <i class="fas fa-cog"></i>
                        </div>
                        <h4 style="color: var(--settings);">Settings</h4>
                        <p>Comprehensive system configuration and personalization options.</p>
                        <ul class="feature-list">
                            <li>Profile management</li>
                            <li>Notification settings</li>
                            <li>Privacy controls</li>
                            <li>Security options</li>
                        </ul>
                    </div>
                </div>
                <!-- Slide 8: Admin Dashboard -->
                <div class="carousel-slide">
                    <div class="carousel-card">
                        <div class="feature-icon" style="background: linear-gradient(135deg, var(--secondary), var(--primary));">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        <h4>Admin Dashboard</h4>
                        <p>Complete control panel for system administrators.</p>
                        <ul class="feature-list">
                            <li>User management</li>
                            <li>Content moderation</li>
                            <li>System monitoring</li>
                            <li>Backup management</li>
                        </ul>
                    </div>
                </div>
                <!-- Slide 9: Event Management -->
                <div class="carousel-slide">
                    <div class="carousel-card">
                        <div class="feature-icon" style="background: linear-gradient(135deg, var(--portfolio), #2980b9);">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h4>Event Management</h4>
                        <p>Efficiently plan and track club events and activities.</p>
                        <ul class="feature-list">
                            <li>Calendar view</li>
                            <li>RSVP tracking</li>
                            <li>Reminders</li>
                            <li>Post-event reports</li>
                        </ul>
                    </div>
                </div>
                <!-- Slide 10: Digital Invoicing -->
                <div class="carousel-slide">
                    <div class="carousel-card">
                        <div class="feature-icon" style="background: linear-gradient(135deg, var(--accent), #c0392b);">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <h4>Digital Invoicing</h4>
                        <p>Generate and manage invoices for club transactions.</p>
                        <ul class="feature-list">
                            <li>Custom templates</li>
                            <li>Payment tracking</li>
                            <li>PDF export</li>
                            <li>Due date alerts</li>
                        </ul>
                    </div>
                </div>
                <!-- Slide 11: Member Directory -->
                <div class="carousel-slide">
                    <div class="carousel-card">
                        <div class="feature-icon" style="background: linear-gradient(135deg, var(--success), #27ae60);">
                            <i class="fas fa-address-book"></i>
                        </div>
                        <h4>Member Directory</h4>
                        <p>Browse and connect with fellow club members easily.</p>
                        <ul class="feature-list">
                            <li>Search & filter</li>
                            <li>Business categories</li>
                            <li>Contact info</li>
                            <li>Privacy controls</li>
                        </ul>
                    </div>
                </div>
                <!-- Slide 12: Custom Reports -->
                <div class="carousel-slide">
                    <div class="carousel-card">
                        <div class="feature-icon" style="background: linear-gradient(135deg, var(--warning), #f39c12);">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h4>Custom Reports</h4>
                        <p>Generate detailed reports for various club activities.</p>
                        <ul class="feature-list">
                            <li>Activity logs</li>
                            <li>Financial summaries</li>
                            <li>Export options</li>
                            <li>Scheduled reports</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Carousel Navigation Dots -->
        <div class="carousel-nav" id="carouselNav">
            <!-- Dots will be generated by JavaScript -->
        </div>
    </div>

    <!-- E-Portfolio Spotlight Section -->
    <section class="features" style="background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);" id="about">
        <div class="section-title">
            <h3 style="color: var(--portfolio);">E-Portfolio Spotlight</h3>
            <p>Revolutionary digital business card system with cutting-edge features</p>
        </div>

        <div style="max-width: 1200px; margin: 0 auto;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-top: 2rem;">
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: var(--shadow);">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                        <div style="width: 60px; height: 60px; background: var(--portfolio); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-qrcode" style="font-size: 1.5rem;"></i>
                        </div>
                        <h4 style="color: var(--portfolio);">QR Code Integration</h4>
                    </div>
                    <p>Generate and share QR codes for instant portfolio access. Perfect for networking events and business meetings.</p>
                </div>

                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: var(--shadow);">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                        <div style="width: 60px; height: 60px; background: var(--portfolio); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-microchip" style="font-size: 1.5rem;"></i>
                        </div>
                        <h4 style="color: var(--portfolio);">NFC Technology</h4>
                    </div>
                    <p>Tap-to-share functionality using NFC technology. Share your portfolio instantly with compatible devices.</p>
                </div>

                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: var(--shadow);">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                        <div style="width: 60px; height: 60px; background: var(--portfolio); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-share-alt" style="font-size: 1.5rem;"></i>
                        </div>
                        <h4 style="color: var(--portfolio);">Easy Sharing</h4>
                    </div>
                    <p>Share your portfolio via email, social media, or direct link with just one click.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer (with subtle login link) -->
    <footer id="contact">
        <div class="footer-content">
            <div class="footer-section">
                <h4>CBOC</h4>
                <p>Comprehensive management system for Cavite Business Owners Club. Featuring the revolutionary E-Portfolio system for modern business networking.</p>
                <div class="social-links">
                    <a href="https://www.facebook.com/groups/caviteonlinebusiness" class="social-link" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <div class="footer-section">
                <h4>System Features</h4>
                <ul class="footer-links">
                    <li><i class="fas fa-bullhorn"></i><a href="#features">Announcements</a></li>
                    <li><i class="fas fa-comments"></i><a href="#features">Messages</a></li>
                    <li><i class="fas fa-info-circle"></i><a href="#features">About Us</a></li>
                    <li><i class="fas fa-id-card"></i><a href="#features">E-Portfolio</a></li>
                    <li><i class="fas fa-qrcode"></i><a href="#features">QR Code</a></li>
                    <li><i class="fas fa-chart-bar"></i><a href="#features">Analytics</a></li>
                    <li><i class="fas fa-cog"></i><a href="#features">Settings</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h4>Contact Info</h4>
                <ul class="footer-links">
                    <li><i class="fas fa-map-marker-alt"></i> Rosario, Cavite, Philippines</li>
                    <li><i class="fas fa-phone"></i> (046) 123-4567</li>
                    <li><i class="fas fa-envelope"></i> info@cavitebusinessowners.club</li>
                    <li><i class="fas fa-clock"></i> Mon-Fri: 8:00 AM - 5:00 PM</li>
                    <li><i class="fas fa-globe"></i> www.cavitebusinessowners.club</li>
                </ul>
            </div>
        </div>

        <!-- Subtle login link in footer -->
        <div class="footer-login">
            <a href="login.php">← access →</a>
        </div>

        <div class="copyright">
            <p>&copy; 2026 Cavite Business Owners Club. All rights reserved.</p>
            <p style="margin-top: 10px; font-size: 0.9rem; color: #aaa;">Featuring Advanced E-Portfolio System with NFC Technology</p>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <a href="#home" id="scrollTop" style="display: flex; align-items: center; justify-content: center;">
        <i class="fas fa-arrow-up"></i>
    </a>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script>
        // Carousel functionality
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-slide');
        const totalSlides = slides.length;
        let autoPlayInterval;

        // Create navigation dots
        const navContainer = document.getElementById('carouselNav');
        for (let i = 0; i < totalSlides; i++) {
            const dot = document.createElement('button');
            dot.className = `carousel-dot ${i === 0 ? 'active' : ''}`;
            dot.setAttribute('onclick', `goToSlide(${i})`);
            navContainer.appendChild(dot);
        }

        const dots = document.querySelectorAll('.carousel-dot');

        function updateSlides(index) {
            // Remove active class from all slides and dots
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            // Add active class to current slide and dot
            slides[index].classList.add('active');
            dots[index].classList.add('active');
            
            currentSlide = index;
        }

        function nextSlide() {
            let next = currentSlide + 1;
            if (next >= totalSlides) {
                next = 0;
            }
            updateSlides(next);
        }

        function prevSlide() {
            let prev = currentSlide - 1;
            if (prev < 0) {
                prev = totalSlides - 1;
            }
            updateSlides(prev);
        }

        function goToSlide(index) {
            updateSlides(index);
        }

        // Auto play functionality - every 3.5 seconds
        function startAutoPlay() {
            autoPlayInterval = setInterval(nextSlide, 3500);
        }

        function stopAutoPlay() {
            clearInterval(autoPlayInterval);
        }

        // Start autoplay when page loads
        window.addEventListener('load', function() {
            startAutoPlay();
            
            // Hide loader
            const loader = document.querySelector('.loader');
            setTimeout(() => {
                loader.style.display = 'none';
            }, 2000);
        });

        // Pause autoplay when hovering over carousel
        const carousel = document.querySelector('.carousel');
        carousel.addEventListener('mouseenter', stopAutoPlay);
        carousel.addEventListener('mouseleave', startAutoPlay);

        // Scroll to top button functionality
        window.addEventListener('scroll', function() {
            const scrollTop = document.getElementById('scrollTop');
            if (window.scrollY > 300) {
                scrollTop.classList.add('show');
            } else {
                scrollTop.classList.remove('show');
            }
        });
    </script>
</body>
</html>