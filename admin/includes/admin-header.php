<?php
// Check if user is logged in
if (!isLoggedIn()) {
    redirect(base_url('admin/login.php'));
}

// Get current page
$currentPage = basename($_SERVER['PHP_SELF']);

// Get user info
$userInfo = $_SESSION['user'] ?? null;
$isAdmin = isAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Admin Panel'; ?> | <?php echo SITE_NAME; ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/dark-theme.css">
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/animations.css">
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/enhanced-hero.css">
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/admin.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="dark-theme admin-panel">
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <div class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <h2>Admin Panel</h2>
                <span class="sidebar-close" id="sidebarClose">
                    <i class="fas fa-times"></i>
                </span>
            </div>
            
            <div class="admin-profile">
                <div class="profile-info">
                    <div class="profile-name"><?php echo escapeHTML($userInfo['first_name'] ?? '') . ' ' . escapeHTML($userInfo['last_name'] ?? ''); ?></div>
                    <div class="profile-role"><?php echo ucfirst(escapeHTML($userInfo['role'] ?? 'User')); ?></div>
                </div>
            </div>
            
            <nav class="admin-nav">
                <ul>
                    <li>
                        <a href="<?php echo admin_url('dashboard.php'); ?>" class="<?php echo $currentPage === 'dashboard.php' ? 'active' : ''; ?>">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    
                    <li>
                        <a href="#" class="nav-dropdown <?php echo strpos($currentPage, 'projects') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-project-diagram"></i> Projects
                            <i class="fas fa-chevron-down dropdown-icon"></i>
                        </a>
                        <ul class="dropdown-menu <?php echo strpos($currentPage, 'projects') !== false ? 'active' : ''; ?>">
                            <li>
                                <a href="<?php echo admin_url('projects/add.php'); ?>" class="<?php echo $currentPage === 'add.php' && strpos($_SERVER['REQUEST_URI'], 'projects') !== false ? 'active' : ''; ?>">
                                    Add Project
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo admin_url('projects/list.php'); ?>" class="<?php echo $currentPage === 'list.php' && strpos($_SERVER['REQUEST_URI'], 'projects') !== false ? 'active' : ''; ?>">
                                    All Projects
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <li>
                        <a href="#" class="nav-dropdown <?php echo strpos($currentPage, 'blog') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-blog"></i> Blog
                            <i class="fas fa-chevron-down dropdown-icon"></i>
                        </a>
                        <ul class="dropdown-menu <?php echo strpos($currentPage, 'blog') !== false ? 'active' : ''; ?>">
                            <li>
                                <a href="<?php echo admin_url('blog/add.php'); ?>" class="<?php echo $currentPage === 'add.php' && strpos($_SERVER['REQUEST_URI'], 'blog') !== false ? 'active' : ''; ?>">
                                    Add Post
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo admin_url('blog/list.php'); ?>" class="<?php echo $currentPage === 'list.php' && strpos($_SERVER['REQUEST_URI'], 'blog') !== false ? 'active' : ''; ?>">
                                    All Posts
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <li>
                        <a href="#" class="nav-dropdown <?php echo strpos($currentPage, 'settings') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-cog"></i> Settings
                            <i class="fas fa-chevron-down dropdown-icon"></i>
                        </a>
                        <ul class="dropdown-menu <?php echo strpos($currentPage, 'settings') !== false ? 'active' : ''; ?>">
                            <li>
                                <a href="<?php echo admin_url('settings/profile.php'); ?>" class="<?php echo $currentPage === 'profile.php' ? 'active' : ''; ?>">
                                    Profile
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo admin_url('settings/social.php'); ?>" class="<?php echo $currentPage === 'social.php' ? 'active' : ''; ?>">
                                    Social Media
                                </a>
                            </li>
                            <?php if ($isAdmin): ?>
                            <li>
                                <a href="<?php echo admin_url('register.php'); ?>" class="<?php echo $currentPage === 'register.php' ? 'active' : ''; ?>">
                                    Add User
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <a href="<?php echo admin_url('logout.php?show_page=1'); ?>" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
        
        <!-- Content -->
        <div class="admin-content">
            <header class="admin-header">
                <div class="header-left">
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title"><?php echo $pageTitle ?? 'Admin Panel'; ?></h1>
                </div>
                
                <div class="header-right">
                    <a href="<?php echo base_url(); ?>" class="view-site" target="_blank">
                        <i class="fas fa-external-link-alt"></i>
                        <span>View Site</span>
                    </a>
                    
                    <div class="admin-dropdown">
                        <div class="dropdown-trigger">
                            <i class="fas fa-user-circle"></i>
                            <span><?php echo escapeHTML($userInfo['username'] ?? 'User'); ?></span>
                        </div>
                        
                        <div class="dropdown-content">
                            <a href="<?php echo admin_url('settings/profile.php'); ?>">
                                <i class="fas fa-user-cog"></i> Profile
                            </a>
                            <?php if ($isAdmin): ?>
                            <a href="<?php echo admin_url('register.php'); ?>">
                                <i class="fas fa-user-plus"></i> Add User
                            </a>
                            <?php endif; ?>
                            <a href="<?php echo admin_url('logout.php'); ?>">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </header>
            
            <main class="admin-main">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?php 
                        echo $_SESSION['success']; 
                        unset($_SESSION['success']);
                        ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php 
                        echo $_SESSION['error']; 
                        unset($_SESSION['error']);
                        ?>
                    </div>
                <?php endif; ?>
