<?php
require_once __DIR__ . '/categorymanager.php';

if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id'])) { die("Unauthorized access blocked."); }

use HamroNews\Database\CategoryManager;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id   = (int)$_POST['id'];
    $name = filter_var(trim($_POST['name']), FILTER_UNSAFE_RAW);
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));

    if (empty($name) || $id <= 0) { die("Invalid parameters provided."); }

    try {
        CategoryManager::update($id, $name, $slug);
        header('Location: ' . BASE_URL . 'categories/manage.php?updated=1');
        exit;
    } catch (\PDOException $e) {
        error_log("Failed to mutate category metrics: " . $e->getMessage());
        die("Database breakdown. The name choice might compete with an existing duplicate row.");
    }
} else {
    die("Direct access restricted.");
}