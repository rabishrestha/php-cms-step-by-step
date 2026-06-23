<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/db.php';

session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'Admin') { die("Unauthorized."); }

use HamroNews\Database\Database;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id        = (int)$_POST['id'];
    $fullName  = filter_var(trim($_POST['full_name']), FILTER_UNSAFE_RAW);
    $username  = filter_var(trim($_POST['username']), FILTER_UNSAFE_RAW);
    $email     = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $contactNo = filter_var(trim($_POST['contact_no']), FILTER_UNSAFE_RAW);
    $role      = $_POST['role'];
    $status    = $_POST['status'];
    $password  = trim($_POST['password']);

    if (!$email || empty($fullName) || empty($username) || $id <= 0) { die("Invalid inputs."); }

    try {
        $db = Database::getConnection();
        $sql = "UPDATE users SET full_name = :full_name, username = :username, email = :email, contact_no = :contact_no, role = :role, status = :status";
        $params = [':full_name'=>$fullName, ':username'=>$username, ':email'=>$email, ':contact_no'=>$contactNo, ':role'=>$role, ':status'=>$status, ':id'=>$id];

        if (!empty($password)) {
            $sql .= ", password = :password";
            $params[':password'] = password_hash($password, PASSWORD_BCRYPT);
        }
        $sql .= " WHERE id = :id";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        header('Location: ' . BASE_URL . 'users.php?updated=1');
        exit;
    } catch (\PDOException $e) { die("Database configuration execution update error."); }
}