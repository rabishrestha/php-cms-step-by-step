<?php 
require_once __DIR__ . '/../config/config.php'; 

// Load datasets and controllers from separate locations
require_once SRC_PATH . 'database/mock_posts.php'; // Contains $posts = [];
require_once SRC_PATH . 'database/postmanager.php'; 

use HamroNews\Database\PostManager;

// 1. Capture the return value of the manager into a variable!
$postObjects = PostManager::fetchAll(); 

require_once TEMPLATE_PATH . 'header.php'; 
?>

<main class="content-area">
    <section class="hero">
        <h1>Welcome to Hamro News</h1>
        <p>Your trusted source for authentic digital journalism in Nepal.</p>
    </section>

    <h2>Latest News Headlines</h2>
    <section class="articles-grid" style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 20px;">
        
        <?php if (!empty($postObjects)): ?>
            <?php foreach ($postObjects as $post): ?>
                <article class="card" style="flex: 1; min-width: 300px; max-width: calc(33.33% - 20px);">
                    <small style="color: #dc3545; font-weight: bold; text-transform: uppercase;">
                        <?= htmlspecialchars($post->getCategory()); ?>
                    </small>
                    <h3 style="margin: 5px 0 10px 0; font-size: 1.2rem;">
                        <?= htmlspecialchars($post->getTitle()); ?>
                    </h3>
                    <p style="font-size: 0.9rem; color: #666; line-height: 1.4;">
                        <?= htmlspecialchars($post->getSummary()); ?>
                    </p>
                    <small style="display: block; margin-top: 10px; color: #999;">
                        By <?= htmlspecialchars($post->getAuthor()); ?> | <?= htmlspecialchars($post->getPublishedAt()); ?>
                    </small>
                    <a href="blog-view.php?slug=<?= urlencode($post->getSlug()); ?>">Read Full Story &rarr;</a>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No news stories found at this moment.</p>
        <?php endif; ?>

    </section>
</main>

<?php 
require_once TEMPLATE_PATH . 'footer.php'; 
?>