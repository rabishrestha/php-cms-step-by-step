<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/db.php';

use HamroNews\Database\Database;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id       = (int)$_POST['id'];
    $title    = filter_var(trim($_POST['title']), FILTER_UNSAFE_RAW);
    $category = filter_var(trim($_POST['category']), FILTER_UNSAFE_RAW);
    $author   = filter_var(trim($_POST['author']), FILTER_UNSAFE_RAW);
    $summary  = filter_var(trim($_POST['summary']), FILTER_UNSAFE_RAW);
    $content  = filter_var(trim($_POST['content']), FILTER_UNSAFE_RAW);
    
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
    $editedAt = date('F j, Y') . ' (Edited)';

    try {
        $db = Database::getConnection();

        // Prepare relational database updates targeting precise primary keys
        $sql = "UPDATE posts 
                SET title = :title, slug = :slug, summary = :summary, content = :content, 
                    category = :category, author = :author, published_at = :published_at 
                WHERE id = :id";
        
        $stmt = $db->prepare($sql);
        
        $stmt->execute([
            ':title'        => $title,
            ':slug'         => $slug,
            ':summary'      => $summary,
            ':content'      => $content,
            ':category'     => $category,
            ':author'       => $author,
            ':published_at' => $editedAt,
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