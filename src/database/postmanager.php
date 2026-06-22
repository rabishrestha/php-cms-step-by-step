<?php
namespace HamroNews\Database;

// 1. Explicitly pull in the files relative to this folder so VS Code and PHP see them
require_once __DIR__ . '/../models/postinterface.php';
require_once __DIR__ . '/../models/post.php';

// 2. CRITICAL FIX: Import the correct namespace for the Post class!
use HamroNews\Models\Post; 
use Exception;

class PostManager {
    /**
     * Converts raw array data into an array of concrete Post objects
     * * @param array $rawPosts Raw associative array data
     * @return Post[] Array of Post class instances
     */
    public static function fetchAll(array $rawPosts): array {
        $objectList = [];
        
        foreach ($rawPosts as $rawItem) {
            try {
                // PHP now maps "Post" to "HamroNews\Models\Post" perfectly!
                $objectList[] = new Post($rawItem);
            } catch (Exception $e) {
                error_log("Skipped post instantiation: " . $e->getMessage());
            }
        }
        
        return $objectList;
    }
}