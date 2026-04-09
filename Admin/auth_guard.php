<?php
    // Prevent direct access
    defined('SECURE_ACCESS') or die('Direct access not permitted');

    // Disable error display in production
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);

    // Security Headers
    $nonce = bin2hex(random_bytes(16));
    // header("Content-Security-Policy: default-src 'self'; script-src 'self' 'nonce-$nonce' https://www.gstatic.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://unpkg.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://unpkg.com; img-src 'self' data: https:; connect-src 'self' https://firestore.googleapis.com https://securetoken.googleapis.com https://identitytoolkit.googleapis.com https://www.googleapis.com https://www.gstatic.com; font-src 'self' https://cdnjs.cloudflare.com;");
    header("X-Frame-Options: SAMEORIGIN");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer-when-downgrade");
    header("X-XSS-Protection: 1; mode=block");

    // Prevent caching of protected pages
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");

    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.cookie_httponly', 1);
        ini_set('session.cookie_secure', 1);
        ini_set('session.use_strict_mode', 1);
        ini_set('session.cookie_samesite', 'Strict');
        session_start();
    }

    if (!isset($_SESSION['created'])) {
        $_SESSION['created'] = time();
    }

    if (time() - $_SESSION['created'] > 600) { // 10 minutes
        session_regenerate_id(true);
        $_SESSION['created'] = time();
    }

    // Security Header
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");

    // Session Timeout
    $SESSION_TIMEOUT = 1800;

    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $SESSION_TIMEOUT)) {
        session_unset();
        session_destroy();
        header("Location: index.php?session_expired=1");
        exit;
    }

    $_SESSION['last_activity'] = time();

    // Block session highjacking
    if (!isset($_SESSION['user_agent'])) {
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    }

    if ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
        session_unset();
        session_destroy();
        header("Location: index.php?security=agent_mismatch");
        exit;
    }

    // For Admin-only pages
    function requireAdmin() {
        if (
            !isset($_SESSION['logged_in']) ||
            !isset($_SESSION['uid']) ||
            !isset($_SESSION['is_admin']) ||
            $_SESSION['logged_in'] !== true ||
            $_SESSION['is_admin'] !== true
        ) {
            header("Location: index.php");
            exit;
        }
    }

    // For Guest-only pages
    function requireGuest() {
        if (!empty($_SESSION['logged_in'])) {

            // Safe redirect fallback
            $redirect = "dashboard.php";

            if (!empty($_SERVER['HTTP_REFERER'])) {
                $referer = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);

                // Prevent redirecting outside the site
                if ($referer && strpos($referer, "/") === 0) {
                    $redirect = $referer;
                }
                
            }
            header("Location: " . $redirect);
            exit;
        }
    }