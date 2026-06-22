<?php 
// 1. Pull in configurations and path constants
require_once __DIR__ . '/../config/config.php'; 

// 2. Load our simulated database array
require_once SRC_PATH . 'database/mock_posts.php';

// 3. Capture the 'slug' from the URL parameter ($_GET)
// If no slug is provided, default it to an empty string
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

// 4. Search our mock data array for an article with a matching slug
$currentPost = null;
foreach ($posts as $post) {
    if ($post['slug'] === $slug) {
        $currentPost = $post;
        break; // Stop looking once we find it
    }
}

// 5. Load the global header layout template
require_once TEMPLATE_PATH . 'header.php'; 
?>

<main class="content-area">
    <?php if ($currentPost): ?>
        <!-- ARTICLE FOUND: Display the dynamic news data -->
        <article class="single-post">
            <small style="color: #dc3545; font-weight: bold; text-transform: uppercase;">
                <?= htmlspecialchars($currentPost['category']); ?>
            </small>
            <h1 style="font-size: 2rem; margin: 10px 0 5px 0;">
                <?= htmlspecialchars($currentPost['title']); ?>
            </h1>
            <p class="meta" style="color: #666; font-size: 0.9rem; margin-bottom: 20px;">
                Published on <?= htmlspecialchars($currentPost['published_at']); ?> | By <strong><?= htmlspecialchars($currentPost['author']); ?></strong>
            </p>
            <hr style="border: 0; border-top: 1px solid #ddd; margin-bottom: 20px;">
            
            <div class="post-body" style="font-size: 1.1rem; line-height: 1.6; color: #222;">
                <!-- The main news content -->
                <p><?= htmlspecialchars($currentPost['content']); ?></p>
            </div>
            
            <a href="index.php" style="display: inline-block; margin-top: 30px; color: #1a1a2e; text-decoration: none; font-weight: bold;">
                &larr; Back to Home News Feed
            </a>
        </article>
    <?php else: ?>
        <!-- ERROR STATE: User manipulated the URL or the article does not exist -->
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