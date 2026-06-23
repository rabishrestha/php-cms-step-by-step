<?php
// 1. FIXED PATH: Jump 3 levels up to reach the root folder from src/database/categories/
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../db.php'; // Finds db.php in the parent src/database/ folder

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized transaction access blocked.");
}

use HamroNews\Database\Database;

$targetId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($targetId > 0) {
    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM categories WHERE id = :id");
        $stmt->execute([':id' => $targetId]);

        // 2. FIXED REDIRECT: Target the new feature-grouped view file location
        header('Location: ' . BASE_URL . 'categories/manage.php?deleted=1');
        exit;
    } catch (\PDOException $e) {
        // Integrity constraint violation error code check (active foreign key restriction triggered)
        if ($e->getCode() == '23000') {
            // 3. FIXED REDIRECT: Target the new view location for errors as well
            header('Location: ' . BASE_URL . 'categories/manage.php?error=restricted');
            exit;
        }
        error_log("Category deletion failed: " . $e->getMessage());
        die("An execution error occurred during category removal tasks.");
    }
} else {
    die("Invalid target identifier missing.");
}