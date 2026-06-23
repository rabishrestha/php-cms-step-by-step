<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/db.php';

use HamroNews\Database\Database;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Extract and scrub incoming submission variables
    $fullName  = filter_var(trim($_POST['full_name']), FILTER_UNSAFE_RAW);
    $email     = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $contactNo = filter_var(trim($_POST['contact_no']), FILTER_UNSAFE_RAW);
    $username  = filter_var(trim($_POST['username']), FILTER_UNSAFE_RAW);
    $role      = in_array($_POST['role'], ['Admin', 'Reporter']) ? $_POST['role'] : 'Reporter';
    $password  = trim($_POST['password']);

    // Validation Guard Checks
    if (!$email) {
        die("Fatal Error: Invalid email layout format.");
    }
    if (empty($fullName) || empty($username) || empty($password)) {
        die("Fatal Error: Missing mandatory fields.");
    }

    // 2. Encrypt plaintext credentials natively using strong BCRYPT hashes
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    try {
        $db = Database::getConnection();
        
        // 3. Insert record securely using named placeholders. Sets initial status to 'Pending'
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

        // 4. Simulated Verification Mail Log Handshake
        error_log("CMS simulated email dispatch verification targeting account: [$email]");

        // Redirect back smoothly to show the green alert box
        header('Location: ' . BASE_URL . 'register.php?success=1');
        exit;
    } catch (\PDOException $e) {
        error_log("Registration SQL insertion failure: " . $e->getMessage());
        die("Error processing sign-up. The chosen username handle or email address might already be active.");
    }
} else {
    die("Direct access restricted.");
}