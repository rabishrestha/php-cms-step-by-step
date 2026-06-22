<?php
// Ensure configurations are loaded
require_once __DIR__ . '/../../config/config.php';

// Check if the file is accessed via a real POST form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Retrieving & Sanitizing Data (Syllabus: Form Handling)
    $title    = filter_var(trim($_POST['title']), FILTER_UNSAFE_RAW);
    $category = filter_var(trim($_POST['category']), FILTER_UNSAFE_RAW);
    $author   = filter_var(trim($_POST['author']), FILTER_UNSAFE_RAW);
    $summary  = filter_var(trim($_POST['summary']), FILTER_UNSAFE_RAW);
    $content  = filter_var(trim($_POST['content']), FILTER_UNSAFE_RAW);

    // 2. Business Logic: Turn the title into a url-safe slug
    // e.g., "Hello Kathmandu!" becomes "hello-kathmandu"
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));

    // 3. Construct a simulated data array payload
    $newPostPayload = [
        'id'           => time(), // Generate a unique mock numeric ID using timestamp
        'title'        => $title,
        'slug'         => $slug,
        'summary'      => $summary,
        'content'      => $content,
        'category'     => $category,
        'author'       => $author,
        'published_at' => date('F j, Y'),
        'image'        => 'default.jpg'
    ];

    // 4. File Handling: Writing data securely to disk (Syllabus: File Handling)
    // We will append this data structure to a separate text file or JSON log
    $storageFile = __DIR__ . '/user_submitted_posts.txt';
    
    // Convert array to a string line item using JSON format representation
    $serializedData = json_encode($newPostPayload) . PHP_EOL;

    // Save it to disk using FILE_APPEND so entries don't overwrite previous ones
    file_put_contents($storageFile, $serializedData, FILE_APPEND | LOCK_EX);

    // 5. Redirect the client back to the public homepage to witness changes!
    header('Location: ' . BASE_URL . 'index.php?success=1');
    exit;

} else {
    // Block unauthorized direct URL access
    die("Direct access restricted.");
}