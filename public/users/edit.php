<?php
// 1. Load config FIRST to guarantee BASE_URL and SRC_PATH are initialized immediately
require_once __DIR__ . '/../../config/config.php';

if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

// Secure Guard Layer: Restrict to logged-in Admins only
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'Admin') {
    die("Access Denied.");
} 

// Include our newly decoupled class-based user module
require_once SRC_PATH . 'database/users/usermanager.php';

use HamroNews\Database\UserManager;

$targetId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$currentUser = null;

if ($targetId > 0) {
    // Dynamic object mapping using our decoupled user controller method
    $currentUser = UserManager::fetchById($targetId);
}

require_once TEMPLATE_PATH . 'header.php';
?>

<main class="content-area dashboard-grid">
    <aside class="sidebar">
        <h3>CMS Actions</h3>
        <ul>
            <li><a href="<?= BASE_URL; ?>dashboard.php">📝 Manage Articles</a></li>
            <li><a href="<?= BASE_URL; ?>categories/manage.php">📁 Manage Categories</a></li>
            <li><a href="<?= BASE_URL; ?>users/manage.php" style="font-weight: bold; color: #dc3545;">👥 Manage User Access Control</a></li>
            <li><a href="<?= BASE_URL; ?>index.php">🌐 View Live Site</a></li>
        </ul>
    </aside>

    <section class="main-dashboard-content">
        <h1>Edit User Profile Matrix</h1>
        
        <?php if ($currentUser): ?>
            <div class="form-container" style="background:#fff; padding:25px; border-radius:6px; border:1px solid #e3e6f0; max-width:600px;">
                <form action="<?= BASE_URL; ?>../src/database/users/update.php" method="POST" style="display:flex; flex-direction:column; gap:15px;">
                    <input type="hidden" name="id" value="<?= $currentUser->getId(); ?>">

                    <div style="display:flex; gap:15px;">
                        <div style="flex:1;">
                            <label style="display:block; font-weight:bold; font-size:0.9rem;">Full Name</label>
                            <input type="text" name="full_name" required value="<?= htmlspecialchars($currentUser->getFullName()); ?>" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; box-sizing: border-box;">
                        </div>
                        <div style="flex:1;">
                            <label style="display:block; font-weight:bold; font-size:0.9rem;">Username</label>
                            <input type="text" name="username" required value="<?= htmlspecialchars($currentUser->getUsername()); ?>" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; box-sizing: border-box;">
                        </div>
                    </div>

                    <div style="display:flex; gap:15px;">
                        <div style="flex:1;">
                            <label style="display:block; font-weight:bold; font-size:0.9rem;">Email</label>
                            <input type="email" name="email" required value="<?= htmlspecialchars($currentUser->getEmail()); ?>" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; box-sizing: border-box;">
                        </div>
                        <div style="flex:1;">
                            <label style="display:block; font-weight:bold; font-size:0.9rem;">Contact Number</label>
                            <input type="text" name="contact_no" value="<?= htmlspecialchars($currentUser->getContactNo() ?? ''); ?>" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; box-sizing: border-box;">
                        </div>
                    </div>

                    <div style="display:flex; gap:15px;">
                        <div style="flex:1;">
                            <label style="display:block; font-weight:bold; font-size:0.9rem;">Role</label>
                            <select name="role" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; box-sizing: border-box;">
                                <option value="Reporter" <?= $currentUser->getRole() === 'Reporter' ? 'selected' : '' ?>>Reporter</option>
                                <option value="Admin" <?= $currentUser->getRole() === 'Admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                        </div>
                        <div style="flex:1;">
                            <label style="display:block; font-weight:bold; font-size:0.9rem;">Status</label>
                            <select name="status" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; box-sizing: border-box;">
                                <option value="Pending" <?= $currentUser->getStatus() === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="Active" <?= $currentUser->getStatus() === 'Active' ? 'selected' : '' ?>>Active</option>
                                <option value="Inactive" <?= $currentUser->getStatus() === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div style="background:#f8f9fa; padding:15px; border-radius:4px; border:1px solid #e3e6f0;">
                        <label style="display:block; font-weight:bold; font-size:0.9rem; color:#dc3545; margin-bottom: 5px;">Password Override Reset</label>
                        <input type="password" name="password" placeholder="Leave blank to preserve current credentials..." style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; box-sizing: border-box;">
                    </div>

                    <div style="display:flex; gap:10px;">
                        <button type="submit" style="background:#0275d8; color:white; padding:10px 20px; border:none; border-radius:4px; font-weight:bold; cursor:pointer;">💾 Save Profile Settings</button>
                        <a href="<?= BASE_URL; ?>users/manage.php" style="background:#6c757d; color:white; padding:10px 20px; border-radius:4px; text-decoration:none; font-weight:bold; font-size:0.9rem; line-height: 1.2;">Cancel</a>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div style="padding: 30px; background: #f8d7da; color: #721c24; border-radius: 4px; text-align: center; font-weight: bold;">
                ❌ Error: The requested user record could not be discovered.
            </div>
        <?php endif; ?>
    </section>
</main>

<?php require_once TEMPLATE_PATH . 'footer.php'; ?>