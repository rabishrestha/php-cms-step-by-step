<?php
// 1. FIXED PATH: Jump 3 levels up to find the config file from src/database/posts/
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../db.php'; // Finds db.php in the parent src/database/ folder

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Enforce active authentication gate clearance
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized transaction access blocked.");
}

use HamroNews\Database\Database;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Extract inputs matching the new relational name parameters
    $id          = (int)$_POST['id'];
    $categoryId  = (int)$_POST['category_id'];
    $title       = filter_var(trim($_POST['title']), FILTER_UNSAFE_RAW);
    $publishedAt = $_POST['published_at']; // Captured straight from the html5 datetime-local picker
    $summary     = filter_var(trim($_POST['summary']), FILTER_UNSAFE_RAW);
    $content     = filter_var(trim($_POST['content']), FILTER_UNSAFE_RAW);
    
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));

    try {
        $db = Database::getConnection();

        // Update execution matching the normalized relational schema targets
        // Note: We don't change user_id here so the original author remains intact
        $sql = "UPDATE posts 
                SET title = :title, 
                    slug = :slug, 
                    summary = :summary, 
                    content = :content, 
                    category_id = :category_id, 
                    published_at = :published_at 
                WHERE id = :id";
        
        $stmt = $db->prepare($sql);
        
        $stmt->execute([
            ':title'        => $title,
            ':slug'         => $slug,
            ':summary'      => $summary,
            ':content'      => $content,
            ':category_id'  => $categoryId,
            ':published_at' => $publishedAt,
            ':id'           => $id
        ]);

        header('Location: ' . BASE_URL . 'dashboard.php?updated=1');
        exit;

    } catch (\PDOException $e) {
        error_log("Database modification failed: " . $e->getMessage());
        die("An execution error occurred while processing row mutations.");
    }

} else {
    die("Direct access restricted.");
}