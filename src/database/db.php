<?php
namespace HamroNews\Database;

// Ensure configuration constants are loaded if not already pulled in by entry files
require_once __DIR__ . '/../../config/config.php';

use PDO;
use PDOException;

class Database {
    private static ?PDO $connection = null;

    /**
     * Establishes or restores a shared PDO connection handler channel
     * @return PDO Active database resource stream instance
     */
    public static function getConnection(): PDO {
        if (self::$connection === null) {
            
            // Map credentials securely using global configuration constants
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$connection = new PDO($dsn, var_export(DB_USER, true) === "''" ? '' : DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                // Log error details privately and display a clean abstraction message
                error_log("Database connection failure: " . $e->getMessage());
                die("Critical Database Connection Failure. Please check system configurations.");
            }
        }
        return self::$connection;
    }
}