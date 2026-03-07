<?php
    session_start();
    $error = "";
    
    // Check if user was redirected from registration
    $registered = isset($_GET['registered']) ? true : false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBOC ADMIN - Login</title>
    <link rel="icon" type="icon" href="CBOC LOGO.jpg"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            /* Replace gradient with business meeting background image */
            background: url('https://images.unsplash.com/photo-1557804506-669a67965ba0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1974&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow: hidden;
            position: relative;
        }

        /* Dark overlay para hindi masyadong bright at readable ang form */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Dark overlay */
            z-index: 1;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: 900px;
            min-height: 550px;
            perspective: 1000px;
            margin: 20px;
            position: relative;
            z-index: 2; /* Above the overlay */
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            padding: 40px;
            width: 100%;
            max-width: 450px;
            transform-style: preserve-3d;
            animation: formEntrance 1s ease-out;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(5px); /* Optional: blur effect sa background ng form */
        }

        .form-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(to bottom right, rgba(79, 139, 255, 0.1), rgba(255, 255, 255, 0));
            transform: rotate(30deg);
            z-index: 0;
        }

        @keyframes formEntrance {
            from {
                opacity: 0;
                transform: translateY(50px) rotateX(30deg);
            }
            to {
                opacity: 1;
                transform: translateY(0) rotateX(0);
            }
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }

        .logo {
            max-width: 180px;
            margin-bottom: 15px;
            filter: drop-shadow(0 5px 10px rgba(0, 0, 0, 0.2));
        }

        .logo-text {
            font-size: 22px;
            font-weight: 700;
            color: #1a2a6c;
            letter-spacing: 1px;
            line-height: 1.4;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }

        .logo-subtext {
            font-size: 14px;
            color: #b21f1f;
            font-weight: 500;
            letter-spacing: 2px;
            margin-top: 5px;
        }

        .form-title {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 25px;
            color: #333;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .form-title::after {
            content: '';
            display: block;
            width: 60px;
            height: 4px;
            background: linear-gradient(to right, #1a2a6c, #b21f1f);
            margin: 10px auto;
            border-radius: 2px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
            z-index: 1;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #444;
            padding-left: 5px;
        }

        .input-with-icon {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 18px;
        }

        .form-group input {
            width: 100%;
            padding: 15px 15px 15px 50px;
            border: 2px solid #e1e5eb;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .form-group input:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.2);
            transform: translateY(-2px);
        }

        .btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(to right, #1a2a6c, #b21f1f);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, #fdbb2d, #b21f1f);
            transition: all 0.4s;
            z-index: -1;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .btn:hover::before {
            left: 0;
        }

        .btn:active {
            transform: translateY(1px);
        }

        .error-message {
            color: #e74c3c;
            text-align: center;
            margin-bottom: 20px;
            padding: 12px;
            background-color: rgba(231, 76, 60, 0.1);
            border-radius: 8px;
            border-left: 4px solid #e74c3c;
            animation: shake 0.5s ease;
            position: relative;
            z-index: 1;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .success-message {
            color: #27ae60;
            text-align: center;
            margin-bottom: 20px;
            padding: 12px;
            background-color: rgba(39, 174, 96, 0.1);
            border-radius: 8px;
            border-left: 4px solid #27ae60;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-footer {
            text-align: center;
            margin-top: 25px;
            color: #666;
            position: relative;
            z-index: 1;
        }

        .form-footer a {
            color: #1a2a6c;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            position: relative;
        }

        .form-footer a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: #b21f1f;
            transition: width 0.3s;
        }

        .form-footer a:hover {
            color: #b21f1f;
        }

        .form-footer a:hover::after {
            width: 100%;
        }

        /* Remove floating objects or keep them */
        .floating-objects {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
            pointer-events: none; /* Para hindi maka-interfere sa clicks */
        }

        .float {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 15s infinite linear;
        }

        @keyframes float {
            0% { transform: translateY(0) rotate(0deg); }
            100% { transform: translateY(-1000px) rotate(720deg); }
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 30px 25px;
            }
            
            .logo-text {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="floating-objects">
        <div class="float" style="width: 50px; height: 50px; left: 10%; animation-duration: 20s;"></div>
        <div class="float" style="width: 30px; height: 30px; left: 25%; animation-duration: 15s; animation-delay: -2s;"></div>
        <div class="float" style="width: 70px; height: 70px; left: 70%; animation-duration: 25s; animation-delay: -5s;"></div>
        <div class="float" style="width: 40px; height: 40px; left: 85%; animation-duration: 18s; animation-delay: -7s;"></div>
    </div>
    
    <div class="container">
        <div class="form-container">
            <div class="logo-container">
                <div class="logo">
                    <svg viewBox="0 0 200 60" xmlns="http://www.w3.org/2000/svg">
                        <rect x="10" y="10" width="40" height="40" rx="5" fill="#1a2a6c" />
                        <text x="60" y="25" font-family="Arial" font-weight="bold" font-size="16" fill="#1a2a6c">CAVITE BUSINESS</text>
                        <text x="60" y="45" font-family="Arial" font-weight="bold" font-size="16" fill="#b21f1f">OWNERS CLUB</text>
                    </svg>
                </div>
                <div class="logo-text">CAVITE BUSINESS</div>
                <div class="logo-subtext">OWNERS CLUB</div>
            </div>
            
            <div class="form-title">Login</div>

            <?php if ($registered): ?>
                <div class="success-message">Registration successful! Please login with your credentials.</div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form id="loginForm">
                <div class="form-group">
                    <label for="login-username">Email or Username</label>
                    <div class="input-with-icon">
                        <div class="input-icon"><i class="fas fa-user"></i></div>
                        <input type="text" id="login-username" name="username" placeholder="Enter your email or username" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="login-password">Password</label>
                    <div class="input-with-icon">
                        <div class="input-icon"><i class="fas fa-lock"></i></div>
                        <input type="password" id="login-password" name="password" placeholder="Enter your password" required>
                    </div>
                </div>

                <button type="submit" class="btn">Login</button>

            </form>
        </div>
    </div>

    <script type="module">
        import { db, app } from './Firebase/firebase_conn.js';
        import { getAuth, signInWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-auth.js";

        const auth = getAuth(app);

        document.getElementById("loginForm").addEventListener("submit", async (e) => {
            e.preventDefault();

            const username = document.getElementById("login-username").value.trim();
            const password = document.getElementById("login-password").value;

            // Map username → email (temporary testing logic)
            const email = username.includes("@") ? username : username + "@cboc.test";

            try {
                await signInWithEmailAndPassword(auth, email, password);

                // Get ID Token
                const user = auth.currentUser;
                const idToken = await user.getIdToken();

                // Send to PHP
                const response = await fetch("verify_token.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    credentials: "same-origin",
                    body: JSON.stringify({ token: idToken })
                });

                const result = await response.text();
                console.log(result);

                window.location.href = "dashboard.php";

            } catch (error) {
                alert("Login failed: " + error.message);
            }
        });
    </script>
</body>
</html>