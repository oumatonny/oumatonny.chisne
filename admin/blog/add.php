<?php
require_once '../../includes/config.php';
require_once '../../includes/functions.php';
require_once '../../includes/security.php';

// Check if user is logged in
requireAdmin();

$pageTitle = 'Add New Blog Post';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check CSRF token
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $_SESSION['error'] = 'Invalid form submission';
        redirect('/oumatonny/admin/blog/add.php');
    }
    
    // Get form data
    $title = clean($_POST['title'] ?? '');
    $excerpt = clean($_POST['excerpt'] ?? '');
    $content = $_POST['content'] ?? '';
    $tags = clean($_POST['tags'] ?? '');
    $status = clean($_POST['status'] ?? 'published');
    
    // Validate required fields
    if (empty($title) || empty($content)) {
        $_SESSION['error'] = 'Title and content are required';
        redirect('/oumatonny/admin/blog/add.php');
    }
    
    // Generate slug
    $slug = generateSlug($title);
    
    // Check if slug already exists
    $db = Database::getInstance();
    $existingSlug = $db->fetch("SELECT id FROM blog_posts WHERE slug = :slug", [':slug' => $slug]);
    
    if ($existingSlug) {
        // Append a unique identifier to the slug
        $slug = $slug . '-' . time();
    }
    
    // Handle image upload
    $featured_image = '';
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
        $upload = uploadFile($_FILES['featured_image'], BLOG_UPLOAD_PATH);
        
        if ($upload['success']) {
            $featured_image = $upload['filename'];
        } else {
            $_SESSION['error'] = $upload['message'];
            redirect('/oumatonny/admin/blog/add.php');
        }
    }
    
    // Insert blog post into database
    $sql = "INSERT INTO blog_posts (title, slug, excerpt, content, featured_image, author_id, status) 
            VALUES (:title, :slug, :excerpt, :content, :featured_image, :author_id, :status)";
    
    $params = [
        ':title' => $title,
        ':slug' => $slug,
        ':excerpt' => $excerpt,
        ':content' => $content,
        ':featured_image' => $featured_image,
        ':author_id' => $_SESSION['user_id'],
        ':status' => $status
    ];
    
    $result = $db->query($sql, $params);
    
    if ($result) {
        $postId = $db->lastInsertId();
        
        // Process tags
        if (!empty($tags)) {
            $tagArray = array_map('trim', explode(',', $tags));
            
            foreach ($tagArray as $tag) {
                if (empty($tag)) continue;
                
                // Check if tag exists
                $tagSlug = generateSlug($tag);
                $existingTag = $db->fetch("SELECT id FROM blog_tags WHERE slug = :slug", [':slug' => $tagSlug]);
                
                if ($existingTag) {
                    $tagId = $existingTag['id'];
                } else {
                    // Create new tag
                    $db->query("INSERT INTO blog_tags (name, slug) VALUES (:name, :slug)", [':name' => $tag, ':slug' => $tagSlug]);
                    $tagId = $db->lastInsertId();
                }
                
                // Associate tag with post
                $db->query("INSERT INTO blog_post_tags (post_id, tag_id) VALUES (:post_id, :tag_id)", [':post_id' => $postId, ':tag_id' => $tagId]);
            }
        }
        
        $_SESSION['success'] = 'Blog post added successfully';
        redirect('/oumatonny/admin/blog/list.php');
    } else {
        $_SESSION['error'] = 'Failed to add blog post';
        redirect('/oumatonny/admin/blog/add.php');
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
            <label for="title">Post Title *</label>
            <input type="text" id="title" name="title" required>
        </div>
        
        <div class="form-group">
            <label for="excerpt">Excerpt</label>
            <textarea id="excerpt" name="excerpt" rows="3"></textarea>
            <small>A short summary of the post (displayed in blog listings)</small>
        </div>
        
        <div class="form-group">
            <label for="content">Post Content *</label>
            <textarea id="content" name="content" class="tinymce-editor" rows="15" required></textarea>
        </div>
        
        <div class="form-group">
            <label for="featured_image">Featured Image</label>
            <input type="file" id="featured_image" name="featured_image" accept="image/*">
            <small>Recommended size: 1200x630 pixels</small>
        </div>
        
        <div class="form-row">
            <div class="form-group half">
                <label for="tags">Tags</label>
                <input type="text" id="tags" name="tags">
                <small>Comma-separated tags (e.g., Data Science, Programming, Career)</small>
            </div>
            
            <div class="form-group half">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                </select>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Add Post</button>
            <a href="/admin/blog/list.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include '../includes/admin-footer.php'; ?>
