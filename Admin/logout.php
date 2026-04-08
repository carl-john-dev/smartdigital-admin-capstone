<?php
    session_start();

    // CSRF Protection
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php");
        exit;
    }

    // Clear session data
    $_SESSION = [];

    // Destroy session cookies
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // Session unset and destroy
    session_unset();
    session_destroy();

    // Redirect
    header("Location: index.php?logout=success");
    exit;