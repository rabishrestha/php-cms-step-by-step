<?php
namespace HamroNews\Database;

require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../db.php';
require_once ROOT_PATH . 'src/models/category.php';

use HamroNews\Models\Category;
use PDO;
use Exception;

class CategoryManager {

    /**
     * Fetches a single specific category model record by primary key ID
     */
    public static function fetchById(int $id): ?Category {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM categories WHERE id = :id LIMIT 1");
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch();
            return $row ? new Category($row) : null;
        } catch (Exception $e) {
            error_log("CategoryManager::fetchById failed: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Persists a new category choice array into database records
     */
    public static function create(string $name, string $slug): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO categories (name, slug) VALUES (:name, :slug)");
        return $stmt->execute([':name' => $name, ':slug' => $slug]);
    }

    /**
     * Mutates structural data metrics for an existing record row
     */
    public static function update(int $id, string $name, string $slug): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE categories SET name = :name, slug = :slug WHERE id = :id");
        return $stmt->execute([':name' => $name, ':slug' => $slug, ':id' => $id]);
    }

    /**
     * Drops a category record row from reference indexes
     */
    public static function delete(int $id): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}