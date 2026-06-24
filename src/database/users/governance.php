<?php
require_once __DIR__ . '/usermanager.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Enforce active admin clearance check
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'Admin') {
    die("Access Denied: Administrative Clearance Required.");
}

use HamroNews\Database\UserManager;

$action   = isset($_GET['action']) ? trim($_GET['action']) : '';
$targetId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($targetId <= 0) {
    die("Invalid transaction identity mapping parameters.");
}

// Prevent self-deactivation safeguards
if ($action === 'deactivate' && $targetId === (int)$_SESSION['user_id']) {
    die("Operation Blocked: Self-deactivation metrics are restricted.");
}

try {
    if ($action === 'approve') {
        UserManager::updateStatus($targetId, 'Active');
    } elseif ($action === 'deactivate') {
        UserManager::updateStatus($targetId, 'Inactive');
    } else {
        die("Invalid runtime workflow command.");
    }

    header('Location: ' . BASE_URL . 'users/manage.php?updated=1');
    exit;
} catch (Exception $e) {
    error_log("User governance transformation breakdown: " . $e->getMessage());
    die("Internal script error occurred during state transformation operations.");
}