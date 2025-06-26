<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

// Check if user is logged in
if (!isLoggedIn()) {
    // Redirect to login page
    redirect('/oumatonny/admin/login.php');
} else {
    // Redirect to dashboard
    redirect('/oumatonny/admin/dashboard.php');
}
