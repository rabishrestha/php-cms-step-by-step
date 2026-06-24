<?php
require_once __DIR__ . '/categorymanager.php';

if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id'])) { die("Unauthorized access blocked."); }

use HamroNews\Database\CategoryManager;

$targetId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($targetId > 0) {
    try {
        CategoryManager::delete($targetId);
        header('Location: ' . BASE_URL . 'categories/manage.php?deleted=1');
        exit;
    } catch (\PDOException $e) {
        if ($e->getCode() == '23000') {
            header('Location: ' . BASE_URL . 'categories/manage.php?error=restricted');
            exit;
        }
        error_log("Category deletion failed: " . $e->getMessage());
        die("An execution error occurred during category removal tasks.");
    }
} else {
    die("Invalid target identifier missing.");
}