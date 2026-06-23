<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/db.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized transaction access blocked.");
}

use HamroNews\Database\Database;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_var(trim($_POST['name']), FILTER_UNSAFE_RAW);
    // Generate clean, url-safe text slugs
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));

    if (empty($name)) {
        die("Category name cannot be empty.");
    }

    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO categories (name, slug) VALUES (:name, :slug)");
        $stmt->execute([
            ':name' => $name,
            ':slug' => $slug
        ]);

        header('Location: ' . BASE_URL . 'categories.php?success=1');
        exit;
    } catch (\PDOException $e) {
        error_log("Failed to insert category: " . $e->getMessage());
        die("An error occurred. The category name or slug might already exist.");
    }
} else {
    die("Direct access restricted.");
}