<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/db.php';

use HamroNews\Database\Database;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    try {
        $db = Database::getConnection();

        // 1. CRITICAL FIX: Restrict lookup to only 'Active' profiles (blocks Pending & Inactive records)
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username AND status = 'Active' LIMIT 1");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        // 2. Cryptographic Validation Layer (Syllabus: Secure Hashes)
        if ($user && password_verify($password, $user['password'])) {
            
            // Safe Session Start check to prevent runtime notices
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            // Success! Initialize secure global session footprints
            $_SESSION['user_id']   = (int)$user['id'];
            $_SESSION['username']  = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            
            // CRITICAL FIX: Cache the user role ('Admin' or 'Reporter') to drive RBAC checks
            $_SESSION['role']      = $user['role']; 

            // Redirect smoothly into the protected dashboard area
            header('Location: ' . BASE_URL . 'dashboard.php');
            exit;
        } else {
            // Credential verification failure or account not yet active fallback
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