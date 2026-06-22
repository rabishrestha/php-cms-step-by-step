<?php 
// 1. Pull in configurations and path constants
require_once __DIR__ . '/../config/config.php'; 

// 2. Load the dependencies
require_once SRC_PATH . 'database/mock_posts.php'; // Contains $posts = [];
require_once SRC_PATH . 'database/postmanager.php'; 

// CRITICAL FIX: We must import the correct namespace for the manager
use HamroNews\Database\PostManager;

// 3. Fetch unified objects from the text file dataset
$postObjects = PostManager::fetchAll($posts);

// 4. Capture the 'slug' from the URL parameter ($_GET)
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

// 5. Loop through $postObjects collection to find the item
$currentPost = null;
foreach ($postObjects as $post) {
    // Both sides must be strings. trim() in PostManager ensures no hidden line breaks break this match.
    if ($post->getSlug() === $slug) {
        $currentPost = $post;
        break; 
    }
}

// 6. Load the global header layout template
require_once TEMPLATE_PATH . 'header.php'; 
?>

<main class="content-area" style="padding: 20px; max-width: 800px; margin: 0 auto;">
    <?php if ($currentPost): ?>
        <!-- SUCCESS STATE: Dynamic news data loaded via OOP getter methods -->
        <article class="single-post">
            <small style="color: #dc3545; font-weight: bold; text-transform: uppercase;">
                <?= htmlspecialchars($currentPost->getCategory()); ?>
            </small>
            <h1 style="font-size: 2rem; margin: 10px 0 5px 0; color: #1a1a2e;">
                <?= htmlspecialchars($currentPost->getTitle()); ?>
            </h1>
            <p class="meta" style="color: #666; font-size: 0.9rem; margin-bottom: 20px;">
                Published on <?= htmlspecialchars($currentPost->getPublishedAt()); ?> | By <strong><?= htmlspecialchars($currentPost->getAuthor()); ?></strong>
            </p>
            <hr style="border: 0; border-top: 1px solid #ddd; margin-bottom: 20px;">
            
            <div class="post-body" style="font-size: 1.1rem; line-height: 1.6; color: #222;">
                <!-- Calling the getter method instead of old array syntax -->
                <p><?= nl2br(htmlspecialchars($currentPost->getContent())); ?></p>
            </div>
            
            <a href="index.php" style="display: inline-block; margin-top: 30px; color: #1a1a2e; text-decoration: none; font-weight: bold;">
                &larr; Back to Home News Feed
            </a>
        </article>
    <?php else: ?>
        <!-- ERROR FALLBACK: Triggered if slug doesn't match character-for-character -->
        <div class="error-box" style="text-align: center; padding: 40px; background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h2 style="color: #dc3545;">📰 Article Not Found</h2>
            <p>The news story you are trying to read does not exist or has been archived.</p>
            <p style="font-size: 0.85rem; color: #999; font-family: monospace;">Requested Slug: "<?= htmlspecialchars($slug) ?>"</p>
            <a href="index.php" style="color: #1a1a2e; font-weight: bold; display: inline-block; margin-top: 15px;">Return to Home Page</a>
        </div>
    <?php endif; ?>
</main>

<?php 
// 7. Load the global footer layout template
require_once TEMPLATE_PATH . 'footer.php'; 
?>