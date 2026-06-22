<?php
require_once __DIR__ . '/../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Unpack hidden identifiers and tracking variables
    $id       = (int)$_POST['id'];
    $title    = filter_var(trim($_POST['title']), FILTER_UNSAFE_RAW);
    $category = filter_var(trim($_POST['category']), FILTER_UNSAFE_RAW);
    $author   = filter_var(trim($_POST['author']), FILTER_UNSAFE_RAW);
    $summary  = filter_var(trim($_POST['summary']), FILTER_UNSAFE_RAW);
    $content  = filter_var(trim($_POST['content']), FILTER_UNSAFE_RAW);
    
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
    $storageFile = __DIR__ . '/user_submitted_posts.txt';

    if (file_exists($storageFile)) {
        // Read entire document lines collection into an execution array buffer stack
        $fileLines = file($storageFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $updatedLinesContent = "";

        foreach ($fileLines as $line) {
            $postData = json_decode($line, true);
            
            if (is_array($postData) && (int)$postData['id'] === $id) {
                // Found a match! Overwrite the old key variables with the fresh post values
                $postData['title']        = $title;
                $postData['slug']         = $slug;
                $postData['category']     = $category;
                $postData['author']       = $author;
                $postData['summary']      = $summary;
                $postData['content']      = $content;
                $postData['published_at'] = date('F j, Y') . ' (Edited)'; // Append structural tracking
            }
            
            // Re-pack the line entry back down into flat-file text formats safely
            $updatedLinesContent .= json_encode($postData) . PHP_EOL;
        }

        // Commit everything back to disk overwriting previous tracking logs completely
        file_put_contents($storageFile, $updatedLinesContent, LOCK_EX);
    }

    // Go right back to the admin control desk grid to see verification
    header('Location: ' . BASE_URL . 'dashboard.php');
    exit;

} else {
    die("Direct access restricted.");
}