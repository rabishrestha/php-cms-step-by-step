<?php
// 1. FIXED PATH: Jump 3 levels up to find the config file from src/database/posts/
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../db.php'; // Finds db.php in the parent src/database/ folder

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security clearance validation check
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized transaction access blocked.");
}

use HamroNews\Database\Database;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $title       = filter_var(trim($_POST['title']), FILTER_UNSAFE_RAW);
    $categoryId  = (int)$_POST['category_id'];
    $publishedAt = $_POST['published_at']; // Form returns Y-m-d\TH:i format directly
    $summary     = filter_var(trim($_POST['summary']), FILTER_UNSAFE_RAW);
    $content     = filter_var(trim($_POST['content']), FILTER_UNSAFE_RAW);

    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
    
    // Bind explicit author identity matching the signed-in profile
    $userId = (int)$_SESSION['user_id']; 

    try {
        $db = Database::getConnection();
        $sql = "INSERT INTO posts (user_id, category_id, title, slug, summary, content, published_at) 
                VALUES (:user_id, :category_id, :title, :slug, :summary, :content, :published_at)";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':user_id'      => $userId,
            ':category_id'  => $categoryId,
            ':title'        => $title,
            ':slug'         => $slug,
            ':summary'      => $summary,
            ':content'      => $content,
            ':published_at' => $publishedAt
        ]);

        header('Location: ' . BASE_URL . 'dashboard.php?success=1');
        exit;
    } catch (\PDOException $e) {
        error_log("Advanced creation failed: " . $e->getMessage());
        die("Database insertion operation tracking breakdown.");
    }
} else {
    die("Direct access restricted.");
}