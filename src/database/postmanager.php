<?php
namespace HamroNews\Database;

use HamroNews\Database\Post;
use Exception;

class PostManager {
    /**
     * Converts raw array data into an array of concrete Post objects
     * 
     * @param array $rawPosts Raw associative array data
     * @return Post[] Array of Post class instances
     */
    public static function fetchAll(array $rawPosts): array {
        $objectList = [];
        
        foreach ($rawPosts as $rawItem) {
            try {
                // Instantiating the Post objects here instead
                $objectList[] = new Post($rawItem);
            } catch (Exception $e) {
                error_log("Skipped post instantiation: " . $e->getMessage());
            }
        }
        
        return $objectList;
    }
}