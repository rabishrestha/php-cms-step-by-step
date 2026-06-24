<?php
require_once __DIR__ . '/../../config/config.php';

if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

// Secure Gate Access Layer: Restrict to logged-in Admins only
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'Admin') {
    die("Access Denied: Administrative Clearance Required.");
}

// Point to our new class-based UserManager file
require_once SRC_PATH . 'database/users/usermanager.php';

use HamroNews\Database\UserManager;

// Pull fully typed collections of User models
$usersList = UserManager::fetchAll();

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
        <p style="color:#666; margin-bottom:20px;">Approve new workspace sign-ups, switch roles, or manage system access tokens.</p>
        
        <?php if (isset($_GET['updated'])): ?>
            <div style="background:#d4edda; color:#155724; padding:10px; border-radius:4px; margin-bottom:15px; font-weight:bold; font-size:0.9rem;">
                ✔️ User profile account state successfully updated!
            </div>
        <?php endif; ?>

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
                        <tr style="<?= $u->getStatus() === 'Inactive' ? 'opacity: 0.55; background: #f8f9fa;' : '' ?>">
                            <td><code><?= $u->getId(); ?></code></td>
                            <td><strong><?= htmlspecialchars($u->getFullName()); ?></strong><br><small style="color:#555;">@<?= htmlspecialchars($u->getUsername()); ?></small></td>
                            <td style="font-size:0.85rem;">📧 <?= htmlspecialchars($u->getEmail()); ?><br>📞 <?= htmlspecialchars($u->getContactNo() ?? 'N/A'); ?></td>
                            <td><span style="background:#f1f3f9; padding:2px 6px; border-radius:4px; font-weight:600; font-size:0.85rem;"><?= htmlspecialchars($u->getRole()); ?></span></td>
                            <td>
                                <?php 
                                    $statusColor = '#6c757d';
                                    if ($u->getStatus() === 'Active') $statusColor = '#28a745';
                                    if ($u->getStatus() === 'Pending') $statusColor = '#ffc107';
                                    if ($u->getStatus() === 'Inactive') $statusColor = '#dc3545';
                                ?>
                                <span class="badge" style="background: <?= $statusColor; ?>; color: <?= $u->getStatus() === 'Pending' ? '#000' : '#fff'; ?>; padding:3px 6px; border-radius:4px; font-size:0.8rem; font-weight:bold;">
                                    <?= htmlspecialchars($u->getStatus()); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($u->getStatus() === 'Pending'): ?>
                                    <a href="<?= BASE_URL; ?>../src/database/users/governance.php?action=approve&id=<?= $u->getId(); ?>" style="color:#28a745; font-weight:bold; text-decoration:none; margin-right:12px;">✔️ Approve</a>
                                <?php endif; ?>
                                
                                <a href="<?= BASE_URL; ?>users/edit.php?id=<?= $u->getId(); ?>" style="color:#0275d8; font-weight:bold; text-decoration:none; margin-right:12px;">✏️ Edit</a>

                                <?php if ($u->getStatus() === 'Active' && $u->getId() !== (int)$_SESSION['user_id']): ?>
                                    <a href="<?= BASE_URL; ?>../src/database/users/governance.php?action=deactivate&id=<?= $u->getId(); ?>" onclick="return confirm('Deactivate user access room? Historical articles will be safely preserved.');" style="color:#d9534f; font-weight:bold; text-decoration:none;">🗑️ Deactivate</a>
                                <?php elseif ($u->getStatus() === 'Inactive'): ?>
                                    <a href="<?= BASE_URL; ?>../src/database/users/governance.php?action=approve&id=<?= $u->getId(); ?>" style="color:#28a745; font-weight:bold; text-decoration:none;">🔄 Reactivate</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px; color: #888;">No system user accounts registered.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</main>

<?php require_once TEMPLATE_PATH . 'footer.php'; ?>