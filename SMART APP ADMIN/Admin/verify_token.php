<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    session_start();
    require __DIR__ . '/vendor/autoload.php';

    use Kreait\Firebase\Factory;
    use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;

    $data = json_decode(file_get_contents("php://input"), true);
    $token = $data['token'] ?? null;

    file_put_contents(__DIR__ . '/verify_hit.txt', `Token: $token`);

    if (!$token) {
        http_response_code(400);
        exit('No token');
    }
    
    // // Fake claims for testing
    // $claims = [
    //     'sub' => '1234567890',
    //     'admin' => true
    // ];

    $auth = (new Factory)
        ->withServiceAccount(__DIR__ . '/firebase_service_account.json')
        ->createAuth();

    try {
        $verifiedToken = $auth->verifyIdToken($token);
        $claims = $verifiedToken->claims()->all();

        file_put_contents(__DIR__ . '/verify_hit.txt', `Claims: $claims`);

        $_SESSION['logged_in'] = true;
        $_SESSION['uid'] = $claims['sub'];
        $_SESSION['is_admin'] = $claims['admin'] ?? false;

        file_put_contents(__DIR__ . '/verify_hit.txt', `Session ID:` + session_id()); // current session id
        file_put_contents(__DIR__ . '/verify_hit.txt', `Session: $_SESSION`);    // see session before echo

        echo json_encode(['ok' => true]);
    } catch (FailedToVerifyToken $e) {
        http_response_code(401);
        echo $e->getMessage();
    }
