<?php 
// 1. Pull in configurations and path constants
require_once __DIR__ . '/../config/config.php'; 

// 2. Load our simulated raw data feed and the dynamic post manager
require_once SRC_PATH . 'database/mock_posts.php';
require_once SRC_PATH . 'database/postmanager.php';

// Import our custom PostManager namespace
use HamroNews\Database\PostManager;

// Convert the raw associative array into unified objects via our Manager
$postObjects = PostManager::fetchAll($posts);

// 3. Capture the 'slug' from the URL parameter ($_GET)
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

// 4. Search our collection of Post objects for an entry matching the slug
$currentPost = null;
foreach ($postObjects as $post) {
    if ($post->getSlug() === $slug) {
        $currentPost = $post;
        break; // Stop looking once we find it
    }
}

// 5. Load the global header layout template
require_once TEMPLATE_PATH . 'header.php'; 
?>

<main class="content-area">
    <?php if ($currentPost): ?>
        <article class="single-post">
            <small style="color: #dc3545; font-weight: bold; text-transform: uppercase;">
                <?= htmlspecialchars($currentPost->getCategory()); ?>
            </small>
            <h1 style="font-size: 2rem; margin: 10px 0 5px 0;">
                <?= htmlspecialchars($currentPost->getTitle()); ?>
            </h1>
            <p class="meta" style="color: #666; font-size: 0.9rem; margin-bottom: 20px;">
                Published on <?= htmlspecialchars($currentPost->getPublishedAt()); ?> | By <strong><?= htmlspecialchars($currentPost->getAuthor()); ?></strong>
            </p>
            <hr style="border: 0; border-top: 1px solid #ddd; margin-bottom: 20px;">
            
            <div class="post-body" style="font-size: 1.1rem; line-height: 1.6; color: #222;">
                <p><?= htmlspecialchars($currentPost->getContent()); ?></p>
            </div>
            
            <a href="index.php" style="display: inline-block; margin-top: 30px; color: #1a1a2e; text-decoration: none; font-weight: bold;">
                &larr; Back to Home News Feed
            </a>
        </article>
    <?php else: ?>
        <div class="error-box" style="text-align: center; padding: 40px; background: #fff; border-radius: 8px;">
            <h2 style="color: #dc3545;">📰 Article Not Found</h2>
            <p>The news story you are trying to read does not exist or has been archived.</p>
            <a href="index.php" style="color: #1a1a2e; font-weight: bold;">Return to Home Page</a>
        </div>
    <?php endif; ?>
</main>

<?php 
// 6. Load the global footer layout template
require_once TEMPLATE_PATH . 'footer.php'; 
?>