<?php
require 'vendor/autoload.php';

use Kreait\Firebase\Factory;

session_start();

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['token'])) {
    http_response_code(400);
    exit("No token provided");
}

try {
    $factory = (new Factory)->withServiceAccount(__DIR__ . '/Firebase/firebase_credentials.json');
    $auth = $factory->createAuth();
    $verifiedIdToken = $auth->verifyIdToken($data['token']);

    $_SESSION['logged_in'] = true;
    $_SESSION['uid'] = $verifiedIdToken->claims()->get('sub');
    echo "Token verified successfully";
    
} catch (Exception $e) {
    echo "Verification failed: " . $e->getMessage();
    http_response_code(401);
}
