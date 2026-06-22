<?php 
// 1. Pull in configurations and path constants
require_once __DIR__ . '/../config/config.php'; 

// 2. Load our simulated raw data feed and the dynamic post manager
require_once SRC_PATH . 'database/mock_posts.php';
require_once SRC_PATH . 'database/postmanager.php';

// Import our custom PostManager namespace
use HamroNews\Database\PostManager;

// Convert your raw data into a collection array of concrete Post objects
$postObjects = PostManager::fetchAll($posts);

// 3. Load the global header layout template
require_once TEMPLATE_PATH . 'header.php'; 
?>

<main class="content-area">
    <section class="hero">
        <h1>Welcome to Hamro News (OOP Engine)</h1>
        <p>This version of the platform runs on fully encapsulated, Object-Oriented PHP structures.</p>
    </section>

    <h2>Latest News Headlines</h2>
    <section class="articles-grid" style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 20px;">
        
        <?php if (!empty($postObjects)): ?>
            <?php foreach ($postObjects as $post): ?>
                <article class="card" style="flex: 1; min-width: 300px; max-width: calc(33.33% - 20px); background: #fff; padding: 20px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <small style="color: #dc3545; font-weight: bold; text-transform: uppercase;">
                            <?= htmlspecialchars($post->getCategory()); ?>
                        </small>
                        <h3 style="margin: 5px 0 10px 0; font-size: 1.2rem; color: #1a1a2e;">
                            <?= htmlspecialchars($post->getTitle()); ?>
                        </h3>
                        <p style="font-size: 0.9rem; color: #666; line-height: 1.4; margin-bottom: 15px;">
                            <?= htmlspecialchars($post->getSummary()); ?>
                        </p>
                    </div>
                    <div>
                        <small style="display: block; margin-bottom: 10px; color: #999;">
                            By <?= htmlspecialchars($post->getAuthor()); ?> | <?= htmlspecialchars($post->getPublishedAt()); ?>
                        </small>
                        <a href="blog-view.php?slug=<?= urlencode($post->getSlug()); ?>" style="color: #1a1a2e; text-decoration: none; font-weight: bold;">Read Full Story &rarr;</a>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No news stories found at this moment.</p>
        <?php endif; ?>

    </section>
</main>

<?php 
// 4. Load the global footer layout template
require_once TEMPLATE_PATH . 'footer.php'; 
?>