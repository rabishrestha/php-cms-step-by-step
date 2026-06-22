<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/db.php'; // Pull in our centralized PDO database adapter

use HamroNews\Database\Database;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Unpack and sanitize incoming form inputs
    $title    = filter_var(trim($_POST['title']), FILTER_UNSAFE_RAW);
    $category = filter_var(trim($_POST['category']), FILTER_UNSAFE_RAW);
    $author   = filter_var(trim($_POST['author']), FILTER_UNSAFE_RAW);
    $summary  = filter_var(trim($_POST['summary']), FILTER_UNSAFE_RAW);
    $content  = filter_var(trim($_POST['content']), FILTER_UNSAFE_RAW);

    // Generate url-safe text routing token slugs
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
    $publishedAt = date('F j, Y');

    try {
        $db = Database::getConnection();

        // 2. Prepare the query layout using named parameters (:placeholder)
        $sql = "INSERT INTO posts (title, slug, summary, content, category, author, published_at) 
                VALUES (:title, :slug, :summary, :content, :category, :author, :published_at)";
        
        $stmt = $db->prepare($sql);

        // 3. Bind value variables safely and execute data insertions securely
        $stmt->execute([
            ':title'        => $title,
            ':slug'         => $slug,
            ':summary'      => $summary,
            ':content'      => $content,
            ':category'     => $category,
            ':author'       => $author,
            ':published_at' => $publishedAt
        ]);

        // Redirect back home to display the update
        header('Location: ' . BASE_URL . 'index.php?success=1');
        exit;

    } catch (\PDOException $e) {
        error_log("Database insertion failed: " . $e->getMessage());
        die("An error occurred while publishing the article. Check duplicate titles/slugs.");
    }

} else {
    die("Direct access restricted.");
}