<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // For Admin-only pages
    function requireAdmin() {
        if (
            !isset($_SESSION['logged_in']) ||
            !isset($_SESSION['is_admin']) ||
            $_SESSION['is_admin'] !== true
        ) {
            header("Location: index.php");
            exit;
        }
    }

    // For Guest-only pages
    function requireGuest() {
        if (!empty($_SESSION['logged_in'])) {

            // If there is a previous page, go back to it
            if (!empty($_SERVER['HTTP_REFERER'])) {
                header("Location: " . $_SERVER['HTTP_REFERER']);
            } else {
                // Fallback if no referrer exists
                header("Location: dashboard.php");
            }

            exit;
        }
    }