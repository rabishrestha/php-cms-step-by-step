<?php 
require_once __DIR__ . '/../config/config.php'; 
require_once SRC_PATH . 'database/posts/postmanager.php';

use HamroNews\Database\PostManager;

// 1. Capture request parameters safely
$page          = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$selectedCat   = isset($_GET['category_id']) && $_GET['category_id'] !== '' ? (int)$_GET['category_id'] : null;
$searchKeyword = isset($_GET['search']) ? trim($_GET['search']) : null;

$itemsPerPage  = 3;

// 2. Fetch processed datasets purely through Controller Methods
$postObjects    = PostManager::fetchAdvanced($page, $itemsPerPage, $selectedCat, $searchKeyword);
$totalRecords   = PostManager::getTotalCount($selectedCat, $searchKeyword);
$categoriesList = PostManager::fetchAllCategories();

$totalPages     = ceil($totalRecords / $itemsPerPage);

require_once TEMPLATE_PATH . 'header.php'; 
?>

<nav class="search-filter-bar" style="background:#fff; padding:20px; border-radius:6px; box-shadow:0 2px 4px rgba(0,0,0,0.05); margin-bottom:30px;">
    <form action="index.php" method="GET" style="display:flex; gap:15px; flex-wrap:wrap; align-items:center;">
        <div style="flex:2; min-width:200px;">
            <input type="text" name="search" placeholder="Search keywords across headlines..." value="<?= htmlspecialchars($searchKeyword ?? '') ?>" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">
        </div>
        <div style="flex:1; min-width:150px;">
            <select name="category_id" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">
                <option value="">-- All Categories --</option>
                <?php foreach ($categoriesList as $cat): ?>
                    <option value="<?= $cat['id']; ?>" <?= $selectedCat === (int)$cat['id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($cat['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" style="background:#1a1a2e; color:white; padding:10px 20px; border:none; border-radius:4px; font-weight:bold; cursor:pointer;">
            🔍 Apply Filter
        </button>
        <?php if ($searchKeyword || $selectedCat): ?>
            <a href="index.php" style="color:#dc3545; text-decoration:none; font-size:0.9rem; font-weight:bold;">❌ Reset</a>
        <?php endif; ?>
    </form>
</nav>

<main class="content-area">
    <h2>Latest News Headlines (<?= $totalRecords ?> Results)</h2>
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
                            By <?= htmlspecialchars($post->getAuthor()); ?> | <?= date('F j, Y', strtotime($post->getPublishedAt())); ?>
                        </small>
                        <a href="blog-view.php?slug=<?= urlencode($post->getSlug()); ?>" style="color: #1a1a2e; text-decoration: none; font-weight: bold;">Read Full Story &rarr;</a>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="padding:40px; text-align:center; width:100%; color:#888;">No matching news items discovered matching those parameters.</p>
        <?php endif; ?>
    </section>

    <?php if ($totalPages > 1): ?>
        <nav class="pagination-container" style="display:flex; justify-content:center; gap:8px; margin-top:40px;">
            <?php for ($i = 1; $i <= $totalPages; $i++): 
                $queryString = http_build_query(array_merge($_GET, ['page' => $i]));
                $activeStyles = ($i === $page) ? 'background:#1a1a2e; color:white;' : 'background:#fff; color:#1a1a2e;';
            ?>
                <a href="index.php?<?= $queryString ?>" style="padding:8px 14px; border:1px solid #ddd; border-radius:4px; text-decoration:none; font-weight:bold; <?= $activeStyles ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </nav>
    <?php endif; ?>
</main>

<?php require_once TEMPLATE_PATH . 'footer.php'; ?>