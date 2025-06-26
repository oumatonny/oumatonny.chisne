<?php
require_once '../../includes/config.php';
require_once '../../includes/functions.php';
require_once '../../includes/security.php';

// Check if user is logged in
requireAdmin();

$pageTitle = 'Social Links';

// Get personal info
$db = Database::getInstance();
$personalInfo = $db->fetch("SELECT * FROM personal_info WHERE id = 1");

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check CSRF token
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $_SESSION['error'] = 'Invalid form submission';
        redirect('/admin/settings/social.php');
    }
    
    // Get form data
    $github_url = clean($_POST['github_url'] ?? '');
    $linkedin_url = clean($_POST['linkedin_url'] ?? '');
    $twitter_url = clean($_POST['twitter_url'] ?? '');
    
    if ($personalInfo) {
        // Update existing record
        $sql = "UPDATE personal_info 
                SET github_url = :github_url, linkedin_url = :linkedin_url, twitter_url = :twitter_url 
                WHERE id = 1";
    } else {
        // Insert new record with default values
        $sql = "INSERT INTO personal_info (id, full_name, title, github_url, linkedin_url, twitter_url) 
                VALUES (1, 'Ouma Tonny', 'Programmer & Data Scientist', :github_url, :linkedin_url, :twitter_url)";
    }
    
    $params = [
        ':github_url' => $github_url,
        ':linkedin_url' => $linkedin_url,
        ':twitter_url' => $twitter_url
    ];
    
    $result = $db->query($sql, $params);
    
    if ($result) {
        $_SESSION['success'] = 'Social links updated successfully';
        redirect('/admin/settings/social.php');
    } else {
        $_SESSION['error'] = 'Failed to update social links';
        redirect('/admin/settings/social.php');
    }
}

// Generate CSRF token
$csrfToken = generateCSRFToken();

include '../includes/admin-header.php';
?>

<div class="admin-form-container">
    <form method="post" action="" class="admin-form">
        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
        
        <div class="form-group">
            <label for="github_url">GitHub URL</label>
            <div class="input-icon">
                <i class="fab fa-github"></i>
                <input type="url" id="github_url" name="github_url" value="<?php echo $personalInfo['github_url'] ?? 'https://github.com/oumatonny'; ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label for="linkedin_url">LinkedIn URL</label>
            <div class="input-icon">
                <i class="fab fa-linkedin"></i>
                <input type="url" id="linkedin_url" name="linkedin_url" value="<?php echo $personalInfo['linkedin_url'] ?? ''; ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label for="twitter_url">Twitter URL</label>
            <div class="input-icon">
                <i class="fab fa-twitter"></i>
                <input type="url" id="twitter_url" name="twitter_url" value="<?php echo $personalInfo['twitter_url'] ?? ''; ?>">
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </form>
</div>

<?php include '../includes/admin-footer.php'; ?>
