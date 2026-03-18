<?php
declare(strict_types=1);

session_start();

// Clear session data
$_SESSION = [];

// Delete the session cookie (if any)
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'] ?? '/',
        $params['domain'] ?? '',
        (bool)($params['secure'] ?? false),
        (bool)($params['httponly'] ?? true)
    );
}

// Destroy session and go back to login
session_destroy();
header('Location: login.php');
exit;
