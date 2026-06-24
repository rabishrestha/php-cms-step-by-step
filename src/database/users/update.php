<?php
// 1. FIXED PATH: Jump 3 levels up to cleanly hit your root architecture configurations
require_once __DIR__ . '/usermanager.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Secure Guard Layer: Restrict to logged-in Admins only
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'Admin') {
    die("Access Denied: Administrative Clearance Required.");
}

use HamroNews\Database\UserManager;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id        = (int)$_POST['id'];
    $fullName  = filter_var(trim($_POST['full_name']), FILTER_UNSAFE_RAW);
    $username  = filter_var(trim($_POST['username']), FILTER_UNSAFE_RAW);
    $email     = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $contactNo = !empty(trim($_POST['contact_no'])) ? filter_var(trim($_POST['contact_no']), FILTER_UNSAFE_RAW) : null;
    $role      = filter_var(trim($_POST['role']), FILTER_UNSAFE_RAW);
    $status    = filter_var(trim($_POST['status']), FILTER_UNSAFE_RAW);
    $password  = !empty($_POST['password']) ? $_POST['password'] : null;

    if ($id <= 0 || empty($fullName) || empty($username) || !$email) {
        die("Invalid profile parameters provided to the application engine.");
    }

    // Safeguard: Prevent admins from accidentally locking themselves out of the control desk
    if ($id === (int)$_SESSION['user_id'] && $status !== 'Active') {
        die("Operation Blocked: You cannot deactivate your own active admin session entry.");
    }

    try {
        $success = UserManager::update($id, $fullName, $username, $email, $contactNo, $role, $status, $password);
        
        if ($success) {
            // Redirect back safely to your refactored subfolder workspace layout view
            header('Location: ' . BASE_URL . 'users/manage.php?updated=1');
            exit;
        } else {
            die("Database processing breakdown. The username or email might already compete with a duplicate entry row.");
        }
    } catch (\Exception $e) {
        error_log("Failed to mutate user details: " . $e->getMessage());
        die("An unexpected execution tracking breakdown occurred.");
    }
} else {
    die("Direct access restricted.");
}