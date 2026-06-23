<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/db.php';

session_start();
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

        header('Location: ' . BASE_URL . 'categories.php?deleted=1');
        exit;
    } catch (\PDOException $e) {
        // Integrity constraint violation error code check (e.g., active foreign key restriction triggered)
        if ($e->getCode() == '23000') {
            header('Location: ' . BASE_URL . 'categories.php?error=restricted');
            exit;
        }
        error_log("Category deletion failed: " . $e->getMessage());
        die("An execution error occurred during category removal tasks.");
    }
} else {
    die("Invalid target identifier missing.");
}