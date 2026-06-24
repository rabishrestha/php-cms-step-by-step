<?php
// 1. Load config FIRST to guarantee BASE_URL and SRC_PATH are initialized immediately
require_once __DIR__ . '/../../config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Secure Guard Integration Layer
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'login.php');
    exit;
}

// Aligned with the new class-based manager subfolder location
require_once SRC_PATH . 'database/categories/categorymanager.php';

use HamroNews\Database\CategoryManager;

$targetId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$currentCategory = null;

if ($targetId > 0) {
    // OPTIMIZED: Using class-based data mapping instead of raw SQL queries in UI code
    $currentCategory = CategoryManager::fetchById($targetId);
}

require_once TEMPLATE_PATH . 'header.php';
?>

<main class="content-area dashboard-grid">
    <aside class="sidebar">
        <h3>CMS Actions</h3>
        <ul>
            <li><a href="<?= BASE_URL; ?>dashboard.php">📝 Manage Articles</a></li>
            <li><a href="<?= BASE_URL; ?>categories/manage.php" style="font-weight: bold; color: #dc3545;">📁 Manage Categories</a></li>
            <?php if (($_SESSION['role'] ?? '') === 'Admin'): ?>
                <li><a href="<?= BASE_URL; ?>users/manage.php">👥 Manage User Access Control</a></li>
            <?php endif; ?>
            <li><a href="<?= BASE_URL; ?>index.php">🌐 View Live Site</a></li>
        </ul>
    </aside>

    <section class="main-dashboard-content">
        <h1>Modify Category Specification</h1>
        
        <?php if ($currentCategory): ?>
            <div class="form-container" style="background: #f8f9fa; padding: 20px; border-radius: 6px; border: 1px solid #e3e6f0; max-width: 500px;">
                <form action="<?= BASE_URL; ?>../src/database/categories/update.php" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
                    <input type="hidden" name="id" value="<?= $currentCategory->getId(); ?>">

                    <div>
                        <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 0.9rem;">Category Name</label>
                        <input type="text" name="name" required value="<?= htmlspecialchars($currentCategory->getName()); ?>" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <button type="submit" style="background: #0275d8; color: white; padding: 10px 20px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
                            💾 Save Category
                        </button>
                        <a href="<?= BASE_URL; ?>categories/manage.php" style="background: #6c757d; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 0.9rem; text-align: center; line-height: 1.2;">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div style="padding: 30px; background: #f8d7da; color: #721c24; border-radius: 4px; text-align: center; font-weight: bold;">
                ❌ Error: The targeted validation category entry could not be discovered.
            </div>
        <?php endif; ?>
    </section>
</main>

<?php require_once TEMPLATE_PATH . 'footer.php'; ?>