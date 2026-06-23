<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/db.php';

session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'Admin') { die("Access unauthorized."); }

use HamroNews\Database\Database;

$action   = $_GET['action'] ?? '';
$targetId = (int)($_GET['id'] ?? 0);

if ($targetId === (int)$_SESSION['user_id']) { die("Self-mutations restricted."); }

if ($targetId > 0 && in_array($action, ['approve', 'deactivate'])) {
    $newStatus = ($action === 'approve') ? 'Active' : 'Inactive';
    try {
        $db = Database::getConnection();
        // Soft deletion switch updating user lifecycle status cleanly
        $stmt = $db->prepare("UPDATE users SET status = :status WHERE id = :id");
        $stmt->execute([':status'=>$newStatus, ':id'=>$targetId]);
        header('Location: ' . BASE_URL . 'users.php');
        exit;
    } catch (\PDOException $e) { die("Operational mutation execution breakdown."); }
}