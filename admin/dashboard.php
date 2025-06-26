<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/security.php';

// Check if user is logged in
requireAdmin();

$pageTitle = 'Admin Dashboard';

// Get counts for dashboard
$db = Database::getInstance();

$projectCount = $db->fetch("SELECT COUNT(*) as count FROM projects")['count'] ?? 0;
$blogCount = $db->fetch("SELECT COUNT(*) as count FROM blog_posts")['count'] ?? 0;
$featuredCount = $db->fetch("SELECT COUNT(*) as count FROM projects WHERE is_featured = 1")['count'] ?? 0;
$draftCount = $db->fetch("SELECT COUNT(*) as count FROM blog_posts WHERE status = 'draft'")['count'] ?? 0;

// Get recent projects
$recentProjects = $db->fetchAll("SELECT * FROM projects ORDER BY created_at DESC LIMIT 5");

// Get recent blog posts
$recentPosts = $db->fetchAll("SELECT * FROM blog_posts ORDER BY published_at DESC LIMIT 5");

include 'includes/admin-header.php';
?>

<div class="dashboard-welcome">
    <h2>Welcome, <?php echo escapeHTML($_SESSION['first_name'] ?? $_SESSION['username'] ?? 'Admin'); ?>!</h2>
    <p>This is your admin dashboard. Manage your portfolio and blog from here.</p>
</div>

<div class="dashboard-stats">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-project-diagram"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $projectCount; ?></h3>
            <p>Total Projects</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-blog"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $blogCount; ?></h3>
            <p>Blog Posts</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-star"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $featuredCount; ?></h3>
            <p>Featured Projects</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-file-alt"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $draftCount; ?></h3>
            <p>Draft Posts</p>
        </div>
    </div>
</div>

<div class="dashboard-recent">
    <div class="recent-section">
        <div class="section-header">
            <h3>Recent Projects</h3>
            <a href="<?php echo admin_url('projects/list.php'); ?>" class="btn btn-small">View All</a>
        </div>
        
        <div class="recent-list">
            <?php if ($recentProjects): ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentProjects as $project): ?>
                            <tr>
                                <td><?php echo escapeHTML($project['title']); ?></td>
                                <td><?php echo escapeHTML($project['category']); ?></td>
                                <td><?php echo formatDate($project['created_at']); ?></td>
                                <td>
                                    <a href="<?php echo admin_url('projects/edit.php?id=' . $project['id']); ?>" class="btn-icon" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?php echo base_url('portfolio/view.php?slug=' . $project['slug']); ?>" class="btn-icon" title="View" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No projects yet. <a href="<?php echo admin_url('projects/add.php'); ?>">Add your first project</a>.</p>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="recent-section">
        <div class="section-header">
            <h3>Recent Blog Posts</h3>
            <a href="<?php echo admin_url('blog/list.php'); ?>" class="btn btn-small">View All</a>
        </div>
        
        <div class="recent-list">
            <?php if ($recentPosts): ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentPosts as $post): ?>
                            <tr>
                                <td><?php echo escapeHTML($post['title']); ?></td>
                                <td>
                                    <span class="status-badge <?php echo $post['status']; ?>">
                                        <?php echo ucfirst($post['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo formatDate($post['published_at']); ?></td>
                                <td>
                                    <a href="<?php echo admin_url('blog/edit.php?id=' . $post['id']); ?>" class="btn-icon" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($post['status'] == 'published'): ?>
                                        <a href="<?php echo base_url('blog/post.php?slug=' . $post['slug']); ?>" class="btn-icon" title="View" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No blog posts yet. <a href="<?php echo admin_url('blog/add.php'); ?>">Write your first post</a>.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="dashboard-actions">
    <div class="action-card">
        <div class="action-icon">
            <i class="fas fa-plus-circle"></i>
        </div>
        <h3>Add New Project</h3>
        <p>Showcase your latest work in your portfolio</p>
        <a href="<?php echo admin_url('projects/add.php'); ?>" class="btn btn-primary">Add Project</a>
    </div>
    
    <div class="action-card">
        <div class="action-icon">
            <i class="fas fa-pen-fancy"></i>
        </div>
        <h3>Write New Blog Post</h3>
        <p>Share your insights and experiences</p>
        <a href="<?php echo admin_url('blog/add.php'); ?>" class="btn btn-primary">Write Post</a>
    </div>
    
    <div class="action-card">
        <div class="action-icon">
            <i class="fas fa-user-edit"></i>
        </div>
        <h3>Update Profile</h3>
        <p>Keep your personal information up to date</p>
        <a href="<?php echo admin_url('settings/profile.php'); ?>" class="btn btn-primary">Edit Profile</a>
    </div>
</div>

<?php include 'includes/admin-footer.php'; ?>
