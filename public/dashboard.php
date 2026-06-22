<?php 
// 1. Pull in configurations and path constants
require_once __DIR__ . '/../config/config.php'; 

// 2. Load the dependencies required to extract files
require_once SRC_PATH . 'database/mock_posts.php';
require_once SRC_PATH . 'database/postmanager.php';

// Import our custom PostManager namespace
use HamroNews\Database\PostManager;

// Convert the raw associative array into unified objects via our Manager
$postObjects = PostManager::fetchAll($posts);

// 3. Load the global frontend template header
require_once TEMPLATE_PATH . 'header.php'; 
?>

<main class="content-area dashboard-grid">
    <aside class="sidebar">
        <h3>CMS Actions</h3>
        <ul>
            <li><a href="dashboard.php" style="font-weight: bold; color: #dc3545;">📝 Manage Content</a></li>
            <li><a href="index.php">🌐 View Live Site</a></li>
        </ul>
    </aside>

    <section class="main-dashboard-content">
        <h1>Admin Control Panel (OOP Overview)</h1>
        <p style="color: #666; margin-bottom: 20px;">
            This restricted dashboard displays an administrative table loop mapped straight out of our object managers.
        </p>
        
        <?php if (!empty($postObjects)): ?>
            <table class="cms-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Post Title</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($postObjects as $post): ?>
                        <tr>
                            <td><code><?= htmlspecialchars($post->getId()); ?></code></td>
                            <td style="font-weight: 600;"><?= htmlspecialchars($post->getTitle()); ?></td>
                            <td><?= htmlspecialchars($post->getCategory()); ?></td>
                            <td><?= htmlspecialchars($post->getAuthor()); ?></td>
                            <td>
                                <span class="badge active" style="background: #28a745; color: white; padding: 2px 6px; border-radius: 4px; font-size: 0.8rem;">
                                    Published
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="padding: 20px; background: #f8f9fa; border-radius: 4px; text-align: center; color: #888;">
                No news articles found in the system data tracking layer.
            </p>
        <?php endif; ?>
    </section>
</main>

<?php 
// 4. Load the global frontend template footer
require_once TEMPLATE_PATH . 'footer.php'; 
?>