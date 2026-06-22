<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hamro News | Reliable Nepali Digital Media</title>
    
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico?v=1">
    
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <header class="main-header">
        <div class="logo-container">
            <a href="index.php" class="brand-link">
                <img src="assets/images/logo.png" alt="Hamro News Logo" class="brand-logo">
                <span class="brand-text">Hamro <span>News</span></span>
            </a>
        </div>
        
        <nav class="nav-links" style="display: flex; gap: 20px; align-items: center;">
            <a href="index.php" style="color: white; text-decoration: none; font-weight: bold;">🌐 News Stream Feed</a>
            
            <?php
            // Detect active authentication status state configurations dynamically
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            if (isset($_SESSION['user_id'])): 
            ?>
                <a href="dashboard.php" style="color: white; text-decoration: none; font-weight: bold;">📊 Control Desk</a>
                <span style="color: #4ec9b0; font-size: 0.9rem;">👋 Namaste, <strong><?= htmlspecialchars($_SESSION['full_name']); ?></strong></span>
                <a href="logout.php" style="background: #dc3545; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 0.85rem;">Sign Out</a>
            <?php else: ?>
                <a href="login.php" style="background: #0275d8; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 0.85rem;">🔑 Administrative Portal</a>
            <?php endif; ?>
        </nav>
    </header>

    <div class="wrapper">