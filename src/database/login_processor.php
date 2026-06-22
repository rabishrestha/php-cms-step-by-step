<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/db.php';

use HamroNews\Database\Database;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    try {
        $db = Database::getConnection();

        // 1. Fetch user records matching the unique input handle token
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        // 2. Cryptographic Validation Layer (Syllabus: Secure Hashes)
        if ($user && password_verify($password, $user['password'])) {
            // Success! Initialize secure global browser server state tokens
            session_start();
            $_SESSION['user_id']   = (int)$user['id'];
            $_SESSION['username']  = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];

            // Redirect smoothly into the protected space area
            header('Location: ' . BASE_URL . 'dashboard.php');
            exit;
        } else {
            // Credential verification failure fallback
            header('Location: ' . BASE_URL . 'login.php?error=1');
            exit;
        }

    } catch (\PDOException $e) {
        error_log("Login authentication error log: " . $e->getMessage());
        die("An operational intercept validation failure occurred.");
    }
} else {
    die("Direct access restricted.");
}