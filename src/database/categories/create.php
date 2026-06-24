<?php
require_once __DIR__ . '/categorymanager.php';

if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id'])) { die("Unauthorized access blocked."); }

use HamroNews\Database\CategoryManager;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_var(trim($_POST['name']), FILTER_UNSAFE_RAW);
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));

    if (empty($name)) { die("Category name cannot be empty."); }

    try {
        CategoryManager::create($name, $slug);
        header('Location: ' . BASE_URL . 'categories/manage.php?success=1');
        exit;
    } catch (\PDOException $e) {
        error_log("Failed to insert category: " . $e->getMessage());
        die("An error occurred. The category name or slug might already exist.");
    }
} else {
    die("Direct access restricted.");
}