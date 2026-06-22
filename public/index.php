<?php 
// 1. Pull in our configuration rules first
require_once __DIR__ . '/../config/config.php'; 

// 2. Load our mock database array using our new path constant
require_once SRC_PATH . 'database/mock_posts.php';

// 3. Load the frontend template header
require_once TEMPLATE_PATH . 'header.php'; 
?>

<main class="content-area">
    <section class="hero">
        <h1>Welcome to Hamro News</h1>
        <p>Your trusted, framework-free source for authentic digital journalism in Nepal.</p>
    </section>

    <h2>Latest News Headlines</h2>
    <section class="articles-grid" style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 20px;">
        
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <article class="card" style="flex: 1; min-width: 300px; max-width: calc(33.33% - 20px);">
                    <small style="color: #dc3545; font-weight: bold; text-transform: uppercase;">
                        <?= htmlspecialchars($post['category']); ?>
                    </small>
                    <h3 style="margin: 5px 0 10px 0; font-size: 1.2rem;">
                        <?= htmlspecialchars($post['title']); ?>
                    </h3>
                    <p style="font-size: 0.9rem; color: #666; line-height: 1.4;">
                        <?= htmlspecialchars($post['summary']); ?>
                    </p>
                    <small style="display: block; margin-top: 10px; color: #999;">
                        By <?= htmlspecialchars($post['author']); ?> | <?= $post['published_at']; ?>
                    </small>
                    <a href="blog-view.php?slug=<?= urlencode($post['slug']); ?>">Read Full Story &rarr;</a>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No news stories found at this moment.</p>
        <?php endif; ?>

    </section>
</main>

<?php 
// 4. Load the frontend template footer
require_once TEMPLATE_PATH . 'footer.php'; 
?>