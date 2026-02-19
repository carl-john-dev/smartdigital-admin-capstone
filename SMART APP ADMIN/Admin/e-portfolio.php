<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Portfolio - Digital Business Card</title>
    <link rel="icon" type="icon" href="CBOC LOGO.jpg"/>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            --nfc-gold: #FFD700;
            --nfc-silver: #C0C0C0;
            --nfc-gradient: linear-gradient(135deg, #1a1f36 0%, #2d3748 100%);
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
            --nfc-gradient: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
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

        /* NFC Smart Card Styles */
        .nfc-card-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .nfc-card {
            background: var(--nfc-gradient);
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.3),
                0 0 60px rgba(67, 97, 238, 0.1),
                inset 0 0 0 1px rgba(255, 255, 255, 0.1),
                inset 0 0 20px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .nfc-card:hover {
            transform: translateY(-5px);
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.4),
                0 0 80px rgba(67, 97, 238, 0.15),
                inset 0 0 0 1px rgba(255, 255, 255, 0.1);
        }

        /* NFC Chip */
        .nfc-chip {
            position: absolute;
            top: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #FFD700, #FFA500);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #000;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
            animation: chip-glow 2s infinite alternate;
        }

        @keyframes chip-glow {
            0% { box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3); }
            100% { box-shadow: 0 4px 25px rgba(255, 215, 0, 0.5); }
        }

        /* NFC Waves Animation */
        .nfc-waves {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, 
                transparent,
                var(--primary),
                var(--secondary),
                var(--primary),
                transparent);
            animation: wave 3s infinite linear;
        }

        @keyframes wave {
            0% { background-position: -200px 0; }
            100% { background-position: 200px 0; }
        }

        /* NFC Status */
        .nfc-status {
            position: absolute;
            bottom: 20px;
            left: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            color: #10b981;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            animation: blink 2s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* QR Code */
        .qr-code {
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 70px;
            height: 70px;
            background: white;
            border-radius: 8px;
            padding: 5px;
            cursor: pointer;
            transition: transform 0.3s ease;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .qr-code:hover {
            transform: scale(1.1);
        }

        .qr-code img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 5px;
        }

        /* Profile Header */
        .profile-header {
            display: flex;
            align-items: center;
            gap: 30px;
            margin-bottom: 30px;
        }

        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 20px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3.5rem;
            font-weight: bold;
            color: white;
            box-shadow: 0 10px 30px rgba(67, 97, 238, 0.3);
            border: 4px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
        }

        .profile-info h1 {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 10px;
            background: linear-gradient(90deg, white, var(--success));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .profile-info .title {
            font-size: 1.3rem;
            color: #cbd5e1;
            margin-bottom: 10px;
        }

        .profile-info .company {
            font-size: 1.1rem;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }

        .stat-item {
            background: rgba(30, 41, 59, 0.5);
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .stat-item:hover {
            background: rgba(67, 97, 238, 0.1);
            border-color: rgba(67, 97, 238, 0.3);
            transform: translateY(-3px);
        }

        .stat-number {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--success);
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.95rem;
            color: #94a3b8;
        }

        /* Contact Info */
        .contact-info {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px;
            margin-bottom: 12px;
            border-radius: 10px;
            transition: background 0.3s ease;
        }

        .contact-item:hover {
            background: rgba(67, 97, 238, 0.1);
        }

        .contact-icon {
            width: 45px;
            height: 45px;
            background: rgba(67, 97, 238, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 20px;
            flex-shrink: 0;
        }

        /* Portfolio Gallery */
        .portfolio-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin: 30px 0;
        }

        .portfolio-item {
            background: rgba(30, 41, 59, 0.5);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }

        .portfolio-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            border-color: rgba(67, 97, 238, 0.3);
        }

        .portfolio-img {
            height: 150px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
        }

        .portfolio-content {
            padding: 20px;
        }

        .portfolio-content h4 {
            margin-bottom: 8px;
            font-size: 1.2rem;
            color: white;
        }

        .portfolio-content p {
            color: #94a3b8;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        /* Skills Section */
        .skills-section {
            margin: 30px 0;
        }

        .skill-item {
            margin-bottom: 20px;
        }

        .skill-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            color: white;
        }

        .skill-bar {
            height: 10px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            overflow: hidden;
        }

        .skill-progress {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 5px;
            transition: width 1.5s ease-in-out;
        }

        /* Social Links */
        .social-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 40px;
        }

        .social-link {
            width: 55px;
            height: 55px;
            background: rgba(30, 41, 59, 0.5);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 22px;
            transition: all 0.3s ease;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }

        .social-link:hover {
            background: var(--primary);
            transform: translateY(-3px) rotate(5deg);
            color: white;
        }

        /* NFC Tap Animation */
        .nfc-tap {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            height: 300px;
            pointer-events: none;
            opacity: 0;
            z-index: 1000;
            transition: opacity 0.3s ease;
        }

        .nfc-tap.active {
            opacity: 1;
        }

        .nfc-tap-inner {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 4px solid var(--primary);
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(0.8);
                opacity: 0.8;
            }
            100% {
                transform: scale(2);
                opacity: 0;
            }
        }

        /* Control Panel */
        .control-panel {
            background: var(--card-bg);
            border-radius: 15px;
            padding: 25px;
            margin-top: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-color);
        }

        .control-panel label {
            color: var(--text-color);
            font-weight: 500;
            margin-bottom: 8px;
        }

        .control-panel .form-control {
            background-color: var(--card-bg);
            color: var(--text-color);
            border: 1px solid var(--border-color);
        }

        .control-panel .form-control:focus {
            background-color: var(--card-bg);
            color: var(--text-color);
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }

        /* Card Color Options - IMPROVED CONTRAST */
        .color-options-container {
            margin-top: 10px;
        }

        .card-color-option {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
            margin-right: 8px;
            margin-bottom: 8px;
            display: inline-block;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .card-color-option:hover {
            transform: scale(1.15);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .card-color-option.active {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px var(--primary), 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .card-color-option::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.2), transparent);
            border-radius: 8px;
        }

        .color-label {
            position: absolute;
            bottom: 2px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            font-weight: bold;
            color: white;
            text-shadow: 0 1px 2px rgba(0,0,0,0.5);
            padding: 1px;
            text-transform: uppercase;
        }

        /* Theme colors with better text visibility */
        .theme-default {
            background: linear-gradient(135deg, #1a1f36, #2d3748);
        }

        .theme-dark {
            background: linear-gradient(135deg, #0f172a, #1e293b);
        }

        .theme-blue {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        }

        .theme-green {
            background: linear-gradient(135deg, #064e3b, #10b981);
        }

        .theme-orange {
            background: linear-gradient(135deg, #7c2d12, #ea580c);
        }

        .theme-purple {
            background: linear-gradient(135deg, #4c1d95, #8b5cf6);
        }

        .theme-red {
            background: linear-gradient(135deg, #7f1d1d, #ef4444);
        }

        .theme-teal {
            background: linear-gradient(135deg, #134e4a, #14b8a6);
        }

        /* Dark Mode Toggle */
        .dark-mode-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            width: 55px;
            height: 55px;
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

        /* Custom Color Picker */
        .custom-color-picker {
            margin-top: 15px;
            padding: 15px;
            background: rgba(var(--primary-rgb, 67, 97, 238), 0.05);
            border-radius: 10px;
            border: 1px solid var(--border-color);
        }

        .custom-color-inputs {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-top: 10px;
        }

        .color-input-group {
            display: flex;
            flex-direction: column;
        }

        .color-input-group small {
            font-size: 11px;
            color: var(--gray);
            margin-top: 2px;
        }

        /* Color Preview */
        .color-preview {
            width: 100%;
            height: 60px;
            border-radius: 10px;
            margin-top: 10px;
            background: linear-gradient(135deg, var(--preview-color-1, #1a1f36), var(--preview-color-2, #2d3748));
            border: 2px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            text-shadow: 0 1px 3px rgba(0,0,0,0.5);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }
            
            .profile-info h1 {
                font-size: 2.2rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .portfolio-gallery {
                grid-template-columns: repeat(2, 1fr);
            }
        }

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
            
            .sidebar-header, .sidebar-menu span {
                display: none;
            }
            
            .sidebar-menu i {
                margin-right: 0;
            }
            
            .profile-avatar {
                width: 120px;
                height: 120px;
                font-size: 2.8rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .portfolio-gallery {
                grid-template-columns: 1fr;
            }
            
            .nfc-card {
                padding: 25px;
            }
            
            .nfc-chip {
                width: 50px;
                height: 50px;
                font-size: 24px;
                top: 20px;
                right: 20px;
            }
            
            .card-color-option {
                width: 35px;
                height: 35px;
            }
        }

        @media (max-width: 480px) {
            .nfc-card {
                padding: 20px;
            }
            
            .profile-info h1 {
                font-size: 1.8rem;
            }
            
            .social-links {
                gap: 10px;
            }
            
            .social-link {
                width: 45px;
                height: 45px;
                font-size: 18px;
            }
            
            .custom-color-inputs {
                grid-template-columns: 1fr;
            }
        }

        /* Animation for elements */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease forwards;
        }

        /* Button Styles */
        .btn-custom {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
            color: white;
        }

        .btn-outline-custom {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
            padding: 10px 22px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-custom:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
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
            <li><a href="admin_profile.php"><i class="fas fa-id-card"></i> <span>Profile</span></a></li>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
            <li><a href="signup.php"><i class="fas fa-user-plus"></i> <span>Create Account</span></a></li>
            <li><a href="members.php"><i class="fas fa-users"></i> <span>Members</span></a></li>
            <li><a href="calendar.php"><i class="fas fa-calendar"></i> <span>Calendar</span></a></li>
            <li><a href="location.php"><i class="fas fa-map-marked-alt"></i><span>Location</span></a></li>
            <li><a href="request.php"><i class="fas fa-clipboard-list"></i> <span>Requests</span></a></li>
            <li><a href="archive.php" class=""><i class="fas fa-archive"></i> <span>Archive</span></a></li>
            <li><a href="logs.php"><i class="fas fa-history"></i> <span>Activity Logs</span></a></li>
            <li><a href="#" class="active"><i class="fas fa-id-card"></i> <span>E-Portfolio</span></a></li>
            <li><a href="rsvptracker.php"><i class="fas fa-calendar-check"></i> <span>RSVP Tracker</span></a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <h1>Digital Business Card & E-Portfolio</h1>
            <div class="user-info">
                <div class="user-avatar">AD</div>
                <div>
                    <div class="fw-bold">Admin User</div>
                    <small class="text-muted">Administrator</small>
                </div>
            </div>
        </div>

        <!-- NFC Tap Animation -->
        <div class="nfc-tap" id="nfcTap">
            <div class="nfc-tap-inner"></div>
        </div>

        <div class="nfc-card-container">
            <!-- NFC Smart Card -->
            <div class="nfc-card" id="nfcCard">
                <!-- NFC Waves Animation -->
                <div class="nfc-waves"></div>
                
                <!-- NFC Chip -->
                <div class="nfc-chip">
                    <i class="fas fa-microchip"></i>
                </div>
                
                <!-- NFC Status -->
                <div class="nfc-status">
                    <div class="status-dot"></div>
                    <span>NFC Ready</span>
                </div>
                
                <!-- QR Code -->
                <div class="qr-code" id="qrCode">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=https://yourportfolio.com&format=svg" alt="QR Code">
                </div>

                <!-- Profile Header -->
                <div class="profile-header">
                    <div class="profile-avatar" id="userAvatar">
                        CJ
                    </div>
                    <div class="profile-info">
                        <h1 id="userName">Carl John D. Anthony</h1>
                        <div class="title" id="userTitle">Front-end Developer</div>
                        <div class="company" id="userCompany">
                            <i class="fas fa-building"></i>
                            TechVision Inc.
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="stats-grid" id="statsGrid">
                    <div class="stat-item">
                        <div class="stat-number" id="statProjects">47</div>
                        <div class="stat-label">Projects Completed</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" id="statClients">128</div>
                        <div class="stat-label">Happy Clients</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" id="statExperience">8+</div>
                        <div class="stat-label">Years Experience</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" id="statRating">4.9</div>
                        <div class="stat-label">Client Rating</div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="contact-info">
                    <h4><i class="fas fa-id-card me-2"></i>Contact Information</h4>
                    <div class="contact-item" id="contactEmail">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Email</div>
                            <div class="text-muted">tc.carljohn.anthony@cvsu.edu.ph</div>
                        </div>
                    </div>
                    <div class="contact-item" id="contactPhone">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Phone</div>
                            <div class="text-muted">09485808806</div>
                        </div>
                    </div>
                    <div class="contact-item" id="contactLocation">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Location</div>
                            <div class="text-muted">Kawit, Cavite</div>
                        </div>
                    </div>
                </div>

                <!-- Portfolio Gallery -->
                <div class="portfolio-gallery">
                    <div class="portfolio-item">
                        <div class="portfolio-img">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div class="portfolio-content">
                            <h4>Mobile App Design</h4>
                            <p>Cross-platform mobile application with React Native and Firebase backend</p>
                        </div>
                    </div>
                    <div class="portfolio-item">
                        <div class="portfolio-img">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="portfolio-content">
                            <h4>E-commerce Platform</h4>
                            <p>Full-stack e-commerce solution with real-time inventory and payment processing</p>
                        </div>
                    </div>
                    <div class="portfolio-item">
                        <div class="portfolio-img">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="portfolio-content">
                            <h4>Analytics Dashboard</h4>
                            <p>Real-time data visualization and reporting system with interactive charts</p>
                        </div>
                    </div>
                </div>

                <!-- Skills Section -->
                <div class="skills-section">
                    <h4><i class="fas fa-code me-2"></i>Technical Skills</h4>
                    <div class="skill-item">
                        <div class="skill-info">
                            <span>React.js & Next.js</span>
                            <span>95%</span>
                        </div>
                        <div class="skill-bar">
                            <div class="skill-progress" style="width: 95%;"></div>
                        </div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-info">
                            <span>Node.js & Express</span>
                            <span>90%</span>
                        </div>
                        <div class="skill-bar">
                            <div class="skill-progress" style="width: 90%;"></div>
                        </div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-info">
                            <span>UI/UX Design</span>
                            <span>85%</span>
                        </div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-info">
                            <span>Python & Django</span>
                            <span>80%</span>
                        </div>
                        <div class="skill-bar">
                            <div class="skill-progress" style="width: 80%;"></div>
                        </div>
                    </div>
                </div>

                <!-- Social Links -->
                <div class="social-links">
                    <a href="#" class="social-link" id="socialLinkedin">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="#" class="social-link" id="socialGithub">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="#" class="social-link" id="socialTwitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="social-link" id="socialDribbble">
                        <i class="fab fa-dribbble"></i>
                    </a>
                    <a href="#" class="social-link" id="socialBehance">
                        <i class="fab fa-behance"></i>
                    </a>
                </div>
            </div>

            <!-- Control Panel -->
            <div class="control-panel">
                <h4><i class="fas fa-cog me-2"></i>E-Portfolio Controls</h4>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="editName" value="Alex Morgan">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Job Title</label>
                            <input type="text" class="form-control" id="editTitle" value="Senior Full Stack Developer">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Company</label>
                            <input type="text" class="form-control" id="editCompany" value="TechVision Inc.">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="editEmail" value="alex.morgan@techvision.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="editPhone" value="+1 (555) 123-4567">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Avatar Initials</label>
                            <input type="text" class="form-control" id="editAvatar" value="AM" maxlength="2">
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Card Color Theme</label>
                        <div class="color-options-container">
                            <div class="card-color-option theme-default active" data-theme="default" data-color1="#1a1f36" data-color2="#2d3748">
                                <span class="color-label">Default</span>
                            </div>
                            <div class="card-color-option theme-dark" data-theme="dark" data-color1="#0f172a" data-color2="#1e293b">
                                <span class="color-label">Dark</span>
                            </div>
                            <div class="card-color-option theme-blue" data-theme="blue" data-color1="#1e3a8a" data-color2="#3b82f6">
                                <span class="color-label">Blue</span>
                            </div>
                            <div class="card-color-option theme-green" data-theme="green" data-color1="#064e3b" data-color2="#10b981">
                                <span class="color-label">Green</span>
                            </div>
                            <div class="card-color-option theme-orange" data-theme="orange" data-color1="#7c2d12" data-color2="#ea580c">
                                <span class="color-label">Orange</span>
                            </div>
                            <div class="card-color-option theme-purple" data-theme="purple" data-color1="#4c1d95" data-color2="#8b5cf6">
                                <span class="color-label">Purple</span>
                            </div>
                            <div class="card-color-option theme-red" data-theme="red" data-color1="#7f1d1d" data-color2="#ef4444">
                                <span class="color-label">Red</span>
                            </div>
                            <div class="card-color-option theme-teal" data-theme="teal" data-color1="#134e4a" data-color2="#14b8a6">
                                <span class="color-label">Teal</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">QR Code Data</label>
                        <input type="text" class="form-control" id="editQRCode" value="https://yourportfolio.com" placeholder="URL for QR Code">
                    </div>
                </div>

                <!-- Custom Color Picker -->
                <div class="custom-color-picker">
                    <label class="form-label">Custom Color Theme</label>
                    <div class="custom-color-inputs">
                        <div class="color-input-group">
                            <input type="color" class="form-control" id="customColor1" value="#1a1f36">
                            <small>Top Color</small>
                        </div>
                        <div class="color-input-group">
                            <input type="color" class="form-control" id="customColor2" value="#2d3748">
                            <small>Bottom Color</small>
                        </div>
                    </div>
                    <div class="color-preview" id="customColorPreview">
                        Custom Preview
                    </div>
                    <button class="btn btn-sm btn-primary mt-2" onclick="applyCustomColor()">
                        <i class="fas fa-palette me-1"></i> Apply Custom Color
                    </button>
                </div>
                
                <div class="d-grid gap-2 mt-3">
                    <button class="btn btn-custom" onclick="updatePortfolio()">
                        <i class="fas fa-sync me-2"></i>Update Portfolio
                    </button>
                    <button class="btn btn-outline-custom" onclick="simulateNFCTap()">
                        <i class="fas fa-tap me-2"></i>Simulate NFC Tap
                    </button>
                    <button class="btn btn-outline-custom" onclick="downloadCard()">
                        <i class="fas fa-download me-2"></i>Download as PDF
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
            window.applyCustomColor = function() {
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
            window.simulateNFCTap = function() {
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
            window.updatePortfolio = function() {
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
            window.downloadCard = function() {
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
                // Create notification element
                const notification = document.createElement('div');
                notification.className = `alert alert-${type} position-fixed`;
                notification.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    z-index: 1000;
                    min-width: 300px;
                    animation: slideIn 0.3s ease;
                    background: ${type === 'success' ? '#10b981' : type === 'info' ? '#3b82f6' : '#ef4444'};
                    color: white;
                    border: none;
                `;
                notification.innerHTML = `
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
                    ${message}
                `;
                
                // Add to body
                document.body.appendChild(notification);
                
                // Remove after 3 seconds
                setTimeout(() => {
                    notification.style.animation = 'slideOut 0.3s ease';
                    setTimeout(() => {
                        document.body.removeChild(notification);
                    }, 300);
                }, 3000);
            }

            // Add CSS animations for notifications
            const notificationStyle = document.createElement('style');
            notificationStyle.textContent = `
                @keyframes slideIn {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                
                @keyframes slideOut {
                    from {
                        transform: translateX(0);
                        opacity: 1;
                    }
                    to {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(notificationStyle);

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
        });
    </script>
</body>
</html>