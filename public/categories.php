<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../config/config.php';
require_once SRC_PATH . 'database/postmanager.php';

use HamroNews\Database\PostManager;

// Pull all categories via our decoupled manager method
$categoriesList = PostManager::fetchAllCategories();

require_once TEMPLATE_PATH . 'header.php';
?>

<main class="content-area dashboard-grid">
    <aside class="sidebar">
        <h3>CMS Actions</h3>
        <ul>
            <li><a href="dashboard.php">📝 Manage Articles</a></li>
            <li><a href="categories.php" style="font-weight: bold; color: #dc3545;">📁 Manage Categories</a></li>
            <li><a href="index.php">🌐 View Live Site</a></li>
        </ul>
    </aside>

    <section class="main-dashboard-content">
        <h1>Category Desk Management</h1>
        <p style="color:#666; margin-bottom: 25px;">Create new classification groups or drop unused tracking fields.</p>

        <?php if (isset($_GET['success'])): ?>
            <div style="background:#d4edda; color:#155724; padding:10px; border-radius:4px; margin-bottom:15px; font-weight:bold; font-size:0.9rem;">
                ✅ Category successfully added!
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['deleted'])): ?>
            <div style="background:#d4edda; color:#155724; padding:10px; border-radius:4px; margin-bottom:15px; font-weight:bold; font-size:0.9rem;">
                🗑️ Category successfully removed.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error']) && $_GET['error'] === 'restricted'): ?>
            <div style="background:#f8d7da; color:#721c24; padding:10px; border-radius:4px; margin-bottom:15px; font-weight:bold; font-size:0.9rem; border-left: 4px solid #dc3545;">
                ❌ Restrict Trigger: Cannot delete this category while articles are still linked to it!
            </div>
        <?php endif; ?>

        <div class="form-container" style="background: #f8f9fa; padding: 20px; border-radius: 6px; margin-bottom: 40px; border: 1px solid #e3e6f0; max-width: 500px;">
            <h2 style="font-size: 1.1rem; margin-bottom: 12px; color: #1a1a2e;">Create New Category</h2>
            <form action="../src/database/create_category.php" method="POST" style="display: flex; gap: 10px;">
                <input type="text" name="name" required placeholder="Category name (e.g., Entertainment)..." style="flex: 2; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <button type="submit" style="flex: 1; background: #1a1a2e; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
                    ➕ Add Category
                </button>
            </form>
        </div>

        <h2>Active System Categories</h2>
        <table class="cms-table" style="max-width: 600px;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>URL Slug</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categoriesList as $cat): ?>
                    <tr>
                        <td><code><?= $cat['id'] ?></code></td>
                        <td style="font-weight: 600;"><?= htmlspecialchars($cat['name']) ?></td>
                        <td><code><?= htmlspecialchars($cat['slug']) ?></code></td>
                        <td>
                            <a href="categories-edit.php?id=<?= $cat['id'] ?>" 
                            style="color: #0275d8; font-weight: bold; text-decoration: none; margin-right: 15px; font-size: 0.9rem;">✏️ Edit</a>
                            
                            <a href="../src/database/delete_category.php?id=<?= $cat['id'] ?>" 
                            onclick="return confirm('Are you sure you want to delete this category?');" 
                            style="color: #d9534f; font-weight: bold; text-decoration: none; font-size: 0.9rem;">🗑️ Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

<?php require_once TEMPLATE_PATH . 'footer.php'; ?>