<?php
require_once '../../includes/config.php';
require_once '../../includes/functions.php';
require_once '../../includes/security.php';

// Check if user is logged in
requireAdmin();

$pageTitle = 'Profile Settings';

// Get personal info
$db = Database::getInstance();
$personalInfo = $db->fetch("SELECT * FROM personal_info WHERE id = 1");

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check CSRF token
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $_SESSION['error'] = 'Invalid form submission';
        redirect('/admin/settings/profile.php');
    }
    
    // Get form data
    $full_name = clean($_POST['full_name'] ?? '');
    $title = clean($_POST['title'] ?? '');
    $bio = clean($_POST['bio'] ?? '');
    $email = clean($_POST['email'] ?? '');
    $phone = clean($_POST['phone'] ?? '');
    $location = clean($_POST['location'] ?? '');
    
    // Validate required fields
    if (empty($full_name)) {
        $_SESSION['error'] = 'Full name is required';
        redirect('/admin/settings/profile.php');
    }
    
    if ($personalInfo) {
        // Update existing record
        $sql = "UPDATE personal_info 
                SET full_name = :full_name, title = :title, bio = :bio, email = :email, phone = :phone, location = :location 
                WHERE id = 1";
    } else {
        // Insert new record
        $sql = "INSERT INTO personal_info (id, full_name, title, bio, email, phone, location) 
                VALUES (1, :full_name, :title, :bio, :email, :phone, :location)";
    }
    
    $params = [
        ':full_name' => $full_name,
        ':title' => $title,
        ':bio' => $bio,
        ':email' => $email,
        ':phone' => $phone,
        ':location' => $location
    ];
    
    $result = $db->query($sql, $params);
    
    if ($result) {
        $_SESSION['success'] = 'Profile updated successfully';
        redirect('/admin/settings/profile.php');
    } else {
        $_SESSION['error'] = 'Failed to update profile';
        redirect('/admin/settings/profile.php');
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
            <label for="full_name">Full Name *</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo $personalInfo['full_name'] ?? 'Ouma Tonny'; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="title">Professional Title</label>
            <input type="text" id="title" name="title" value="<?php echo $personalInfo['title'] ?? 'Programmer & Data Scientist'; ?>">
            <small>E.g., Programmer & Data Scientist</small>
        </div>
        
        <div class="form-group">
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio" rows="6"><?php echo $personalInfo['bio'] ?? ''; ?></textarea>
            <small>Tell visitors about yourself and your professional journey</small>
        </div>
        
        <div class="form-row">
            <div class="form-group half">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $personalInfo['email'] ?? ''; ?>">
            </div>
            
            <div class="form-group half">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" value="<?php echo $personalInfo['phone'] ?? ''; ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" id="location" name="location" value="<?php echo $personalInfo['location'] ?? ''; ?>">
            <small>E.g., Nairobi, Kenya</small>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </form>
</div>

<?php include '../includes/admin-footer.php'; ?>
