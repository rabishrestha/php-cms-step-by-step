<?php 
// 1. PATH RESOLUTION: Require config FIRST to ensure BASE_URL and SRC_PATH constants are defined immediately
require_once __DIR__ . '/../config/config.php'; 
require_once SRC_PATH . 'database/posts/postmanager.php';

use HamroNews\Database\PostManager;

// 2. Capture request pagination and filtering parameters safely from the URL ($_GET)
$page          = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$selectedCat   = isset($_GET['category_id']) && $_GET['category_id'] !== '' ? (int)$_GET['category_id'] : null;
$searchKeyword = isset($_GET['search']) ? trim($_GET['search']) : null;

$itemsPerPage  = 3; // Controls viewable records block window sizes

// 3. Fetch datasets purely through decoupled class-based Controller Methods
$postObjects    = PostManager::fetchAdvanced($page, $itemsPerPage, $selectedCat, $searchKeyword);
$totalRecords   = PostManager::getTotalCount($selectedCat, $searchKeyword);
$categoriesList = PostManager::fetchAllCategories(); // Returns arrays of Category models

$totalPages     = ceil($totalRecords / $itemsPerPage);

require_once TEMPLATE_PATH . 'header.php'; 
?>

<nav class="search-filter-bar" style="background:#fff; padding:20px; border-radius:6px; box-shadow:0 2px 4px rgba(0,0,0,0.05); margin-bottom:30px;">
    <form action="index.php" method="GET" style="display:flex; gap:15px; flex-wrap:wrap; align-items:center;">
        
        <div style="flex:2; min-width:200px;">
            <input type="text" name="search" placeholder="Search keywords across headlines..." value="<?= htmlspecialchars($searchKeyword ?? '') ?>" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px; box-sizing: border-box;">
        </div>
        
        <div style="flex:1; min-width:150px;">
            <select name="category_id" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px; box-sizing: border-box;">
                <option value="">-- All Categories --</option>
                <?php foreach ($categoriesList as $cat): ?>
                    <option value="<?= $cat->getId(); ?>" <?= $selectedCat === $cat->getId() ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($cat->getName()); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <button type="submit" style="background:#1a1a2e; color:white; padding:10px 20px; border:none; border-radius:4px; font-weight:bold; cursor:pointer; height: 38px;">
            🔍 Apply Filter
        </button>
        
        <?php if ($searchKeyword || $selectedCat): ?>
            <a href="index.php" style="color:#dc3545; text-decoration:none; font-size:0.9rem; font-weight:bold; padding-left: 5px;">❌ Reset</a>
        <?php endif; ?>
    </form>
</nav>

<main class="content-area">
    <h2 style="color: #1a1a2e; margin-bottom: 20px; font-size: 1.5rem;">
        📰 Latest News Headlines (<?= $totalRecords ?> Results found)
    </h2>
    
    <section class="articles-grid" style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 20px;">
        <?php if (!empty($postObjects)): ?>
            <?php foreach ($postObjects as $post): ?>
                <article class="card" style="flex: 1; min-width: 300px; max-width: calc(33.33% - 20px); background: #fff; padding: 20px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); display: flex; flex-direction: column; justify-content: space-between; border: 1px solid #eee;">
                    <div>
                        <small style="color: #dc3545; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; font-size: 0.8rem;">
                            📁 <?= htmlspecialchars($post->getCategory()); ?>
                        </small>
                        <h3 style="margin: 8px 0 10px 0; font-size: 1.25rem; color: #1a1a2e; line-height: 1.3;">
                            <?= htmlspecialchars($post->getTitle()); ?>
                        </h3>
                        <p style="font-size: 0.9rem; color: #555; line-height: 1.5; margin-bottom: 15px; text-align: justify;">
                            <?= htmlspecialchars($post->getSummary()); ?>
                        </p>
                    </div>
                    <div>
                        <small style="display: block; margin-bottom: 15px; color: #888; border-top: 1px solid #f9f9f9; padding-top: 10px; font-size: 0.8rem;">
                            By <strong>✍️ <?= htmlspecialchars($post->getAuthor()); ?></strong> | 🗓️ <?= date('M d, Y', strtotime($post->getPublishedAt())); ?>
                        </small>
                        <a href="blog-view.php?slug=<?= urlencode($post->getSlug()); ?>" style="color: #1a1a2e; text-decoration: none; font-weight: bold; font-size: 0.95rem; display: inline-block;">
                            Read Full Story &rarr;
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="padding: 60px 20px; text-align: center; width: 100%; color: #888; background: #fff; border-radius: 6px; border: 1px solid #e3e6f0;">
                <h3>No matching news items discovered matching those parameters.</h3>
                <p style="font-size: 0.9rem; margin-top: 5px;">Try refining your target words or choosing another category tracking group.</p>
                <a href="index.php" style="color: #0275d8; font-weight: bold; text-decoration: none; display: inline-block; margin-top: 15px;">Clear Filters</a>
            </div>
        <?php endif; ?>
    </section>

    <?php if ($totalPages > 1): ?>
        <nav class="pagination-container" style="display:flex; justify-content:center; gap:8px; margin-top:50px; padding-bottom: 20px;">
            <?php for ($i = 1; $i <= $totalPages; $i++): 
                // Preserves search inputs and selected categories while hopping pages
                $queryString = http_build_query(array_merge($_GET, ['page' => $i]));
                $activeStyles = ($i === $page) ? 'background:#1a1a2e; color:white; border-color:#1a1a2e;' : 'background:#fff; color:#1a1a2e; border-color:#ddd;';
            ?>
                <a href="index.php?<?= $queryString ?>" style="padding:8px 16px; border:1px solid; border-radius:4px; text-decoration:none; font-weight:bold; transition: 0.2s; <?= $activeStyles ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </nav>
    <?php endif; ?>
</main>

<?php require_once TEMPLATE_PATH . 'footer.php'; ?>