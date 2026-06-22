<?php

// Secure Guard Integration Layer
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../config/config.php';
require_once SRC_PATH . 'database/mock_posts.php'; // Passes empty array $posts = [];
require_once SRC_PATH . 'database/postmanager.php';

use HamroNews\Database\PostManager;

// Load all existing post objects out of the flat text data store
$allPosts = PostManager::fetchAll();

require_once TEMPLATE_PATH . 'header.php';
?>

<main class="content-area dashboard-grid">
    <aside class="sidebar">
        <h3>CMS Actions</h3>
        <ul>
            <li><a href="dashboard.php" style="font-weight: bold; color: #dc3545;">📝 Manage Articles</a></li>
            <li><a href="index.php">🌐 View Live Site</a></li>
        </ul>
    </aside>

    <section class="main-dashboard-content">
        <h1>Publisher Administration Terminal</h1>
        <p style="color: #666; margin-bottom: 25px;">Create new news entries or modify live published files.</p>

        <div class="form-container" style="background: #f8f9fa; padding: 20px; border-radius: 6px; margin-bottom: 40px; border: 1px solid #e3e6f0;">
            <h2 style="font-size: 1.2rem; margin-bottom: 15px; color: #1a1a2e;">Add New Article</h2>
            <form action="../src/database/create_post.php" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
                
                <div style="display: flex; gap: 15px;">
                    <div style="flex: 2;">
                        <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 0.9rem;">Article Title</label>
                        <input type="text" name="title" required placeholder="Headline goes here..." style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div style="flex: 1;">
                        <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 0.9rem;">Category</label>
                        <select name="category" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                            <option value="National">National</option>
                            <option value="Tourism">Tourism</option>
                            <option value="Technology">Technology</option>
                            <option value="Sports">Sports</option>
                            <option value="Business">Business</option>
                            <option value="Culture">Culture</option>
                        </select>
                    </div>
                </div>

                <div style="display: flex; gap: 15px;">
                    <div style="flex: 1;">
                        <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 0.9rem;">Author Name</label>
                        <input type="text" name="author" required placeholder="Writer profile name" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div style="flex: 2;">
                        <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 0.9rem;">Short Summary</label>
                        <input type="text" name="summary" required placeholder="A single sentence snapshot" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                </div>

                <div>
                    <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 0.9rem;">Main Article Content</label>
                    <textarea name="content" rows="4" required placeholder="Write the full body text here..." style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; resize: vertical;"></textarea>
                </div>

                <button type="submit" style="background: #1a1a2e; color: white; padding: 10px 20px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; align-self: flex-start;">
                    🚀 Publish News Article
                </button>
            </form>
        </div>

        <h2 style="font-size: 1.3rem; margin-bottom: 10px; color: #1a1a2e;">Live News Archive Records</h2>
        <?php if (!empty($allPosts)): ?>
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
                    <?php foreach ($allPosts as $post): ?>
                        <tr>
                            <td><code><?= htmlspecialchars($post->getId()); ?></code></td>
                            <td style="font-weight: 600;"><?= htmlspecialchars($post->getTitle()); ?></td>
                            <td><span class="badge" style="background: #1a1a2e; color: white; padding: 3px 8px; border-radius: 4px; font-size: 0.8rem;"><?= htmlspecialchars($post->getCategory()); ?></span></td>
                            <td><?= htmlspecialchars($post->getAuthor()); ?></td>
                            <td style="font-size: 0.85rem; color: #666;"><?= htmlspecialchars($post->getPublishedAt()); ?></td>
                            <td>
                                <a href="edit-post.php?id=<?= urlencode($post->getId()); ?>" style="color: #0275d8; text-decoration: none; font-weight: bold; font-size: 0.9rem; margin-right: 15px;">✏️ Edit</a>
                                <a href="../src/database/delete_post_processor.php?id=<?= urlencode($post->getId()); ?>" 
                                   onclick="return confirm('Are you sure you want to permanently delete this article from Hamro News?');" 
                                   style="color: #d9534f; text-decoration: none; font-weight: bold; font-size: 0.9rem;">🗑️ Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="padding: 20px; background: #f8f9fa; border-radius: 4px; text-align: center; color: #888;">No database text row instances logged yet.</p>
        <?php endif; ?>
    </section>
</main>

<?php
require_once TEMPLATE_PATH . 'footer.php';
?>