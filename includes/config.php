<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'oumatonny_portfolio');

// Site configuration
define('SITE_NAME', 'Ouma Tonny | Programmer & Data Scientist');
define('SITE_URL', 'http://localhost:8080/oumatonny');
define('BASE_PATH', '/oumatonny');
define('ADMIN_URL', SITE_URL . '/admin');
define('ASSETS_URL', SITE_URL . '/assets');

// File upload configuration
define('UPLOAD_DIR', 'assets/uploads/');
define('MAX_UPLOAD_SIZE', 5242880); // 5MB

// Security configuration
define('SALT', 'your-secret-salt-here');
define('HASH_ALGO', 'sha256');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session configuration
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Create upload directories if they don't exist
$uploadPath = __DIR__ . '/../' . UPLOAD_DIR;
if (!file_exists($uploadPath)) {
    mkdir($uploadPath, 0755, true);
}

$projectUploadPath = $uploadPath . 'projects/';
if (!file_exists($projectUploadPath)) {
    mkdir($projectUploadPath, 0755, true);
}

$blogUploadPath = $uploadPath . 'blog/';
if (!file_exists($blogUploadPath)) {
    mkdir($blogUploadPath, 0755, true);
}

define('PROJECT_UPLOAD_PATH', 'assets/uploads/projects/');
