<?php
// 1. FIXED PATH: Jump 3 levels up to find the config file from src/database/posts/
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

        // Execute targeted line removals natively matching standard syntax requirements
        $stmt = $db->prepare("DELETE FROM posts WHERE id = :id");
        $stmt->execute([':id' => $targetId]);

        header('Location: ' . BASE_URL . 'dashboard.php?deleted=1');
        exit;

    } catch (\PDOException $e) {
        error_log("Database row deletion failed: " . $e->getMessage());
        die("Could not execute deletion request processing tasks.");
    }
} else {
    die("Error: Invalid transaction parameter ID signature missing.");
}