<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

// Set current page based on URL
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
if ($currentPage === 'index') {
    $currentPage = 'index';
} elseif ($currentPage === 'portfolio' || $currentPage === 'index' && basename(dirname($_SERVER['PHP_SELF'])) === 'portfolio') {
    $currentPage = 'portfolio';
} elseif ($currentPage === 'blog' || $currentPage === 'index' && basename(dirname($_SERVER['PHP_SELF'])) === 'blog') {
    $currentPage = 'blog';
} elseif ($currentPage === 'about') {
    $currentPage = 'about';
} elseif ($currentPage === 'contact') {
    $currentPage = 'contact';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' | ' . SITE_NAME : SITE_NAME; ?></title>
    <meta name="description" content="<?php echo isset($pageDescription) ? $pageDescription : 'Ouma Tonny - Programmer and Data Scientist Portfolio'; ?>">
    
    <!-- Favicon -->
    <link rel="icon" href="<?php echo ASSETS_URL; ?>/images/favicon.ico" type="image/x-icon">
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/dark-theme.css">
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/animations.css">
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/enhanced-hero.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="dark-theme">
    <div class="page-wrapper">
        <header class="site-header">
            <div class="container">
                <div class="header-inner">
                    <div class="logo">
                        <a href="<?php echo BASE_PATH; ?>">
                            <span class="logo-text">Ouma Tonny</span>
                        </a>
                    </div>
                    
                    <nav class="main-nav">
                        <ul>
                            <li><a href="<?php echo SITE_URL; ?>" class="<?php echo ($currentPage == 'index') ? 'active' : ''; ?>">Home</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/portfolio/" class="<?php echo ($currentPage == 'portfolio') ? 'active' : ''; ?>">Portfolio</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/blog/" class="<?php echo ($currentPage == 'blog') ? 'active' : ''; ?>">Blog</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/about.php" class="<?php echo ($currentPage == 'about') ? 'active' : ''; ?>">About</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/contact.php" class="<?php echo ($currentPage == 'contact') ? 'active' : ''; ?>">Contact</a></li>
                        </ul>
                    </nav>
                    
                    <div class="mobile-menu-toggle">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </header>
        
        <div class="mobile-menu">
            <ul>
                <li><a href="<?php echo SITE_URL; ?>" class="<?php echo ($currentPage == 'index') ? 'active' : ''; ?>">Home</a></li>
                <li><a href="<?php echo SITE_URL; ?>/portfolio/" class="<?php echo ($currentPage == 'portfolio') ? 'active' : ''; ?>">Portfolio</a></li>
                <li><a href="<?php echo SITE_URL; ?>/blog/" class="<?php echo ($currentPage == 'blog') ? 'active' : ''; ?>">Blog</a></li>
                <li><a href="<?php echo SITE_URL; ?>/about.php" class="<?php echo ($currentPage == 'about') ? 'active' : ''; ?>">About</a></li>
                <li><a href="<?php echo SITE_URL; ?>/contact.php" class="<?php echo ($currentPage == 'contact') ? 'active' : ''; ?>">Contact</a></li>
            </ul>
        </div>
        
        <main class="main-content">
