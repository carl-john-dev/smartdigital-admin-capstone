<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
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
            width: 100%;
            position: relative;
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
            width: min(400px, 80vw);
            height: min(400px, 80vw);
            top: -100px;
            left: -100px;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: min(300px, 60vw);
            height: min(300px, 60vw);
            top: 50%;
            right: -50px;
            animation-delay: 5s;
            background: rgba(231, 76, 60, 0.1);
        }

        .shape:nth-child(3) {
            width: min(250px, 50vw);
            height: min(250px, 50vw);
            bottom: -50px;
            left: 30%;
            animation-delay: 10s;
            background: rgba(46, 204, 113, 0.1);
        }

        .shape:nth-child(4) {
            width: min(200px, 40vw);
            height: min(200px, 40vw);
            top: 20%;
            left: 60%;
            animation-delay: 7s;
            background: rgba(230, 126, 34, 0.1);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        /* Navigation with Burger Menu */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 3%;
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
            gap: clamp(10px, 2vw, 15px);
            flex-wrap: wrap;
        }

        .logo-icon {
            width: clamp(40px, 8vw, 60px);
            height: clamp(40px, 8vw, 60px);
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
            font-size: clamp(1.2rem, 4vw, 1.8rem);
            color: var(--primary);
            transition: var(--transition);
            line-height: 1.2;
        }

        .navbar:hover .logo-text h1 {
            color: var(--secondary);
        }

        .logo-text span {
            color: var(--accent);
        }

        .logo-text small {
            display: block;
            font-size: clamp(0.7rem, 2vw, 0.9rem);
            color: #666;
            margin-top: 2px;
        }

        /* Burger Menu Styles */
        .burger-menu {
            display: none;
            flex-direction: column;
            cursor: pointer;
            z-index: 1001;
            padding: 10px;
        }

        .burger-bar {
            width: 30px;
            height: 3px;
            background: var(--primary);
            margin: 3px 0;
            transition: var(--transition);
            border-radius: 3px;
        }

        /* Navigation Links - Desktop */
        .nav-links {
            display: flex;
            gap: clamp(0.5rem, 2vw, 2rem);
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            padding: 0.5rem clamp(0.5rem, 1.5vw, 1rem);
            border-radius: 5px;
            transition: var(--transition);
            position: relative;
            font-size: clamp(0.9rem, 2.5vw, 1rem);
            white-space: nowrap;
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

        /* Mobile Styles */
        @media (max-width: 768px) {
            .burger-menu {
                display: flex;
            }

            .nav-links {
                position: fixed;
                top: 0;
                right: -100%;
                width: 80%;
                max-width: 300px;
                height: 100vh;
                background: white;
                flex-direction: column;
                justify-content: flex-start;
                padding: 80px 2rem 2rem;
                transition: right 0.3s ease;
                box-shadow: var(--shadow);
                z-index: 1000;
                gap: 1rem;
            }

            .nav-links.active {
                right: 0;
            }

            .nav-links a {
                width: 100%;
                text-align: left;
                padding: 1rem;
                font-size: 1.1rem;
                white-space: normal;
                border-bottom: 1px solid #eee;
            }

            .nav-links a:last-child {
                border-bottom: none;
            }

            .nav-links a::after {
                display: none;
            }

            .nav-links a:hover {
                background: rgba(52, 152, 219, 0.1);
                padding-left: 1.5rem;
            }

            /* Burger Animation */
            .burger-menu.active .burger-bar:nth-child(1) {
                transform: rotate(45deg) translate(8px, 8px);
            }

            .burger-menu.active .burger-bar:nth-child(2) {
                opacity: 0;
            }

            .burger-menu.active .burger-bar:nth-child(3) {
                transform: rotate(-45deg) translate(5px, -5px);
            }
        }

        /* Overlay for mobile menu */
        .menu-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .menu-overlay.active {
            display: block;
            opacity: 1;
        }

        @media (max-width: 768px) {
            .menu-overlay.active {
                display: block;
            }
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 80px 3% 0 3%;
            margin-top: 0;
            width: 100%;
        }

        @media (max-width: 768px) {
            .hero {
                padding: 100px 3% 0 3%;
                min-height: auto;
                padding-bottom: 3rem;
            }
        }

        .hero-content {
            max-width: min(600px, 100%);
            animation: slideIn 1s ease-out;
            width: 100%;
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
            font-size: clamp(2rem, 6vw, 3.5rem);
            margin-bottom: 1rem;
            color: var(--primary);
            line-height: 1.2;
            word-wrap: break-word;
        }

        .hero h2 span {
            color: var(--accent);
            position: relative;
            display: inline-block;
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
            font-size: clamp(1rem, 3vw, 1.2rem);
            color: #666;
            margin-bottom: 2rem;
            word-wrap: break-word;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        @media (max-width: 480px) {
            .cta-buttons {
                flex-direction: column;
                width: 100%;
            }
        }

        .btn {
            padding: clamp(0.8rem, 2vw, 1rem) clamp(1.5rem, 4vw, 2rem);
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            font-size: clamp(0.9rem, 2.5vw, 1rem);
            white-space: nowrap;
        }

        @media (max-width: 480px) {
            .btn {
                white-space: normal;
                width: 100%;
                text-align: center;
            }
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

        .quick-stats {
            margin-top: 3rem;
            width: 100%;
        }

        .quick-stats > div {
            display: flex;
            gap: clamp(1rem, 4vw, 2rem);
            flex-wrap: wrap;
            justify-content: flex-start;
        }

        @media (max-width: 480px) {
            .quick-stats > div {
                justify-content: center;
                text-align: center;
            }
        }

        .quick-stats h3 {
            font-size: clamp(1.5rem, 4vw, 2rem);
        }

        /* Features Section Title */
        .features {
            padding: clamp(3rem, 8vw, 5rem) 3% 0 3%;
            background: white;
            position: relative;
            width: 100%;
        }

        .section-title {
            text-align: center;
            margin-bottom: 2rem;
            width: 100%;
        }

        .section-title h3 {
            font-size: clamp(1.8rem, 5vw, 2.5rem);
            color: var(--primary);
            margin-bottom: 1rem;
            padding: 0 3%;
            word-wrap: break-word;
        }

        .section-title p {
            color: #666;
            max-width: min(600px, 100%);
            margin: 0 auto;
            font-size: clamp(0.95rem, 2.5vw, 1.1rem);
            padding: 0 3%;
            word-wrap: break-word;
        }

        /* ----- Slideshow Carousel ----- */
        .carousel-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 0 15px 3rem 15px;
            position: relative;
            width: 100%;
            background: white;
        }

        .carousel {
            position: relative;
            width: 100%;
            height: 500px;
            overflow: hidden;
            border-radius: 20px;
            box-shadow: var(--shadow);
        }

        @media (max-width: 768px) {
            .carousel {
                height: 550px;
            }
        }

        @media (max-width: 480px) {
            .carousel {
                height: 600px;
            }
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
            padding: clamp(1.5rem, 4vw, 2rem);
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
            width: clamp(60px, 8vw, 70px);
            height: clamp(60px, 8vw, 70px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: white;
            font-size: clamp(1.5rem, 4vw, 2rem);
            flex-shrink: 0;
        }

        .carousel-card h4 {
            font-size: clamp(1.4rem, 4vw, 1.8rem);
            margin-bottom: 1rem;
            word-wrap: break-word;
        }

        .carousel-card p {
            font-size: clamp(0.95rem, 2.5vw, 1rem);
            color: #666;
            margin-bottom: 1.5rem;
            line-height: 1.5;
            word-wrap: break-word;
        }

        .carousel-card .feature-list {
            list-style: none;
            padding-left: 0;
            margin-top: 0;
            font-size: clamp(0.9rem, 2.5vw, 1rem);
        }

        .carousel-card .feature-list li {
            margin-bottom: 0.6rem;
            position: relative;
            padding-left: 1.8rem;
            color: #555;
            word-wrap: break-word;
        }

        .carousel-card .feature-list li:before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--secondary);
            font-weight: bold;
            font-size: 1.1rem;
        }

        /* About Us specific styles */
        .about-section {
            margin-bottom: 1.5rem;
        }

        .about-section h5 {
            font-size: 1.3rem;
            color: var(--aboutus);
            margin-bottom: 0.5rem;
            border-left: 4px solid var(--aboutus);
            padding-left: 1rem;
        }

        .about-section p {
            margin-bottom: 1rem;
            color: #555;
        }

        .values-box {
            background: linear-gradient(135deg, #fff3e0, #ffe0b2);
            padding: 1rem;
            border-radius: 10px;
            margin: 1rem 0;
            border-left: 4px solid var(--aboutus);
        }

        .values-box p {
            margin-bottom: 0;
            color: var(--dark);
            font-weight: 500;
        }

        .founded-badge {
            display: inline-block;
            background: var(--aboutus);
            color: white;
            padding: 0.3rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        /* Carousel navigation */
        .carousel-nav {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
            padding: 0 3%;
        }

        .carousel-dot {
            width: clamp(10px, 2vw, 12px);
            height: clamp(10px, 2vw, 12px);
            border-radius: 50%;
            background: #ccc;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            padding: 0;
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
            width: clamp(40px, 6vw, 50px);
            height: clamp(40px, 6vw, 50px);
            border-radius: 50%;
            background: white;
            border: none;
            box-shadow: var(--shadow);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: clamp(1rem, 3vw, 1.2rem);
            color: var(--primary);
            transition: var(--transition);
            z-index: 10;
        }

        @media (max-width: 480px) {
            .carousel-btn {
                width: 35px;
                height: 35px;
            }
        }

        .carousel-btn:hover {
            background: var(--secondary);
            color: white;
        }

        .carousel-btn.prev {
            left: 10px;
        }

        .carousel-btn.next {
            right: 10px;
        }

        @media (max-width: 768px) {
            .carousel-btn.prev {
                left: 5px;
            }
            .carousel-btn.next {
                right: 5px;
            }
        }

        /* E-Portfolio Spotlight Section */
        .features:last-of-type {
            background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
            padding: clamp(3rem, 8vw, 5rem) 3%;
        }

        .features:last-of-type > div {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .features:last-of-type > div > div {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(280px, 100%), 1fr));
            gap: clamp(1rem, 3vw, 2rem);
            margin-top: 2rem;
            width: 100%;
        }

        .features:last-of-type > div > div > div {
            background: white;
            padding: clamp(1.5rem, 4vw, 2rem);
            border-radius: 15px;
            box-shadow: var(--shadow);
            width: 100%;
            transition: var(--transition);
        }

        .features:last-of-type > div > div > div:hover {
            transform: translateY(-5px);
        }

        .features:last-of-type > div > div > div > div {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .features:last-of-type > div > div > div > div > div {
            width: clamp(50px, 8vw, 60px);
            height: clamp(50px, 8vw, 60px);
            background: var(--portfolio);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
        }

        .features:last-of-type > div > div > div > div > div i {
            font-size: clamp(1.2rem, 3vw, 1.5rem);
        }

        .features:last-of-type > div > div > div > div h4 {
            font-size: clamp(1.1rem, 3vw, 1.4rem);
            color: var(--portfolio);
            word-wrap: break-word;
            flex: 1;
        }

        .features:last-of-type > div > div > div p {
            font-size: clamp(0.9rem, 2.5vw, 1rem);
            color: #666;
            word-wrap: break-word;
        }

        /* Footer */
        footer {
            background: var(--dark);
            color: white;
            padding: clamp(2rem, 6vw, 3rem) 3%;
            width: 100%;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(240px, 100%), 1fr));
            gap: clamp(1.5rem, 4vw, 2rem);
            margin-bottom: 2rem;
            width: 100%;
        }

        .footer-section {
            width: 100%;
        }

        .footer-section h4 {
            margin-bottom: 1rem;
            color: var(--light);
            font-size: clamp(1.1rem, 3vw, 1.3rem);
        }

        .footer-section p {
            font-size: clamp(0.9rem, 2.5vw, 1rem);
            word-wrap: break-word;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            font-size: clamp(0.85rem, 2.5vw, 0.95rem);
        }

        .footer-links i {
            width: 20px;
            color: var(--secondary);
            flex-shrink: 0;
        }

        .footer-links a {
            color: #ccc;
            text-decoration: none;
            transition: var(--transition);
            word-wrap: break-word;
            flex: 1;
        }

        .footer-links a:hover {
            color: var(--secondary);
            padding-left: 5px;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        .social-link {
            width: clamp(35px, 6vw, 40px);
            height: clamp(35px, 6vw, 40px);
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: var(--transition);
            font-size: clamp(0.9rem, 2.5vw, 1rem);
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
            font-size: clamp(0.75rem, 2vw, 0.8rem);
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

        .copyright p {
            font-size: clamp(0.8rem, 2.5vw, 0.9rem);
            word-wrap: break-word;
            padding: 0 3%;
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
            padding: 0 3%;
        }

        .spinner {
            width: clamp(50px, 10vw, 60px);
            height: clamp(50px, 10vw, 60px);
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
            bottom: clamp(10px, 3vh, 20px);
            right: clamp(10px, 3vw, 20px);
            display: flex;
            align-items: center;
            justify-content: center;
            width: clamp(40px, 8vw, 50px);
            height: clamp(40px, 8vw, 50px);
            background: var(--secondary);
            color: white;
            border-radius: 50%;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
            text-decoration: none;
            font-size: clamp(1rem, 2.5vw, 1.2rem);
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

        /* Additional responsive fixes */
        img {
            max-width: 100%;
            height: auto;
        }

        iframe {
            max-width: 100%;
        }

        /* Container queries for better responsiveness */
        .container {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }

        /* Touch-friendly improvements */
        @media (hover: none) and (pointer: coarse) {
            .btn:hover {
                transform: none;
            }
            
            .social-link:hover {
                transform: none;
            }
            
            .carousel-btn {
                width: 44px;
                height: 44px;
            }
        }

        /* Landscape mode fixes */
        @media (max-width: 900px) and (orientation: landscape) {
            .hero {
                min-height: auto;
                padding-top: 120px;
            }
            
            .carousel {
                height: 400px;
            }
            
            .navbar {
                padding: 0.5rem 3%;
            }
        }

        /* Ensure text doesn't overflow on very small screens */
        @media (max-width: 320px) {
            .hero h2 {
                font-size: 1.6rem;
            }
            
            .btn {
                padding: 0.7rem 1rem;
                font-size: 0.85rem;
            }
            
            .logo-text h1 {
                font-size: 1rem;
            }
            
            .logo-text small {
                font-size: 0.65rem;
            }
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

    <!-- Navigation with Burger Menu -->
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
        
        <!-- Burger Menu Icon -->
        <div class="burger-menu" id="burgerMenu">
            <div class="burger-bar"></div>
            <div class="burger-bar"></div>
            <div class="burger-bar"></div>
        </div>

        <!-- Navigation Links -->
        <div class="nav-links" id="navLinks">
            <a href="#home">Home</a>
            <a href="#features">Features</a>
            <a href="#about">About</a>
            <a href="#contact">Contact</a>
        </div>
    </nav>

    <!-- Overlay for mobile menu -->
    <div class="menu-overlay" id="menuOverlay"></div>

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

            <div class="quick-stats">
                <div>
                    <div>
                        <h3 style="color: var(--secondary);">500+</h3>
                        <p>Active Members</p>
                    </div>
                    <div>
                        <h3 style="color: var(--announcements);">50+</h3>
                        <p>Events Monthly</p>
                    </div>
                    <div>
                        <h3 style="color: var(--portfolio);">100+</h3>
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

    <!-- About CBOC Section (formerly E-Portfolio Spotlight) -->
    <section class="features" id="about" style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1556761175-b413da4baf72?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1974&q=80'); background-size: cover; background-position: center; background-attachment: fixed;">
        <div class="section-title">
            <h3 style="color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">About Cavite Business Owners Club</h3>
            <p style="color: #f0f0f0; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">Empowering Cavite entrepreneurs through collaboration, innovation, and sustainable growth</p>
        </div>

        <div style="max-width: 1200px; margin: 0 auto;">

            <!-- BACKGROUND -->
            <div style="max-width: 1000px; margin: 0 auto 3rem auto; background: rgba(255,255,255,0.85); backdrop-filter: blur(8px); padding: 2rem 2.5rem; border-radius: 15px; text-align: center; box-shadow: var(--shadow);">
                <p id="backgroundText1" style="font-size: 1.2rem; line-height: 1.6; color: #333;"></p>
                <p id="backgroundText2" style="font-size: 1.2rem; line-height: 1.6; color: #333; margin-top: 1rem;"></p>
            </div>

            <!-- ABOUT US -->
            <div style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); padding: 2rem; border-radius: 15px; box-shadow: var(--shadow); margin-bottom: 2rem; border: 1px solid rgba(255,255,255,0.2);">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                    <div style="width: 60px; height: 60px; background: var(--aboutus); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; box-shadow: 0 4px 10px rgba(230, 126, 34, 0.3);">
                        <i class="fas fa-info-circle" style="font-size: 1.5rem;"></i>
                    </div>
                    <h4 style="color: #e67e22; font-size: 1.8rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">ABOUT US</h4>
                </div>
                <p id="aboutUsText1" style="font-size: 1.1rem; line-height: 1.6; color: #333; margin-bottom: 1rem; font-weight: 400;"></p>
                <p id="aboutUsText2" style="font-size: 1.1rem; line-height: 1.6; color: #333; font-weight: 400;"></p>
            </div>

            <!-- MISSION & VISION GRID -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr)); gap: 2rem; margin-bottom: 2rem;">
                <!-- MISSION -->
                <div style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); padding: 2rem; border-radius: 15px; box-shadow: var(--shadow); border: 1px solid rgba(255,255,255,0.2);">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                        <div style="width: 60px; height: 60px; background: var(--aboutus); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; box-shadow: 0 4px 10px rgba(230, 126, 34, 0.3);">
                            <i class="fas fa-bullseye" style="font-size: 1.5rem;"></i>
                        </div>
                        <h4 style="color: #e67e22; font-size: 1.8rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">MISSION</h4>
                    </div>
                    <p id="missionText" style="font-size: 1.1rem; line-height: 1.6; color: #333; font-weight: 400;"></p>
                </div>

                <!-- VISION -->
                <div style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); padding: 2rem; border-radius: 15px; box-shadow: var(--shadow); border: 1px solid rgba(255,255,255,0.2);">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                        <div style="width: 60px; height: 60px; background: var(--aboutus); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; box-shadow: 0 4px 10px rgba(230, 126, 34, 0.3);">
                            <i class="fas fa-eye" style="font-size: 1.5rem;"></i>
                        </div>
                        <h4 style="color: #e67e22; font-size: 1.8rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">VISION</h4>
                    </div>
                    <p id="visionText" style="font-size: 1.1rem; line-height: 1.6; color: #333; font-weight: 400;"></p>
                </div>
            </div>

            <!-- COMPANY VALUES & FOUNDED -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr)); gap: 2rem;">
                <!-- COMPANY VALUES -->
                <div style="background: linear-gradient(135deg, rgba(255, 243, 224, 0.95), rgba(255, 224, 178, 0.95)); backdrop-filter: blur(10px); padding: 2rem; border-radius: 15px; box-shadow: var(--shadow); border-left: 4px solid var(--aboutus);">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                        <div style="width: 60px; height: 60px; background: var(--aboutus); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; box-shadow: 0 4px 10px rgba(230, 126, 34, 0.3);">
                            <i class="fas fa-star" style="font-size: 1.5rem;"></i>
                        </div>
                        <h4 style="color: #e67e22; font-size: 1.8rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">COMPANY VALUES</h4>
                    </div>
                    <p id="valuesText" style="font-size: 1.1rem; line-height: 1.6; color: #2c3e50; font-weight: 500;"></p>
                </div>

                <!-- FOUNDED -->
                <div style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); padding: 2rem; border-radius: 15px; box-shadow: var(--shadow); display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; border: 1px solid rgba(255,255,255,0.2);">
                    <div style="width: 80px; height: 80px; background: var(--aboutus); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; margin-bottom: 1rem; box-shadow: 0 4px 10px rgba(230, 126, 34, 0.3);">
                        <i class="fas fa-calendar" style="font-size: 2rem;"></i>
                    </div>
                    <h4 id="foundedYear" style="color: #e67e22; font-size: 2rem; margin-bottom: 0.5rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);"></h4>
                    <p style="font-size: 1.2rem; color: #333; font-weight: 400;">Founded</p>
                    <span id="foundedLabel" style="display: inline-block; background: var(--aboutus); color: white; padding: 0.5rem 1.5rem; border-radius: 50px; font-size: 1rem; margin-top: 1rem; box-shadow: 0 4px 10px rgba(230, 126, 34, 0.3);"></span>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact">
        <div class="footer-content">
            <div class="footer-section">
                <h4>CBOC</h4>
                <p>Comprehensive management system for Cavite Business Owners Club. Featuring the revolutionary E-Portfolio system for modern business networking.</p>
                <div class="social-links">
                    <a href="https://www.facebook.com/groups/caviteonlinebusiness" class="social-link" target="_blank"><i class="fab fa-facebook-f"></i></a>
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
                </ul>
            </div>
        </div>

        <!-- Subtle login link in footer -->
        <div class="footer-login">
            <a href="login.php">← access →</a>
        </div>

        <div class="copyright">
            <p>&copy; 2026 Cavite Business Owners Club. All rights reserved.</p>
            <p>Featuring Advanced E-Portfolio System with NFC Technology</p>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <a href="#home" id="scrollTop">
        <i class="fas fa-arrow-up"></i>
    </a>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script type="module">
        import { db, storage } from './Firebase/firebase_conn.js';
        import { doc, getDoc } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-firestore.js";
        
        // Load About Us Content from Firebase DB
        async function loadAboutContent() {
            try {
                const docRef = doc(db, "siteContent", "aboutCBOC");
                const docSnap = await getDoc(docRef);

                if (!docSnap.exists()) return;

                const data = docSnap.data();

                document.getElementById("backgroundText1").textContent = data.backgroundText1 || "";
                document.getElementById("backgroundText2").textContent = data.backgroundText2 || "";
                document.getElementById("aboutUsText1").textContent = data.aboutUsText1 || "";
                document.getElementById("aboutUsText2").textContent = data.aboutUsText2 || "";
                document.getElementById("missionText").textContent = data.missionText || "";
                document.getElementById("visionText").textContent = data.visionText || "";
                document.getElementById("valuesText").textContent = data.valuesText || "";
                document.getElementById("foundedYear").textContent = data.foundedYear || "";
                document.getElementById("foundedLabel").textContent = data.foundedLabel || "";

            } catch (error) {
                console.error("Error loading About section:", error);
            }
        }
        loadAboutContent();

        // Burger Menu Functionality
        const burgerMenu = document.getElementById('burgerMenu');
        const navLinks = document.getElementById('navLinks');
        const menuOverlay = document.getElementById('menuOverlay');

        function toggleMenu() {
            burgerMenu.classList.toggle('active');
            navLinks.classList.toggle('active');
            menuOverlay.classList.toggle('active');
            
            // Prevent body scrolling when menu is open
            if (navLinks.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }

        burgerMenu.addEventListener('click', toggleMenu);
        menuOverlay.addEventListener('click', toggleMenu);

        // Close menu when clicking on a link
        const navLinksItems = document.querySelectorAll('.nav-links a');
        navLinksItems.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    toggleMenu();
                }
            });
        });

        // Close menu on window resize if opened
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                burgerMenu.classList.remove('active');
                navLinks.classList.remove('active');
                menuOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });

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
            dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
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
            // Reset autoplay when manually changing slides
            stopAutoPlay();
            startAutoPlay();
        }

        // Auto play functionality - every 3.5 seconds
        function startAutoPlay() {
            if (autoPlayInterval) {
                clearInterval(autoPlayInterval);
            }
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
        if (carousel) {
            carousel.addEventListener('mouseenter', stopAutoPlay);
            carousel.addEventListener('mouseleave', startAutoPlay);
            
            // Touch events for mobile
            carousel.addEventListener('touchstart', stopAutoPlay);
            carousel.addEventListener('touchend', startAutoPlay);
        }

        // Scroll to top button functionality
        window.addEventListener('scroll', function() {
            const scrollTop = document.getElementById('scrollTop');
            if (window.scrollY > 300) {
                scrollTop.classList.add('show');
            } else {
                scrollTop.classList.remove('show');
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Handle orientation change
        window.addEventListener('orientationchange', function() {
            // Recalculate carousel height if needed
            setTimeout(function() {
                const activeSlide = document.querySelector('.carousel-slide.active');
                if (activeSlide) {
                    // Force reflow
                    activeSlide.style.display = 'none';
                    activeSlide.offsetHeight;
                    activeSlide.style.display = '';
                }
            }, 200);
        });

        // Add resize listener for responsive adjustments
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                // Any resize adjustments can go here
            }, 250);
        });
    </script>
</body>
</html>