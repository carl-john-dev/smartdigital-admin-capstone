<?php 
    require 'vendor/autoload.php';
    use Kreait\Firebase\Factory;
    session_start();

    // Security Headers
    header("Content-Type: application/json");
    header("X-Content-Type-Options: nosniff");

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(["error" => "Invalid request method"]);
        exit;
    }

    $headers = getallheaders();
    $csrfHeader = $headers['X-Csrf-Token'] ?? '';

    if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrfHeader)) {
        http_response_code(403);
        echo json_encode(["error" => "Invalid CSRF token"]);
        exit;
    }

    $_SESSION['verify_attempts'] = ($_SESSION['verify_attempts'] ?? 0) + 1;

    if ($_SESSION['verify_attempts'] > 10) {
        http_response_code(429);
        echo json_encode(["error" => "Too many attempts"]);
        exit;
    }

    $rawInput = file_get_contents("php://input");
    $data = json_decode($rawInput, true);

    if (!$data || !isset($data['token'])) {
        http_response_code(400);
        echo json_encode(["error" => "Token missing"]);
        exit;
    }

    $token = trim($data['token']);

    if (strlen($token) > 2000) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid token"]);
        exit;
    }

    try { 
        $factory = (new Factory)->withServiceAccount(__DIR__ . '/Firebase/firebase_credentials.json');
        $auth = $factory->createAuth();
        $verifiedIdToken = $auth->verifyIdToken($token);

        $uid = $verifiedIdToken->claims()->get('sub');
        if (!$uid) {
            throw new Exception("UID missing");
        }

        $user = $auth->getUser($uid);
        $claims = $user->customClaims;

        session_regenerate_id(true);

        $_SESSION['logged_in'] = true;
        $_SESSION['uid'] = $uid;
        $_SESSION['is_admin'] = $claims['admin'] ?? false;
        $_SESSION['login_time'] = time();
        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

        $_SESSION['verify_attempts'] = 0;
        echo json_encode([
            "success" => true
        ]);

    } catch (\Kreait\Firebase\Exception\Auth\FailedToVerifyToken $e) {
        http_response_code(401);
        echo json_encode(["error" => "Invalid token"]);
        exit;

    } catch (Exception $e) { 
        http_response_code(500);
        echo json_encode(["error" => "Authentication failed"]);
        exit;
    }