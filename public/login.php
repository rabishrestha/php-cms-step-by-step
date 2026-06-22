<?php
require_once __DIR__ . '/../config/config.php';

// If a session already exists, skip the login view form entirely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'dashboard.php');
    exit;
}

require_once TEMPLATE_PATH . 'header.php';
?>

<main class="content-area" style="max-width: 450px; margin: 60px auto; padding: 20px; background: #fff; border-radius: 6px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); border: 1px solid #e3e6f0;">
    <h1 style="font-size: 1.6rem; text-align: center; color: #1a1a2e; margin-bottom: 5px;">CMS Secure Gateway</h1>
    <p style="text-align: center; color: #777; font-size: 0.9rem; margin-bottom: 25px;">Hamro News Publisher Authentication Panel</p>

    <?php if (isset($_GET['error'])): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 0.85rem; font-weight: bold; border-left: 4px solid #dc3545;">
            ❌ Invalid username credentials or password parameters.
        </div>
    <?php endif; ?>

    <form action="../src/database/login_processor.php" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
        <div>
            <label style="display: block; font-weight: bold; font-size: 0.9rem; margin-bottom: 5px;">Username Token</label>
            <input type="text" name="username" required placeholder="e.g., admin" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
        </div>

        <div>
            <label style="display: block; font-weight: bold; font-size: 0.9rem; margin-bottom: 5px;">Security Password</label>
            <input type="password" name="password" required placeholder="•••••••••" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
        </div>

        <button type="submit" style="background: #1a1a2e; color: white; padding: 12px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; margin-top: 5px; font-size: 1rem;">
            🔒 Secure Verification Sign In
        </button>
    </form>
</main>

<?php
require_once TEMPLATE_PATH . 'footer.php';
?>