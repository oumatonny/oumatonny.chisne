<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Get user info before logout
$userInfo = null;
if (isLoggedIn()) {
    $userInfo = $_SESSION['user'] ?? null;
}

// Logout user
$auth = new Auth();
$auth->logout();

// Check if we should show the logout page or redirect immediately
$showLogoutPage = isset($_GET['show_page']) && $_GET['show_page'] == 1;

if (!$showLogoutPage) {
    // Redirect to login page
    redirect('/admin/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Out | <?php echo SITE_NAME; ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/dark-theme.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <meta http-equiv="refresh" content="5;url=/admin/login.php">
</head>
<body class="dark-theme logout-page">
    <div class="logout-container">
        <div class="logout-box">
            <div class="logout-icon">
                <i class="fas fa-check"></i>
            </div>
            
            <div class="logout-message">
                <h2>You've Been Logged Out</h2>
                <p>
                    <?php if ($userInfo): ?>
                        Thank you, <?php echo escapeHTML($userInfo['first_name'] ?? $userInfo['username']); ?>, for using the admin panel.
                    <?php else: ?>
                        Thank you for using the admin panel.
                    <?php endif; ?>
                </p>
                <p class="text-muted">You will be redirected to the login page in 5 seconds...</p>
            </div>
            
            <div class="logout-actions">
                <a href="/admin/login.php" class="btn btn-primary">Login Again</a>
                <a href="/" class="btn btn-outline">Return to Website</a>
            </div>
        </div>
    </div>
</body>
</html>
