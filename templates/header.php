<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hamro News | Reliable Nepali Digital Media</title>
    
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL; ?>assets/images/favicon.ico?v=1">
    <link rel="stylesheet" href="<?= BASE_URL; ?>assets/css/style.css">
</head>
<body>

    <header class="main-header">
        <div class="logo-container">
            <a href="<?= BASE_URL; ?>index.php" class="brand-link">
                <img src="<?= BASE_URL; ?>assets/images/logo.png" alt="Hamro News Logo" class="brand-logo">
                <span class="brand-text">Hamro <span>News</span></span>
            </a>
        </div>
        
        <nav class="nav-links" style="display: flex; gap: 20px; align-items: center;">
            <a href="<?= BASE_URL; ?>index.php" style="color: white; text-decoration: none; font-weight: bold;">🌐 News Stream Feed</a>
            
            <?php
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            if (isset($_SESSION['user_id'])): 
                $userRole = $_SESSION['role'] ?? 'Reporter';
            ?>
                <a href="<?= BASE_URL; ?>dashboard.php" style="color: white; text-decoration: none; font-weight: bold;">📊 Control Desk</a>
                <span style="color: #4ec9b0; font-size: 0.9rem;">👋 Namaste, <strong><?= htmlspecialchars($_SESSION['full_name']); ?></strong> (<?= $userRole ?>)</span>
                <a href="<?= BASE_URL; ?>logout.php" style="background: #dc3545; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 0.85rem;">Sign Out</a>
            <?php else: ?>
                <a href="<?= BASE_URL; ?>login.php" style="color: white; text-decoration: none; font-weight: bold; font-size: 0.85rem;">🔑 Sign In</a>
                <a href="<?= BASE_URL; ?>register.php" style="background: #28a745; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 0.85rem;">📝 Register Account</a>
            <?php endif; ?>
        </nav>
    </header>

    <div class="wrapper">