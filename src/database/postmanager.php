<?php
namespace HamroNews\Database;

require_once __DIR__ . '/../../config/config.php';
require_once ROOT_PATH . 'src/models/post.php';
require_once __DIR__ . '/db.php';

use HamroNews\Models\Post;
use PDO;
use Exception;

class PostManager {
    
    /**
     * Advanced Dynamic Fetch with Search Filtering and Pagination Controls
     * @return Post[]
     */
    public static function fetchAdvanced(int $page = 1, int $perPage = 6, ?int $categoryId = null, ?string $searchKeyword = null): array {
        $objectList = [];
        $db = Database::getConnection();
        
        $sql = "SELECT p.*, u.full_name AS author_name, c.name AS category_name 
                FROM posts p
                INNER JOIN users u ON p.user_id = u.id
                INNER JOIN categories c ON p.category_id = c.id
                WHERE 1=1";
        
        $params = [];
        
        if ($categoryId !== null && $categoryId > 0) {
            $sql .= " AND p.category_id = :category_id";
            $params[':category_id'] = $categoryId;
        }
        
        if ($searchKeyword !== null && trim($searchKeyword) !== '') {
            $sql .= " AND (p.title LIKE :search OR p.summary LIKE :search OR p.content LIKE :search)";
            $params[':search'] = '%' . trim($searchKeyword) . '%';
        }
        
        $sql .= " ORDER BY p.published_at DESC LIMIT :limit OFFSET :offset";
        $offset = ($page - 1) * $perPage;
        
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            
            foreach ($params as $key => $val) {
                $stmt->bindValue($key, $val);
            }
            
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $objectList[] = new Post($row);
            }
        } catch (Exception $e) {
            error_log("Advanced fetch execution failure: " . $e->getMessage());
        }
        
        return $objectList;
    }

    /**
     * ADVANCED CMS DATA INTERFACE: Fetches a single post record natively matching a unique URL slug
     * @param string $slug The raw URL text routing slug parameter
     * @return Post|null Returns a complete Post instance or null if no record matches
     */
    public static function fetchBySlug(string $slug): ?Post {
        if (empty(trim($slug))) {
            return null;
        }

        try {
            $db = Database::getConnection();
            
            // Execute a unified relational inner join matching the target slug
            $stmt = $db->prepare("SELECT p.*, u.full_name AS author_name, c.name AS category_name 
                                  FROM posts p
                                  INNER JOIN users u ON p.user_id = u.id
                                  INNER JOIN categories c ON p.category_id = c.id
                                  WHERE p.slug = :slug LIMIT 1");
            
            $stmt->execute([':slug' => trim($slug)]);
            $row = $stmt->fetch();
            
            return $row ? new Post($row) : null;
        } catch (Exception $e) {
            error_log("Failed to fetch post by slug coordinate: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Compute maximum item matching boundaries for pagination calculations
     */
    public static function getTotalCount(?int $categoryId = null, ?string $searchKeyword = null): int {
        $db = Database::getConnection();
        $sql = "SELECT COUNT(*) FROM posts WHERE 1=1";
        $params = [];
        
        if ($categoryId !== null && $categoryId > 0) {
            $sql .= " AND category_id = :category_id";
            $params[':category_id'] = $categoryId;
        }
        
        if ($searchKeyword !== null && trim($searchKeyword) !== '') {
            $sql .= " AND (title LIKE :search OR summary LIKE :search OR content LIKE :search)";
            $params[':search'] = '%' . trim($searchKeyword) . '%';
        }
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    /**
     * BACKEND CMS INTERFACE: Fetches all administrative records mapped as concrete objects
     * @return Post[]
     */
    public static function fetchAdminDashboardList(): array {
        $objectList = [];
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT p.*, u.full_name AS author_name, c.name AS category_name 
                                FROM posts p 
                                INNER JOIN users u ON p.user_id = u.id 
                                INNER JOIN categories c ON p.category_id = c.id 
                                ORDER BY p.published_at DESC");
            
            while ($row = $stmt->fetch()) {
                $objectList[] = new Post($row);
            }
        } catch (Exception $e) {
            error_log("Dashboard list pull breakdown: " . $e->getMessage());
        }
        return $objectList;
    }

    /**
     * GLOBAL SYSTEM DATA INTERFACE: Retrieves all categories cleanly from the database
     */
    public static function fetchAllCategories(): array {
        try {
            $db = Database::getConnection();
            return $db->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
        } catch (Exception $e) {
            error_log("Category fetch failure: " . $e->getMessage());
            return [];
        }
    }

    /**
     * ROLE-BASED CMS DATA INTERFACE: Fetches post lists filtered by authorization clearance parameters
     * @param string $role The authenticated user's role ('Admin' or 'Reporter')
     * @param int $userId The primary key ID of the currently authenticated user
     * @return Post[]
     */
    public static function fetchDashboardByRole(string $role, int $userId): array {
        $objectList = [];
        try {
            $db = Database::getConnection();
            
            // 1. Base query template structure
            $sql = "SELECT p.*, u.full_name AS author_name, c.name AS category_name 
                    FROM posts p 
                    INNER JOIN users u ON p.user_id = u.id 
                    INNER JOIN categories c ON p.category_id = c.id";
            
            // 2. Conditionally enforce data isolation boundaries based on role parameters
            if ($role !== 'Admin') {
                $sql .= " WHERE p.user_id = :user_id";
            }
            
            $sql .= " ORDER BY p.published_at DESC";
            
            $stmt = $db->prepare($sql);
            
            if ($role !== 'Admin') {
                $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            
            while ($row = $stmt->fetch()) {
                $objectList[] = new Post($row);
            }
        } catch (Exception $e) {
            error_log("Role-based dashboard dataset pull failure: " . $e->getMessage());
        }
        return $objectList;
    }
}