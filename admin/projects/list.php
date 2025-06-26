<?php
require_once '../../includes/config.php';
require_once '../../includes/functions.php';
require_once '../../includes/security.php';

// Check if user is logged in
requireAdmin();

$pageTitle = 'All Projects';

// Get all projects
$db = Database::getInstance();
$projects = $db->fetchAll("SELECT * FROM projects ORDER BY created_at DESC");

include '../includes/admin-header.php';
?>

<div class="admin-actions">
    <a href="/admin/projects/add.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Project
    </a>
</div>

<div class="admin-table-container">
    <?php if ($projects): ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Featured</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project): ?>
                    <tr>
                        <td><?php echo $project['title']; ?></td>
                        <td><?php echo $project['category'] ?: 'Uncategorized'; ?></td>
                        <td>
                            <?php if ($project['is_featured']): ?>
                                <span class="status-badge featured">Featured</span>
                            <?php else: ?>
                                <span class="status-badge">No</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo formatDate($project['created_at']); ?></td>
                        <td class="actions">
                            <a href="/admin/projects/edit.php?id=<?php echo $project['id']; ?>" class="btn-icon" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="/portfolio/view.php?slug=<?php echo $project['slug']; ?>" class="btn-icon" title="View" target="_blank">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="/admin/projects/delete.php?id=<?php echo $project['id']; ?>&csrf_token=<?php echo generateCSRFToken(); ?>" class="btn-icon delete-btn" title="Delete" data-confirm="Are you sure you want to delete this project?">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-items">
            <p>No projects found. <a href="/admin/projects/add.php">Add your first project</a>.</p>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/admin-footer.php'; ?>
