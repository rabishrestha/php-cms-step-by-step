<?php 
include_once __DIR__ . '/../templates/header.php'; 
?>

<main class="content-area dashboard-grid">
    <aside class="sidebar">
        <h3>CMS Actions</h3>
        <ul>
            <li><a href="#">📝 Create New Post</a></li>
            <li><a href="#">📂 Manage Content</a></li>
            <li><a href="#">⚙️ System Settings</a></li>
        </ul>
    </aside>

    <section class="main-dashboard-content">
        <h1>Admin Control Panel</h1>
        <p>This UI area will eventually serve as the secured administrative screen where you can add, update, or remove blog posts directly.</p>
        
        <table class="cms-table">
            <thead>
                <tr>
                    <th>Post Title</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Breaking: Pure PHP is Incredible</td>
                    <td><span class="badge active">Published</span></td>
                    <td><a href="#">Edit</a> | <a href="#" style="color:red;">Delete</a></td>
                </tr>
            </tbody>
        </table>
    </section>
</main>

<?php 
include_once __DIR__ . '/../templates/footer.php'; 
?>