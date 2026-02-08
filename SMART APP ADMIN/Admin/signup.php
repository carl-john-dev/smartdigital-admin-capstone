<?php
// Database connection code
$host = 'localhost';
$user = 'root';
$password = ''; // Replace with actual password
$database = 'crudproj_db';

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start(); 
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $checkQuery = "SELECT * FROM tbl_users WHERE username='$username'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            $error = "Username already taken!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Only insert username and password as per your DB structure
            $insertQuery = "INSERT INTO tbl_users (username, pass) VALUES ('$username', '$hashed_password')";
            if (mysqli_query($conn, $insertQuery)) {
                $success = "Signup successful! You can now <a href='login.php'>login</a>.";
            } else {
                $error = "Database error: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBOC Sign Up Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="icon" href="CBOC LOGO.jpg"/>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #1a2a6c, #b21f1f, #fdbb2d);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow: hidden;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: 950px;
            min-height: 600px;
            perspective: 1000px;
            margin: 20px;
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            padding: 40px;
            width: 100%;
            max-width: 500px;
            transform-style: preserve-3d;
            animation: formEntrance 1s ease-out;
            position: relative;
            overflow: hidden;
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
            margin-bottom: 20px;
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
            margin-top: 10px;
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

        .success-message {
            color: #27ae60;
            text-align: center;
            margin-bottom: 20px;
            padding: 12px;
            background-color: rgba(39, 174, 96, 0.1);
            border-radius: 8px;
            border-left: 4px solid #27ae60;
            animation: fadeIn 0.5s ease;
            position: relative;
            z-index: 1;
        }

        .success-message a {
            color: #27ae60;
            font-weight: 600;
            text-decoration: none;
        }

        .success-message a:hover {
            text-decoration: underline;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
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

        .floating-objects {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
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

        .password-strength {
            height: 5px;
            margin-top: 5px;
            border-radius: 5px;
            background: #eee;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0;
            transition: width 0.3s, background 0.3s;
            border-radius: 5px;
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 30px 25px;
            }
            
            .logo-text {
                font-size: 20px;
            }
        }
        
        .db-status {
            text-align: center;
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .db-connected {
            background-color: rgba(46, 204, 113, 0.2);
            color: #27ae60;
            border: 1px solid #27ae60;
        }
        
        .db-error {
            background-color: rgba(231, 76, 60, 0.2);
            color: #c0392b;
            border: 1px solid #c0392b;
        }
        
        .form-row {
            display: flex;
            gap: 15px;
        }
        
        .form-row .form-group {
            flex: 1;
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
                <!-- CBOC Logo -->
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
            
            <div class="form-title">Create Account</div>

            <?php if (!empty($error)): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="success-message"><?= $success ?></div>
            <?php endif; ?>

            <form action="" method="post">
                <div class="form-group">
                    <label for="signup-name">Full Name</label>
                    <div class="input-with-icon">
                        <div class="input-icon"><i class="fas fa-user"></i></div>
                        <input type="text" id="signup-name" name="fullname" placeholder="Enter your full name" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="signup-username">Username</label>
                        <div class="input-with-icon">
                            <div class="input-icon"><i class="fas fa-at"></i></div>
                            <input type="text" id="signup-username" name="username" placeholder="Choose a username" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="signup-email">Email</label>
                        <div class="input-with-icon">
                            <div class="input-icon"><i class="fas fa-envelope"></i></div>
                            <input type="email" id="signup-email" name="email" placeholder="Enter your email" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="signup-password">Password</label>
                        <div class="input-with-icon">
                            <div class="input-icon"><i class="fas fa-lock"></i></div>
                            <input type="password" id="signup-password" name="password" placeholder="Create a password" required>
                            <div class="password-strength">
                                <div class="password-strength-bar" id="password-strength-bar"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="signup-confirm-password">Confirm Password</label>
                        <div class="input-with-icon">
                            <div class="input-icon"><i class="fas fa-lock"></i></div>
                            <input type="password" id="signup-confirm-password" name="confirm_password" placeholder="Confirm your password" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn">Create Account</button>

                <div class="form-footer">
                    Already have an account? <a href="login.php">Login Here</a>
                </div>
            </form>
            
            <!-- Database Connection Status -->
            <div class="db-status <?php echo $conn->connect_error ? 'db-error' : 'db-connected'; ?>">
                <?php 
                if ($conn->connect_error) {
                    echo "Database Connection Error: " . $conn->connect_error;
                } else {
                    echo "Database Connection: Successful";
                }
                ?>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.parentElement.style.transform = 'translateY(-5px)';
                    this.parentElement.parentElement.style.transition = 'transform 0.3s ease';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.parentElement.style.transform = 'translateY(0)';
                });
            });
            
            // Add animation to button on hover
            const btn = document.querySelector('.btn');
            btn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-3px)';
            });
            
            btn.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
            
            // Add pulse animation to logo
            const logo = document.querySelector('.logo');
            setInterval(() => {
                logo.style.transform = 'scale(1.05)';
                setTimeout(() => {
                    logo.style.transform = 'scale(1)';
                }, 500);
            }, 3000);
            
            // Password strength indicator
            const passwordInput = document.getElementById('signup-password');
            const strengthBar = document.getElementById('password-strength-bar');
            
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                
                if (password.length >= 8) strength += 25;
                if (password.match(/[a-z]+/)) strength += 25;
                if (password.match(/[A-Z]+/)) strength += 25;
                if (password.match(/[0-9]+/)) strength += 25;
                
                strengthBar.style.width = strength + '%';
                
                if (strength < 50) {
                    strengthBar.style.background = '#e74c3c';
                } else if (strength < 75) {
                    strengthBar.style.background = '#f39c12';
                } else {
                    strengthBar.style.background = '#27ae60';
                }
            });
            
            // Confirm password validation
            const confirmPasswordInput = document.getElementById('signup-confirm-password');
            
            confirmPasswordInput.addEventListener('input', function() {
                const password = passwordInput.value;
                const confirmPassword = this.value;
                
                if (confirmPassword && password !== confirmPassword) {
                    this.style.borderColor = '#e74c3c';
                    this.style.boxShadow = '0 0 0 3px rgba(231, 76, 60, 0.2)';
                } else {
                    this.style.borderColor = '#e1e5eb';
                    this.style.boxShadow = 'none';
                }
            });
        });
    </script>
</body>
</html>