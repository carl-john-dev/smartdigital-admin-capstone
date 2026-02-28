<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'vendor/autoload.php';

use Kreait\Firebase\Factory;

session_start();

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['token'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "No token provided"]);
    exit("No token provided");
}

try {
    $factory = (new Factory)->withServiceAccount(__DIR__ . '/Firebase/firebase_credentials.json');
    $auth = $factory->createAuth();
    $verifiedIdToken = $auth->verifyIdToken($data['token']);

    $uid = $verifiedIdToken->claims()->get('sub');
    $user = $auth->getUser($uid);
    $claims = $user->customClaims;

    $_SESSION['logged_in'] = true;
    $_SESSION['uid'] = $uid;
    $_SESSION['is_admin'] = $claims['admin'] ?? false;
    echo json_encode(["status" => "ok"]);
    exit;
    
} catch (Exception $e) {
    echo "Verification failed: " . $e->getMessage();
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
