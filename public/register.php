<?php
require_once __DIR__ . '/../config/config.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
require_once TEMPLATE_PATH . 'header.php';
?>

<main class="content-area" style="max-width: 500px; margin: 40px auto; padding: 25px; background: #fff; border-radius: 6px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); border: 1px solid #e3e6f0;">
    <h1 style="font-size: 1.6rem; text-align: center; color: #1a1a2e; margin-bottom: 5px;">Join Hamro News Team</h1>
    <p style="text-align: center; color: #777; font-size: 0.9rem; margin-bottom: 25px;">Create an account. Access requires Admin approval activation.</p>

    <?php if (isset($_GET['success'])): ?>
        <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 15px; font-weight: bold; font-size: 0.85rem;">
            🎉 Registration submitted! An administrator will review your contact details and approve activation shortly.
        </div>
    <?php endif; ?>

    <form action="../src/database/register_processor.php" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
        <div>
            <label style="display: block; font-weight: bold; font-size: 0.9rem; margin-bottom: 5px;">Full Legal Name</label>
            <input type="text" name="full_name" required placeholder="e.g., Ram Bahadur" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
        </div>

        <div style="display: flex; gap: 15px;">
            <div style="flex: 1;">
                <label style="display: block; font-weight: bold; font-size: 0.9rem; margin-bottom: 5px;">Email Address</label>
                <input type="email" name="email" required placeholder="name@domain.com" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
            </div>
            <div style="flex: 1;">
                <label style="display: block; font-weight: bold; font-size: 0.9rem; margin-bottom: 5px;">Contact Number</label>
                <input type="text" name="contact_no" placeholder="98XXXXXXXX" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
            </div>
        </div>

        <div>
            <label style="display: block; font-weight: bold; font-size: 0.9rem; margin-bottom: 5px;">Desired Username</label>
            <input type="text" name="username" required placeholder="e.g., rambahadur" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
        </div>

        <div>
            <label style="display: block; font-weight: bold; font-size: 0.9rem; margin-bottom: 5px;">System Role Preference</label>
            <select name="role" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                <option value="Reporter">Reporter (Write News Only)</option>
                <option value="Admin">Admin (Full System Management)</option>
            </select>
        </div>

        <div>
            <label style="display: block; font-weight: bold; font-size: 0.9rem; margin-bottom: 5px;">Security Password</label>
            <input type="password" name="password" required placeholder="•••••••••" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
        </div>

        <button type="submit" style="background: #1a1a2e; color: white; padding: 12px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 1rem;">
            📝 Submit Account Registration Request
        </button>
    </form>
</main>

<?php require_once TEMPLATE_PATH . 'footer.php'; ?>