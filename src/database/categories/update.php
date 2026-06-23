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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id   = (int)$_POST['id'];
    $name = filter_var(trim($_POST['name']), FILTER_UNSAFE_RAW);
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));

    if (empty($name) || $id <= 0) {
        die("Invalid parameters provided to the application engine.");
    }

    try {
        $db = Database::getConnection();
        
        $stmt = $db->prepare("UPDATE categories SET name = :name, slug = :slug WHERE id = :id");
        $stmt->execute([
            ':name' => $name,
            ':slug' => $slug,
            ':id'   => $id
        ]);

        // 2. FIXED REDIRECT: Target the new feature-grouped view file location
        header('Location: ' . BASE_URL . 'categories/manage.php?updated=1');
        exit;
    } catch (\PDOException $e) {
        error_log("Failed to mutate category metrics: " . $e->getMessage());
        die("Database processing breakdown. The custom choice name might compete with an existing duplicate row index.");
    }
} else {
    die("Direct access restricted.");
}