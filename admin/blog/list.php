<?php
require_once '../../includes/config.php';
require_once '../../includes/functions.php';
require_once '../../includes/security.php';

// Check if user is logged in
requireAdmin();

$pageTitle = 'All Blog Posts';

// Get all blog posts
$db = Database::getInstance();
$posts = $db->fetchAll("SELECT p.*, u.full_name as author_name 
                        FROM blog_posts p 
                        LEFT JOIN users u ON p.author_id = u.id 
                        ORDER BY p.published_at DESC");

include '../includes/admin-header.php';
?>

<div class="admin-actions">
    <a href="/admin/blog/add.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Post
    </a>
</div>

<div class="admin-table-container">
    <?php if ($posts): ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td><?php echo $post['title']; ?></td>
                        <td><?php echo $post['author_name']; ?></td>
                        <td>
                            <span class="status-badge <?php echo $post['status']; ?>">
                                <?php echo ucfirst($post['status']); ?>
                            </span>
                        </td>
                        <td><?php echo formatDate($post['published_at']); ?></td>
                        <td class="actions">
                            <a href="/admin/blog/edit.php?id=<?php echo $post['id']; ?>" class="btn-icon" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <?php if ($post['status'] == 'published'): ?>
                                <a href="/blog/post.php?slug=<?php echo $post['slug']; ?>" class="btn-icon" title="View" target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                            <?php endif; ?>
                            <a href="/admin/blog/delete.php?id=<?php echo $post['id']; ?>&csrf_token=<?php echo generateCSRFToken(); ?>" class="btn-icon delete-btn" title="Delete" data-confirm="Are you sure you want to delete this blog post?">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-items">
            <p>No blog posts found. <a href="/admin/blog/add.php">Write your first post</a>.</p>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/admin-footer.php'; ?>
