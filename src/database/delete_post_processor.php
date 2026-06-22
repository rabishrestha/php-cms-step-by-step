<?php
require_once __DIR__ . '/../../config/config.php';

// 1. Capture target ID parameter from the URL bar query string
$targetId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($targetId > 0) {
    $storageFile = __DIR__ . '/user_submitted_posts.txt';

    if (file_exists($storageFile)) {
        // 2. Read entire document lines collection into an execution array buffer stack
        $fileLines = file($storageFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $remainingLinesContent = "";
        $deletedSuccessfully = false;

        foreach ($fileLines as $line) {
            $postData = json_decode($line, true);
            
            // 3. File Handling Filter: If it matches the ID, skip it! (Exclusion method)
            if (is_array($postData) && (int)$postData['id'] === $targetId) {
                $deletedSuccessfully = true;
                continue; // Skips appending this line to the stream buffer string
            }
            
            // Re-pack the surviving records back down into flat-file text formats
            $remainingLinesContent .= $line . PHP_EOL;
        }

        // 4. Overwrite file with remaining content entries only
        file_put_contents($storageFile, $remainingLinesContent, LOCK_EX);
    }
    
    // Redirect back to the admin dashboard grid
    header('Location: ' . BASE_URL . 'dashboard.php?deleted=' . ($deletedSuccessfully ? '1' : '0'));
    exit;
} else {
    die("Error: Invalid transaction parameter ID signature missing.");
}