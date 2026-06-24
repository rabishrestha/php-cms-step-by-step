<?php
// 1. PATH RESOLUTION: Load config FIRST so BASE_URL and SRC_PATH constants are active immediately
require_once __DIR__ . '/../config/config.php';

// Safe Session Status validation guard
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Secure Gate Authentication Guard
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'login.php');
    exit;
}

// Points cleanly to the feature-grouped subfolder path inside src/database/
require_once SRC_PATH . 'database/posts/postmanager.php';

use HamroNews\Database\PostManager;

$userRole = $_SESSION['role'] ?? 'Reporter';
$userId   = (int)$_SESSION['user_id'];

// Fetch presentation datasets purely via clean decoupled controller methods
$categoriesList = PostManager::fetchAllCategories(); // Now returns an array of Category objects
$allPosts       = PostManager::fetchDashboardByRole($userRole, $userId);

require_once TEMPLATE_PATH . 'header.php';
?>

<main class="content-area dashboard-grid">
    <aside class="sidebar">
        <h3>CMS Actions</h3>
        <ul>
            <li><a href="<?= BASE_URL; ?>dashboard.php" style="font-weight: bold; color: #dc3545;">📝 Manage Articles</a></li>
            <?php if ($userRole === 'Admin'): ?>
                <li><a href="<?= BASE_URL; ?>categories/manage.php">📁 Manage Categories</a></li>
                <li><a href="<?= BASE_URL; ?>users/manage.php">👥 Manage User Access Control</a></li>
            <?php endif; ?>
            <li><a href="<?= BASE_URL; ?>index.php">🌐 View Live Site</a></li>
        </ul>
    </aside>

    <section class="main-dashboard-content">
        <h1>Advanced Administration Desk</h1>
        <p style="color:#666;">Signed in as: <strong><?= htmlspecialchars($_SESSION['full_name']); ?></strong> (Role: <code><?= $userRole ?></code>)</p>

        <?php if (isset($_GET['success'])): ?>
            <div style="background:#d4edda; color:#155724; padding:10px; border-radius:4px; margin-bottom:15px; font-weight:bold; font-size:0.9rem;">
                🚀 News article successfully published to the public stream!
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['updated'])): ?>
            <div style="background:#d4edda; color:#155724; padding:10px; border-radius:4px; margin-bottom:15px; font-weight:bold; font-size:0.9rem;">
                📝 Article modifications successfully recorded!
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['deleted'])): ?>
            <div style="background:#d4edda; color:#155724; padding:10px; border-radius:4px; margin-bottom:15px; font-weight:bold; font-size:0.9rem;">
                🗑️ Article successfully removed from archive tables.
            </div>
        <?php endif; ?>

        <div class="form-container" style="background: #f8f9fa; padding: 20px; border-radius: 6px; margin-bottom: 40px; border: 1px solid #e3e6f0;">
            <h2 style="font-size: 1.2rem; margin-bottom: 15px; color: #1a1a2e;">Add New Article</h2>
            <form action="<?= BASE_URL; ?>../src/database/posts/create.php" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
                
                <div style="display: flex; gap: 15px;">
                    <div style="flex: 2;">
                        <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 0.9rem;">Article Title</label>
                        <input type="text" name="title" required placeholder="Headline..." style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div style="flex: 1;">
                        <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 0.9rem;">Category Relation</label>
                        <select name="category_id" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                            <?php foreach ($categoriesList as $cat): ?>
                                <option value="<?= $cat->getId(); ?>"><?= htmlspecialchars($cat->getName()); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div style="display: flex; gap: 15px;">
                    <div style="flex: 1;">
                        <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 0.9rem;">Publication Date & Time</label>
                        <input type="datetime-local" name="published_at" required value="<?= date('Y-m-d\TH:i') ?>" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div style="flex: 2;">
                        <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 0.9rem;">Short Summary</label>
                        <input type="text" name="summary" required placeholder="Snapshot..." style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                </div>

                <div>
                    <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 0.9rem;">Main Article Content</label>
                    <textarea name="content" rows="4" required placeholder="Full story..." style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; resize: vertical;"></textarea>
                </div>

                <button type="submit" style="background: #1a1a2e; color: white; padding: 10px 20px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; align-self: flex-start;">
                    🚀 Publish News Article
                </button>
            </form>
        </div>

        <h2 style="font-size: 1.3rem; margin-bottom: 10px; color: #1a1a2e;">Live News Archive Records</h2>
        <table class="cms-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Headline Title</th>
                    <th>Category</th>
                    <th>Author</th>
                    <th>Date Published</th>
                    <th>Action Options</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($allPosts)): ?>
                    <?php foreach ($allPosts as $post): ?>
                        <tr>
                            <td><code><?= $post->getId() ?></code></td>
                            <td style="font-weight:600;"><?= htmlspecialchars($post->getTitle()) ?></td>
                            <td><span class="badge" style="background:#1a1a2e; color:white; padding:3px 6px; border-radius:4px; font-size:0.8rem;"><?= htmlspecialchars($post->getCategory()) ?></span></td>
                            <td><?= htmlspecialchars($post->getAuthor()) ?></td>
                            <td style="font-size:0.85rem; color:#666;"><?= date('M d, Y H:i', strtotime($post->getPublishedAt())) ?></td>
                            <td>
                                <a href="<?= BASE_URL; ?>posts/edit.php?id=<?= $post->getId() ?>" style="color:#0275d8; font-weight:bold; text-decoration:none; margin-right:15px;">✏️ Edit</a>
                                <a href="<?= BASE_URL; ?>../src/database/posts/delete.php?id=<?= $post->getId() ?>" onclick="return confirm('Confirm Deletion?');" style="color:#d9534f; font-weight:bold; text-decoration:none;">🗑️ Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center; padding:20px; color:#888;">No active news records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</main>

<?php require_once TEMPLATE_PATH . 'footer.php'; ?>