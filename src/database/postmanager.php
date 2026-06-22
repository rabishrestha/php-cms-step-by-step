<?php
namespace HamroNews\Database;

require_once __DIR__ . '/../../config/config.php';
require_once ROOT_PATH . 'src/models/post.php';
require_once __DIR__ . '/db.php'; // Load database connection module

use HamroNews\Models\Post;
use PDO;
use Exception;

class PostManager {
    /**
     * Fetches all articles directly from the MySQL database table
     * @return Post[] Unified collection array of Post instances
     */
    public static function fetchAll(): array {
        $objectList = [];
        
        try {
            // 1. Obtain our centralized database socket channel
            $db = Database::getConnection();
            
            // 2. Query all items sorted newest first
            $stmt = $db->query("SELECT * FROM posts ORDER BY id DESC");
            
            // 3. Loop through database rows and map them into objects
            while ($row = $stmt->fetch()) {
                try {
                    $objectList[] = new Post($row);
                } catch (Exception $e) {
                    error_log("Skipped database mapping item: " . $e->getMessage());
                }
            }
        } catch (Exception $e) {
            error_log("Database fetchAll failed: " . $e->getMessage());
        }
        
        return $objectList;
    }
}