<?php
// 1. Secure Guard Integration Layer
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../config/config.php';
require_once SRC_PATH . 'database/postmanager.php';
require_once SRC_PATH . 'database/db.php';

use HamroNews\Database\PostManager;
use HamroNews\Database\Database;

// 2. Fetch all clean categories through our decoupled controller method
$categoriesList = PostManager::fetchAllCategories();

// 3. Capture the ID from the URL parameter ($_GET) safely
$targetId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$currentPost = null;

if ($targetId > 0) {
    try {
        $db = Database::getConnection();
        // Fetch the specific post raw data directly matching the ID target
        $stmt = $db->prepare("SELECT * FROM posts WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $targetId]);
        $currentPost = $stmt->fetch();
    } catch (\Exception $e) {
        error_log("Failed to fetch post for editing: " . $e->getMessage());
    }
}

require_once TEMPLATE_PATH . 'header.php';
?>

<main class="content-area dashboard-grid">
    <aside class="sidebar">
        <h3>CMS Actions</h3>
        <ul>
            <li><a href="dashboard.php">📝 Manage Articles</a></li>
            <li><a href="index.php">🌐 View Live Site</a></li>
        </ul>
    </aside>

    <section class="main-dashboard-content">
        <h1>Edit News Article</h1>
        
        <?php if ($currentPost): ?>
            <div class="form-container" style="background: #f8f9fa; padding: 20px; border-radius: 6px; border: 1px solid #e3e6f0;">
                <form action="../src/database/update_post_processor.php" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
                    <input type="hidden" name="id" value="<?= $currentPost['id']; ?>">
                    
                    <div style="display: flex; gap: 15px;">
                        <div style="flex: 2;">
                            <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 0.9rem;">Article Title</label>
                            <input type="text" name="title" required value="<?= htmlspecialchars($currentPost['title']); ?>" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        </div>
                        <div style="flex: 1;">
                            <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 0.9rem;">Category Relation</label>
                            <select name="category_id" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                                <?php foreach ($categoriesList as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= (int)$currentPost['category_id'] === (int)$cat['id'] ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div style="display: flex; gap: 15px;">
                        <div style="flex: 1;">
                            <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 0.9rem;">Publication Date & Time</label>
                            <input type="datetime-local" name="published_at" required value="<?= date('Y-m-d\TH:i', strtotime($currentPost['published_at'])) ?>" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        </div>
                        <div style="flex: 2;">
                            <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 0.9rem;">Short Summary</label>
                            <input type="text" name="summary" required value="<?= htmlspecialchars($currentPost['summary']); ?>" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        </div>
                    </div>

                    <div>
                        <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 0.9rem;">Main Article Content</label>
                        <textarea name="content" rows="6" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; resize: vertical;"><?= htmlspecialchars($currentPost['content']); ?></textarea>
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <button type="submit" style="background: #0275d8; color: white; padding: 10px 20px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
                            💾 Save Changes
                        </button>
                        <a href="dashboard.php" style="background: #6c757d; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 0.9rem; display: inline-block; line-height: 1.2;">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div style="padding: 30px; background: #f8d7da; color: #721c24; border-radius: 4px; text-align: center; font-weight: bold;">
                📰 Error: The article you are attempting to edit could not be found.
            </div>
        <?php endif; ?>
    </section>
</main>

<?php require_once TEMPLATE_PATH . 'footer.php'; ?>