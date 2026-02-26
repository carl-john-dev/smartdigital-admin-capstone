<?php
session_start();

function requireAdmin() {
    if (!isset($_SESSION['logged_in']) || $_SESSION['is_admin'] !== true) {
        header("Location: login.php");
        exit;
    }
}

function requireGuest() {
    if (isset($_SESSION['logged_in']) && $_SESSION['is_admin'] === true) {
        header("Location: dashboard.php");
        exit;
    }
}
