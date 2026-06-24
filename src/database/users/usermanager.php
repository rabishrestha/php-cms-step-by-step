<?php
namespace HamroNews\Database;

require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../db.php';
require_once ROOT_PATH . 'src/models/user.php';

use HamroNews\Models\User;
use PDO;
use Exception;

class UserManager {

    /**
     * Fetches a single specific user profile mapped directly to a User domain object
     * @param int $id Primary key identifier
     * @return User|null
     */
    public static function fetchById(int $id): ?User {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch();
            
            return $row ? new User($row) : null;
        } catch (Exception $e) {
            error_log("UserManager::fetchById failed: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Fetches all registered users mapped directly to User objects
     * @return User[]
     */
    public static function fetchAll(): array {
        $objectList = [];
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT id, username, full_name, email, contact_no, role, status FROM users ORDER BY id DESC");
            while ($row = $stmt->fetch()) {
                $objectList[] = new User($row);
            }
        } catch (Exception $e) {
            error_log("UserManager::fetchAll failed: " . $e->getMessage());
        }
        return $objectList;
    }

    /**
     * Updates an individual user's authorization status (Approve / Deactivate)
     */
    public static function updateStatus(int $id, string $status): bool {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("UPDATE users SET status = :status WHERE id = :id");
            return $stmt->execute([':status' => $status, ':id' => $id]);
        } catch (Exception $e) {
            error_log("UserManager::updateStatus failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Persists updated profile matrix metrics and password modifications safely
     */
    public static function update(int $id, string $fullName, string $username, string $email, ?string $contactNo, string $role, string $status, ?string $password = null): bool {
        try {
            $db = Database::getConnection();
            
            $sql = "UPDATE users 
                    SET full_name = :full_name, 
                        username = :username, 
                        email = :email, 
                        contact_no = :contact_no, 
                        role = :role, 
                        status = :status";
            
            $params = [
                ':full_name'  => $fullName,
                ':username'   => $username,
                ':email'      => $email,
                ':contact_no' => $contactNo,
                ':role'       => $role,
                ':status'     => $status,
                ':id'         => $id
            ];

            // Conditionally append hashed password if overriding credentials
            if ($password !== null && trim($password) !== '') {
                $sql .= ", password = :password";
                $params[':password'] = password_hash($password, PASSWORD_BCRYPT);
            }

            $sql .= " WHERE id = :id";
            
            $stmt = $db->prepare($sql);
            return $stmt->execute($params);
        } catch (Exception $e) {
            error_log("UserManager::update failed: " . $e->getMessage());
            return false;
        }
    }
}