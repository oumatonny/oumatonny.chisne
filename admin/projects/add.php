<?php
require_once '../../includes/config.php';
require_once '../../includes/functions.php';
require_once '../../includes/security.php';

// Check if user is logged in
requireAdmin();

$pageTitle = 'Add New Project';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check CSRF token
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $_SESSION['error'] = 'Invalid form submission';
        redirect('/admin/projects/add.php');
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
        redirect('/admin/projects/add.php');
    }
    
    // Generate slug
    $slug = generateSlug($title);
    
    // Check if slug already exists
    $db = Database::getInstance();
    $existingSlug = $db->fetch("SELECT id FROM projects WHERE slug = :slug", [':slug' => $slug]);
    
    if ($existingSlug) {
        // Append a unique identifier to the slug
        $slug = $slug . '-' . time();
    }
    
    // Handle image upload
    $featured_image = '';
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
        try {
            $upload = uploadFile($_FILES['featured_image'], 'projects/');
            
            if ($upload['success']) {
                $featured_image = $upload['filename'];
            } else {
                error_log("Image upload failed: " . $upload['message']);
                $_SESSION['error'] = "Failed to upload image: " . $upload['message'];
                redirect('/admin/projects/add.php');
            }
        } catch (Exception $e) {
            error_log("Error during image upload: " . $e->getMessage());
            $_SESSION['error'] = "Failed to upload image. Please try again.";
            redirect('/admin/projects/add.php');
        }
    }
    
    // Insert project into database
    $sql = "INSERT INTO projects (title, slug, description, content, featured_image, video_url, dashboard_url, github_url, tags, category, is_featured) 
            VALUES (:title, :slug, :description, :content, :featured_image, :video_url, :dashboard_url, :github_url, :tags, :category, :is_featured)";
    
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
        ':is_featured' => $is_featured
    ];
    
    $result = $db->query($sql, $params);
    
    if ($result) {
        $_SESSION['success'] = 'Project added successfully';
        redirect('/admin/projects/list.php');
    } else {
        $_SESSION['error'] = 'Failed to add project';
        redirect('/admin/projects/add.php');
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
            <input type="text" id="title" name="title" required>
        </div>
        
        <div class="form-group">
            <label for="description">Short Description *</label>
            <textarea id="description" name="description" rows="3" required></textarea>
            <small>A brief summary of the project (displayed in project cards)</small>
        </div>
        
        <div class="form-group">
            <label for="content">Project Content</label>
            <textarea id="content" name="content" class="tinymce-editor" rows="10"></textarea>
            <small>Detailed description of the project</small>
        </div>
        
        <div class="form-row">
            <div class="form-group half">
                <label for="category">Category</label>
                <input type="text" id="category" name="category">
                <small>E.g., Data Science, Web Development, etc.</small>
            </div>
            
            <div class="form-group half">
                <label for="tags">Tags</label>
                <input type="text" id="tags" name="tags">
                <small>Comma-separated tags (e.g., Python, Machine Learning, SQL)</small>
            </div>
        </div>
        
        <div class="form-group">
            <label for="featured_image">Featured Image</label>
            <input type="file" id="featured_image" name="featured_image" accept="image/*">
            <small>Recommended size: 800x600 pixels</small>
        </div>
        
        <div class="form-row">
            <div class="form-group half">
                <label for="github_url">GitHub URL</label>
                <input type="url" id="github_url" name="github_url">
            </div>
            
            <div class="form-group half">
                <label for="video_url">Video URL</label>
                <input type="url" id="video_url" name="video_url">
                <small>YouTube or Vimeo link</small>
            </div>
        </div>
        
        <div class="form-group">
            <label for="dashboard_url">Dashboard URL</label>
            <input type="url" id="dashboard_url" name="dashboard_url">
            <small>Tableau or other dashboard link</small>
        </div>
        
        <div class="form-group checkbox">
            <input type="checkbox" id="is_featured" name="is_featured">
            <label for="is_featured">Feature this project on homepage</label>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Add Project</button>
            <a href="/admin/projects/list.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include '../includes/admin-footer.php'; ?>
