<?php
require_once '../../includes/config.php';
require_once '../../includes/functions.php';
require_once '../../includes/security.php';

// Check if user is logged in
requireAdmin();

// Get project ID from URL
$projectId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch project details
$db = Database::getInstance();
$project = $db->fetch("SELECT * FROM projects WHERE id = ?", [$projectId]);

if (!$project) {
    $_SESSION['error'] = 'Project not found';
    redirect('/admin/projects/list.php');
}

$pageTitle = 'Edit Project: ' . htmlspecialchars($project['title']);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check CSRF token
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $_SESSION['error'] = 'Invalid form submission';
        redirect('/admin/projects/edit.php?id=' . $projectId);
    }
    
    // Get form data
    $title = clean($_POST['title'] ?? '');
    $description = clean($_POST['description'] ?? '');
    $content = $_POST['content'] ?? '';
    $category = clean($_POST['category'] ?? '');
    $tags = clean($_POST['tags'] ?? '');
    $github_url = clean($_POST['github_url'] ?? '');
    $video_url = clean($_POST['video_url'] ?? '');
    $dashboard_url = clean($_POST['dashboard_url'] ?? '');
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    // Validate required fields
    if (empty($title) || empty($description)) {
        $_SESSION['error'] = 'Title and description are required';
        redirect('/admin/projects/edit.php?id=' . $projectId);
    }
    
    // Generate slug
    $slug = generateSlug($title);
    
    // Check if slug already exists for another project
    $existingSlug = $db->fetch("SELECT id FROM projects WHERE slug = :slug AND id != :id", [
        ':slug' => $slug,
        ':id' => $projectId
    ]);
    
    if ($existingSlug) {
        // Append a unique identifier to the slug
        $slug = $slug . '-' . time();
    }
    
    // Handle image upload
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
        try {
            $upload = uploadFile($_FILES['featured_image'], 'projects/');
            
            if ($upload['success']) {
                // Delete old image if it exists and is different
                if ($project['featured_image'] && $upload['filename'] !== $project['featured_image']) {
                    $oldImagePath = __DIR__ . '/../../../assets/uploads/projects/' . $project['featured_image'];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                $featured_image = $upload['filename'];
            } else {
                $_SESSION['error'] = $upload['message'];
                redirect('/admin/projects/edit.php?id=' . $projectId);
            }
        } catch (Exception $e) {
            error_log("Error during image upload: " . $e->getMessage());
            $_SESSION['error'] = "Failed to upload image. Please try again.";
            redirect('/admin/projects/edit.php?id=' . $projectId);
        }
    } else {
        $featured_image = $project['featured_image'];
    }
    
    // Update project in database
    $sql = "UPDATE projects SET 
        title = :title, 
        slug = :slug, 
        description = :description, 
        content = :content, 
        featured_image = :featured_image, 
        video_url = :video_url, 
        dashboard_url = :dashboard_url, 
        github_url = :github_url, 
        tags = :tags, 
        category = :category, 
        is_featured = :is_featured,
        updated_at = NOW()
        WHERE id = :id";
    
    $params = [
        ':title' => $title,
        ':slug' => $slug,
        ':description' => $description,
        ':content' => $content,
        ':featured_image' => $featured_image,
        ':video_url' => $video_url,
        ':dashboard_url' => $dashboard_url,
        ':github_url' => $github_url,
        ':tags' => $tags,
        ':category' => $category,
        ':is_featured' => $is_featured,
        ':id' => $projectId
    ];
    
    $result = $db->query($sql, $params);
    
    if ($result) {
        $_SESSION['success'] = 'Project updated successfully';
        redirect('/admin/projects/list.php');
    } else {
        $_SESSION['error'] = 'Failed to update project';
        redirect('/admin/projects/edit.php?id=' . $projectId);
    }
}

// Generate CSRF token
$csrfToken = generateCSRFToken();

include '../includes/admin-header.php';
?>

<div class="admin-form-container">
    <form method="post" action="" enctype="multipart/form-data" class="admin-form">
        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
        
        <div class="form-group">
            <label for="title">Project Title *</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($project['title']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="description">Short Description *</label>
            <textarea id="description" name="description" rows="3" required><?php echo htmlspecialchars($project['description']); ?></textarea>
            <small>A brief summary of the project (displayed in project cards)</small>
        </div>
        
        <div class="form-group">
            <label for="content">Project Content</label>
            <textarea id="content" name="content" class="tinymce-editor" rows="10"><?php echo htmlspecialchars($project['content']); ?></textarea>
            <small>Detailed description of the project</small>
        </div>
        
        <div class="form-row">
            <div class="form-group half">
                <label for="category">Category</label>
                <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($project['category']); ?>">
                <small>E.g., Data Science, Web Development, etc.</small>
            </div>
            
            <div class="form-group half">
                <label for="tags">Tags</label>
                <input type="text" id="tags" name="tags" value="<?php echo htmlspecialchars($project['tags']); ?>">
                <small>Comma-separated tags (e.g., Python, Machine Learning, SQL)</small>
            </div>
        </div>
        
        <div class="form-group">
            <label for="featured_image">Featured Image</label>
            <?php if ($project['featured_image']): ?>
                <div class="current-image">
                    <img src="<?php echo SITE_URL . '/assets/uploads/projects/' . $project['featured_image']; ?>" 
                         alt="Current Image" style="max-width: 200px;">
                    <p>Current Image</p>
                </div>
            <?php endif; ?>
            <input type="file" id="featured_image" name="featured_image" accept="image/*">
            <small>Recommended size: 800x600 pixels</small>
        </div>
        
        <div class="form-row">
            <div class="form-group half">
                <label for="github_url">GitHub URL</label>
                <input type="url" id="github_url" name="github_url" value="<?php echo htmlspecialchars($project['github_url']); ?>">
            </div>
            
            <div class="form-group half">
                <label for="video_url">Video URL</label>
                <input type="url" id="video_url" name="video_url" value="<?php echo htmlspecialchars($project['video_url']); ?>">
                <small>YouTube or Vimeo link</small>
            </div>
        </div>
        
        <div class="form-group">
            <label for="dashboard_url">Dashboard URL</label>
            <input type="url" id="dashboard_url" name="dashboard_url" value="<?php echo htmlspecialchars($project['dashboard_url']); ?>">
            <small>Tableau or other dashboard link</small>
        </div>
        
        <div class="form-group checkbox">
            <input type="checkbox" id="is_featured" name="is_featured" <?php echo $project['is_featured'] ? 'checked' : ''; ?>>
            <label for="is_featured">Feature this project on homepage</label>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Project</button>
            <a href="/admin/projects/list.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include '../includes/admin-footer.php'; ?>
