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

    /**
     * Fetches a single article from the database matching a unique slug
     * @param string $slug The URL slug of the post
     * @return Post|null Returns a Post object or null if not found
     */
    public static function fetchBySlug(string $slug): ?Post {
        if (empty(trim($slug))) {
            return null;
        }

        try {
            $db = Database::getConnection();

            // Use a prepared statement with a WHERE clause to target the unique slug
            $stmt = $db->prepare("SELECT * FROM posts WHERE slug = :slug LIMIT 1");
            $stmt->execute([':slug' => trim($slug)]);
            
            $row = $stmt->fetch();
            
            // If a matching row is found, return it as a Post object; otherwise return null
            return $row ? new Post($row) : null;
            
        } catch (Exception $e) {
            error_log("Database fetchBySlug failed: " . $e->getMessage());
            return null;
        }
    }
}