<?php 
// Pull in the shared header
include_once __DIR__ . '/../templates/header.php'; 
?>

<main class="content-area">
    <section class="hero">
        <h1>Welcome to the News & Blog Portal</h1>
        <p>This is the framework-free public landing page where users read latest news stories.</p>
    </section>

    <section class="articles-grid">
        <h2>Latest News Articles (Static Placeholder)</h2>
        <article class="card">
            <h3>Breaking: Pure PHP is Incredible</h3>
            <p>Building a CMS layout without a framework exposes how files assemble natively...</p>
            <a href="blog-view.php">Read Full Article &rarr;</a>
        </article>
    </section>
</main>

<?php 
// Pull in the shared footer
include_once __DIR__ . '/../templates/footer.php'; 
?>