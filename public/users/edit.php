<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'Admin') {
    die("Access Denied.");
} 

require_once __DIR__ . '/../config/config.php';
require_once SRC_PATH . 'database/db.php';

use HamroNews\Database\Database;
$targetId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$userData = null;

if ($targetId > 0) {
    $db = Database::getConnection();
    $stmt = $db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $targetId]);
    $userData = $stmt->fetch();
}

require_once TEMPLATE_PATH . 'header.php';
?>

<main class="content-area dashboard-grid">
    <aside class="sidebar">
        <h3>CMS Actions</h3>
        <ul>
            <li><a href="dashboard.php">📝 Manage Articles</a></li>
            <li><a href="categories.php">📁 Manage Categories</a></li>
            <li><a href="users.php" style="font-weight: bold; color: #dc3545;">👥 Manage User Access Control</a></li>
        </ul>
    </aside>

    <section class="main-dashboard-content">
        <h1>Edit User Profile Matrix</h1>
        
        <?php if ($userData): ?>
            <div class="form-container" style="background:#fff; padding:25px; border-radius:6px; border:1px solid #e3e6f0; max-width:600px;">
                <form action="../src/database/update_user_processor.php" method="POST" style="display:flex; flex-direction:column; gap:15px;">
                    <input type="hidden" name="id" value="<?= $userData['id'] ?>">

                    <div style="display:flex; gap:15px;">
                        <div style="flex:1;">
                            <label style="display:block; font-weight:bold; font-size:0.9rem;">Full Name</label>
                            <input type="text" name="full_name" required value="<?= htmlspecialchars($userData['full_name']) ?>" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
                        </div>
                        <div style="flex:1;">
                            <label style="display:block; font-weight:bold; font-size:0.9rem;">Username</label>
                            <input type="text" name="username" required value="<?= htmlspecialchars($userData['username']) ?>" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
                        </div>
                    </div>

                    <div style="display:flex; gap:15px;">
                        <div style="flex:1;">
                            <label style="display:block; font-weight:bold; font-size:0.9rem;">Email</label>
                            <input type="email" name="email" required value="<?= htmlspecialchars($userData['email']) ?>" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
                        </div>
                        <div style="flex:1;">
                            <label style="display:block; font-weight:bold; font-size:0.9rem;">Contact Number</label>
                            <input type="text" name="contact_no" value="<?= htmlspecialchars($userData['contact_no'] ?? '') ?>" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
                        </div>
                    </div>

                    <div style="display:flex; gap:15px;">
                        <div style="flex:1;">
                            <label style="display:block; font-weight:bold; font-size:0.9rem;">Role</label>
                            <select name="role" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
                                <option value="Reporter" <?= $userData['role'] === 'Reporter' ? 'selected' : '' ?>>Reporter</option>
                                <option value="Admin" <?= $userData['role'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                        </div>
                        <div style="flex:1;">
                            <label style="display:block; font-weight:bold; font-size:0.9rem;">Status</label>
                            <select name="status" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
                                <option value="Pending" <?= $userData['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="Active" <?= $userData['status'] === 'Active' ? 'selected' : '' ?>>Active</option>
                                <option value="Inactive" <?= $userData['status'] === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div style="background:#f8f9fa; padding:15px; border-radius:4px; border:1px solid #e3e6f0;">
                        <label style="display:block; font-weight:bold; font-size:0.9rem; color:#dc3545;">Password Override Reset</label>
                        <input type="password" name="password" placeholder="Leave blank to preserve current credentials..." style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
                    </div>

                    <div style="display:flex; gap:10px;">
                        <button type="submit" style="background:#0275d8; color:white; padding:10px 20px; border:none; border-radius:4px; font-weight:bold; cursor:pointer;">💾 Save Profile Settings</button>
                        <a href="users.php" style="background:#6c757d; color:white; padding:10px 20px; border-radius:4px; text-decoration:none; font-weight:bold; font-size:0.9rem;">Cancel</a>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php require_once TEMPLATE_PATH . 'footer.php'; ?>