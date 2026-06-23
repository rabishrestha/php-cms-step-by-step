<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/db.php';

use HamroNews\Database\Database;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName  = filter_var(trim($_POST['full_name']), FILTER_UNSAFE_RAW);
    $email     = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $contactNo = filter_var(trim($_POST['contact_no']), FILTER_UNSAFE_RAW);
    $username  = filter_var(trim($_POST['username']), FILTER_UNSAFE_RAW);
    $role      = in_array($_POST['role'], ['Admin', 'Reporter']) ? $_POST['role'] : 'Reporter';
    $password  = trim($_POST['password']);

    if (!$email || empty($fullName) || empty($username) || empty($password)) {
        die("Missing or malformed input registration data properties.");
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    try {
        $db = Database::getConnection();
        $sql = "INSERT INTO users (username, password, full_name, email, contact_no, role, status, email_confirmed) 
                VALUES (:username, :password, :full_name, :email, :contact_no, :role, 'Pending', 0)";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':username'   => $username,
            ':password'   => $hashedPassword,
            ':full_name'  => $fullName,
            ':email'      => $email,
            ':contact_no' => $contactNo,
            ':role'       => $role
        ]);

        // Simulated Verification Email Signature Track
        error_log("CMS simulated confirmation email dispatched safely targeting: [$email]");

        header('Location: ' . BASE_URL . 'register.php?success=1');
        exit;
    } catch (\PDOException $e) {
        die("Registration failure. The chosen username or email coordinate might compete with existing entries.");
    }
}