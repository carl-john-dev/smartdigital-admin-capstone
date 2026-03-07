<?php
session_start();
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$idToken = $input['token'] ?? '';

if (empty($idToken)) {
    echo json_encode(['success' => false, 'message' => 'No token provided']);
    exit;
}

// Simple session creation
$_SESSION['user_token'] = $idToken;
$_SESSION['user_logged_in'] = true;
$_SESSION['user_email'] = 'user@example.com';

echo json_encode(['success' => true, 'message' => 'Login successful']);
?>