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
        file_put_contents("verify_hit.txt", $data['token']);
        $verifiedIdToken = $auth->verifyIdToken($data['token']);

        $uid = $verifiedIdToken->claims()->get('sub');
        $user = $auth->getUser($uid);
        $claims = $user->customClaims;

        $_SESSION['logged_in'] = true;
        $_SESSION['uid'] = $uid;
        $_SESSION['is_admin'] = $claims['admin'] ?? false;

        echo "Token verified successfully";

    } catch (Exception $e) { 
        http_response_code(401);
        echo json_encode([
            "error" => $e->getMessage()
        ]);
        exit;
    }