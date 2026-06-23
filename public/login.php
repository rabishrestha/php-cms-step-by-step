<?php
require_once __DIR__ . '/../config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'dashboard.php');
    exit;
}

require_once TEMPLATE_PATH . 'header.php';
?>

<main class="content-area" style="max-width: 450px; margin: 60px auto; padding: 25px; background: #fff; border-radius: 6px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); border: 1px solid #e3e6f0;">
    <div style="text-align: center; margin-bottom: 20px;">
        <img src="assets/images/logo.png" alt="Hamro News Logo" style="max-width: 180px; height: auto; fallback: url('logo.png');">
    </div>

    <h1 style="font-size: 1.5rem; text-align: center; color: #1a1a2e; margin-bottom: 5px;">CMS Secure Gateway</h1>
    <p style="text-align: center; color: #777; font-size: 0.9rem; margin-bottom: 25px;">Hamro News Publisher Authentication Panel</p>

    <?php if (isset($_GET['error'])): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 0.85rem; font-weight: bold; border-left: 4px solid #dc3545;">
            ❌ Access Denied: Invalid credentials or pending account clearance.
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

    <div style="text-align: center; margin-top: 20px; border-top: 1px solid #eee; padding-top: 15px; font-size: 0.9rem;">
        <span style="color:#555;">New to the newsroom team?</span><br>
        <a href="register.php" style="color: #28a745; font-weight: bold; text-decoration: none; display: inline-block; margin-top: 5px;">📝 Request Account Registration &rarr;</a>
    </div>
</main>

<?php
require_once TEMPLATE_PATH . 'footer.php';
?>