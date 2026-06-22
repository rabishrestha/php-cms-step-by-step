<?php
require_once __DIR__ . '/../config/config.php';

session_start();

// Unset all allocated execution matrix session parameter state tokens completely
$_SESSION = [];

// Destroy the tracking session file cookie presence off the storage disk engine tracks
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// Redirect back cleanly into the open marketplace visibility area
header('Location: ' . BASE_URL . 'index.php');
exit;