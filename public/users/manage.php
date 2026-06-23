<?php
// 1. Load config FIRST to guarantee BASE_URL exists before any redirects or template includes
require_once __DIR__ . '/../../config/config.php';

if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

// Secure Guard Access Layer
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'Admin') {
    die("Access Denied: Administrative Clearance Required.");
}

require_once SRC_PATH . 'database/db.php';

use HamroNews\Database\Database;
try {
    $db = Database::getConnection();
    $usersList = $db->query("SELECT id, username, full_name, email, contact_no, role, status FROM users ORDER BY id DESC")->fetchAll();
} catch (\Exception $e) {
    error_log("Failed to fetch user index list: " . $e->getMessage());
    $usersList = [];
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
        <h1>User Governance Desk</h1>
        
        <table class="cms-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name & Username</th>
                    <th>Contact Coordinates</th>
                    <th>Assigned Role</th>
                    <th>Status State</th>
                    <th>Operational Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($usersList)): ?>
                    <?php foreach ($usersList as $u): ?>
                        <tr style="<?= $u['status'] === 'Inactive' ? 'opacity: 0.55; background: #f2f2f2;' : '' ?>">
                            <td><code><?= $u['id'] ?></code></td>
                            <td><strong><?= htmlspecialchars($u['full_name']) ?></strong><br><small>@<?= htmlspecialchars($u['username']) ?></small></td>
                            <td style="font-size:0.85rem;">📧 <?= htmlspecialchars($u['email']) ?><br>📞 <?= htmlspecialchars($u['contact_no'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($u['role']) ?></td>
                            <td><span class="badge"><?= htmlspecialchars($u['status']) ?></span></td>
                            <td>
                                <?php if ($u['status'] === 'Pending'): ?>
                                    <a href="<?= BASE_URL; ?>../src/database/users/governance.php?action=approve&id=<?= $u['id'] ?>" style="color:#28a745; font-weight:bold; text-decoration:none; margin-right:10px;">✔️ Approve</a>
                                <?php endif; ?>
                                
                                <a href="<?= BASE_URL; ?>users/edit.php?id=<?= $u['id'] ?>" style="color:#0275d8; font-weight:bold; text-decoration:none; margin-right:10px;">✏️ Edit</a>

                                <?php if ($u['status'] === 'Active' && (int)$u['id'] !== (int)$_SESSION['user_id']): ?>
                                    <a href="<?= BASE_URL; ?>../src/database/users/governance.php?action=deactivate&id=<?= $u['id'] ?>" onclick="return confirm('Deactivate user? Historical articles will remain intact.');" style="color:#d9534f; font-weight:bold; text-decoration:none;">🗑️ Deactivate</a>
                                <?php elseif ($u['status'] === 'Inactive'): ?>
                                    <a href="<?= BASE_URL; ?>../src/database/users/governance.php?action=approve&id=<?= $u['id'] ?>" style="color:#28a745; font-weight:bold; text-decoration:none;">🔄 Reactivate</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px; color: #888;">No active user profiles mapped.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</main>

<?php require_once TEMPLATE_PATH . 'footer.php'; ?>