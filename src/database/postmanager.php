<?php
namespace HamroNews\Database;

require_once __DIR__ . '/../../config/config.php';
require_once ROOT_PATH . 'src/models/post.php';

use HamroNews\Models\Post;
use Exception;

class PostManager {
    /**
     * Converts raw array data and filesystem updates into Unified Post Objects
     * * @param array $staticPosts The baseline hardcoded array
     * @return Post[] Consolidated array of Post instances
     */
    public static function fetchAll(array $staticPosts): array {
        $objectList = [];
        
        // 1. Process the base static array items first
        foreach ($staticPosts as $rawItem) {
            try {
                $objectList[] = new Post($rawItem);
            } catch (Exception $e) {
                error_log("Skipped static item: " . $e->getMessage());
            }
        }
        
        // 2. File Handling: Read dynamically written file items (Syllabus: Unit 4)
        $storageFile = __DIR__ . '/user_submitted_posts.txt';
        
        if (file_exists($storageFile)) {
            // Open the file stream in read-only mode safely
            $fileHandle = fopen($storageFile, 'r');
            
            if ($fileHandle) {
                // Read row item line-by-line until end-of-file (EOF)
                while (($line = fgets($fileHandle)) !== false) {
                    $trimmedLine = trim($line);
                    if (empty($trimmedLine)) continue;
                    
                    // Unserialize the data payload back into an associative array
                    $customData = json_decode($trimmedLine, true);
                    
                    if (is_array($customData)) {
                        try {
                            // Insert the user-submitted file object at the START of the feed
                            array_unshift($objectList, new Post($customData));
                        } catch (Exception $e) {
                            error_log("Skipped custom file item: " . $e->getMessage());
                        }
                    }
                }
                // Close the stream resource channel to save system performance
                fclose($fileHandle);
            }
        }
        
        return $objectList;
    }
}