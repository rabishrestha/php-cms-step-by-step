<?php

// Secure Guard Integration Layer
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../config/config.php';
require_once SRC_PATH . 'database/mock_posts.php';
require_once SRC_PATH . 'database/postmanager.php';

use HamroNews\Database\PostManager;

$allPosts = PostManager::fetchAll($posts);

// 1. Capture target ID parameter from URL bar
$targetId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 2. Scan and find the corresponding record
$editablePost = null;
foreach ($allPosts as $post) {
    if ($post->getId() === $targetId) {
        $editablePost = $post;
        break;
    }
}

// Fallback safety filter
if (!$editablePost) {
    die("Error: Target publication data record matching ID row parameters not found.");
}

require_once TEMPLATE_PATH . 'header.php';
?>

<main class="content-area dashboard-grid">
    <aside class="sidebar">
        <h3>CMS Actions</h3>
        <ul>
            <li><a href="dashboard.php">&larr; Return to Dashboard</a></li>
        </ul>
    </aside>

    <section class="main-dashboard-content">
        <h1>Modify Article Document</h1>
        <p style="color: #666; margin-bottom: 20px;">Updating record entry signature tracking reference: <code><?= $editablePost->getId(); ?></code></p>

        <form action="../src/database/update_post_processor.php" method="POST" style="display: flex; flex-direction: column; gap: 15px; max-width: 600px;">
            
            <input type="hidden" name="id" value="<?= $editablePost->getId(); ?>">
            
            <div>
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Article Title</label>
                <input type="text" name="title" required value="<?= htmlspecialchars($editablePost->getTitle()); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>

            <div>
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Category</label>
                <select name="category" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    <?php 
                    $categories = ["National", "Tourism", "Technology", "Sports", "Business", "Culture"];
                    foreach ($categories as $cat) {
                        $selected = ($editablePost->getCategory() === $cat) ? 'selected' : '';
                        echo "<option value=\"$cat\" $selected>$cat</option>";
                    }
                    ?>
                </select>
            </div>

            <div>
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Author Name</label>
                <input type="text" name="author" required value="<?= htmlspecialchars($editablePost->getAuthor()); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>

            <div>
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Short Summary</label>
                <input type="text" name="summary" required value="<?= htmlspecialchars($editablePost->getSummary()); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>

            <div>
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Main Article Content</label>
                <textarea name="content" rows="6" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; resize: vertical;"><?= htmlspecialchars($editablePost->getContent()); ?></textarea>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" style="background: #28a745; color: white; padding: 12px 25px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
                    💾 Save Updates & Overwrite
                </button>
                <a href="dashboard.php" style="background: #6c757d; color: white; padding: 12px 25px; border-radius: 4px; text-decoration: none; font-weight: bold;">Cancel Changes</a>
            </div>
        </form>
    </section>
</main>

<?php
require_once TEMPLATE_PATH . 'footer.php';
?>