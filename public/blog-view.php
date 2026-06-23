<?php 
// 1. Pull in configurations and path constants
require_once __DIR__ . '/../config/config.php'; 
require_once __DIR__ . '/../src/database/posts/postmanager.php'; 

use HamroNews\Database\PostManager;

// 2. Capture the 'slug' from the URL parameter ($_GET) safely
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

// 3. Fetch the unified object directly from the controller via our new decoupled method
$currentPost = PostManager::fetchBySlug($slug);

// 4. Load the global header layout template
require_once TEMPLATE_PATH . 'header.php'; 
?>

<main class="content-area" style="padding: 20px; max-width: 800px; margin: 0 auto;">
    <?php if ($currentPost): ?>
        <article class="single-post">
            <small style="color: #dc3545; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px;">
                📁 <?= htmlspecialchars($currentPost->getCategory()); ?>
            </small>
            <h1 style="font-size: 2.2rem; margin: 10px 0 10px 0; color: #1a1a2e; line-height: 1.3;">
                <?= htmlspecialchars($currentPost->getTitle()); ?>
            </h1>
            <p class="meta" style="color: #666; font-size: 0.9rem; margin-bottom: 20px;">
                Published on <?= date('F j, Y g:i A', strtotime($currentPost->getPublishedAt())); ?> | By <strong>✍️ <?= htmlspecialchars($currentPost->getAuthor()); ?></strong>
            </p>
            <hr style="border: 0; border-top: 1px solid #ddd; margin-bottom: 25px;">
            
            <div class="post-body" style="font-size: 1.15rem; line-height: 1.7; color: #222; text-align: justify;">
                <p><?= nl2br(htmlspecialchars($currentPost->getContent())); ?></p>
            </div>
            
            <a href="index.php" style="display: inline-block; margin-top: 40px; color: #1a1a2e; text-decoration: none; font-weight: bold; border: 1px solid #1a1a2e; padding: 8px 16px; border-radius: 4px; transition: 0.2s;">
                &larr; Back to Home News Feed
            </a>
        </article>
    <?php else: ?>
        <div class="error-box" style="text-align: center; padding: 50px 20px; background: #fff; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); border: 1px solid #e3e6f0;">
            <h2 style="color: #dc3545; margin-bottom: 10px;">📰 Article Not Found</h2>
            <p style="color:#555;">The news story you are trying to read does not exist or has been modified by the editing room.</p>
            <p style="font-size: 0.85rem; color: #999; font-family: monospace; background: #f8f9fa; padding: 8px; display: inline-block; border-radius: 4px; margin-top: 15px;">
                Requested Signature Slug: "<?= htmlspecialchars($slug) ?>"
            </p>
            <br>
            <a href="index.php" style="color: #0275d8; font-weight: bold; display: inline-block; margin-top: 20px; text-decoration: none;">Return to Home Page</a>
        </div>
    <?php endif; ?>
</main>

<?php 
// 5. Load the global footer layout template
require_once TEMPLATE_PATH . 'footer.php'; 
?>