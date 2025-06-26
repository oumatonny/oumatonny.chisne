<?php
require_once 'config.php';
require_once 'db.php';

// URL helper functions using your existing config constants
function base_url($path = '') {
    $path = ltrim($path, '/');
    return SITE_URL . ($path ? '/' . $path : '');
}

function asset_url($path = '') {
    $path = ltrim($path, '/');
    return ASSETS_URL . '/' . $path;
}

function admin_url($path = '') {
    $path = ltrim($path, '/');
    return ADMIN_URL . ($path ? '/' . $path : '');
}

// Redirect function with correct base path
function redirect($url) {
    // Check if URL is already absolute
    if (strpos($url, 'http') === 0) {
        header("Location: $url");
    } elseif (strpos($url, '/') === 0) {
        header("Location: $url");
    } else {
        // Add base URL for relative paths
        header("Location: " . base_url($url));
    }
    exit;
}

// Clean input data
function clean($data) {
    if (is_array($data)) {
        return array_map('clean', $data);
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Escape HTML output
function escapeHTML($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Generate slug from string
function generateSlug($string) {
    $slug = strtolower($string);
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug;
}

// Format date
function formatDate($date, $format = 'F j, Y') {
    return date($format, strtotime($date));
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Check if user is admin
function isAdmin() {
    return isLoggedIn() && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Require admin access
function requireAdmin() {
    if (!isAdmin()) {
        $_SESSION['error'] = 'Access denied. Admin privileges required.';
        redirect('admin/login.php');
    }
}

// Get personal info
function getPersonalInfo() {
    try {
        $db = Database::getInstance();
        $sql = "SELECT * FROM personal_info WHERE id = 1";
        return $db->fetch($sql);
    } catch (Exception $e) {
        error_log("Error fetching personal info: " . $e->getMessage());
        return null;
    }
}

// Get all projects
function getProjects($limit = null, $featured = false) {
    try {
        $db = Database::getInstance();
        
        $sql = "SELECT * FROM projects";
        $params = [];
        
        if ($featured) {
            $sql .= " WHERE is_featured = 1";
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        
        return $db->fetchAll($sql, $params);
    } catch (Exception $e) {
        error_log("Error fetching projects: " . $e->getMessage());
        return [];
    }
}

// Get project by ID or slug
function getProject($identifier) {
    try {
        $db = Database::getInstance();
        
        if (is_numeric($identifier)) {
            $sql = "SELECT * FROM projects WHERE id = ? AND status = 'published'";
            $params = [$identifier];
        } else {
            $sql = "SELECT * FROM projects WHERE slug = ? AND status = 'published'";
            $params = [$identifier];
        }
        
        return $db->fetch($sql, $params);
    } catch (Exception $e) {
        error_log("Error fetching project: " . $e->getMessage());
        return null;
    }
}

// Get all blog posts
function getBlogPosts($limit = null) {
    try {
        $db = Database::getInstance();
        
        $sql = "SELECT p.*, u.username as author_name 
                FROM blog_posts p 
                LEFT JOIN users u ON p.author_id = u.id 
                WHERE p.status = 'published' 
                ORDER BY p.published_at DESC";
        $params = [];
        
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        
        return $db->fetchAll($sql, $params);
    } catch (Exception $e) {
        error_log("Error fetching blog posts: " . $e->getMessage());
        return [];
    }
}

// Get blog post by ID or slug
function getBlogPost($identifier) {
    try {
        $db = Database::getInstance();
        
        if (is_numeric($identifier)) {
            $sql = "SELECT p.*, u.username as author_name 
                    FROM blog_posts p 
                    LEFT JOIN users u ON p.author_id = u.id 
                    WHERE p.id = ? AND p.status = 'published'";
            $params = [$identifier];
        } else {
            $sql = "SELECT p.*, u.username as author_name 
                    FROM blog_posts p 
                    LEFT JOIN users u ON p.author_id = u.id 
                    WHERE p.slug = ? AND p.status = 'published'";
            $params = [$identifier];
        }
        
        return $db->fetch($sql, $params);
    } catch (Exception $e) {
        error_log("Error fetching blog post: " . $e->getMessage());
        return null;
    }
}

// Get blog post tags
function getPostTags($postId) {
    try {
        $db = Database::getInstance();
        
        $sql = "SELECT t.* 
                FROM blog_tags t 
                JOIN blog_post_tags pt ON t.id = pt.tag_id 
                WHERE pt.post_id = ?";
        $params = [$postId];
        
        return $db->fetchAll($sql, $params);
    } catch (Exception $e) {
        error_log("Error fetching post tags: " . $e->getMessage());
        return [];
    }
}

// Upload file function using your existing config
function uploadFile($file, $subfolder = '') {
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
        return ['success' => false, 'message' => 'No file uploaded'];
    }
    
    $fileName = $file['name'];
    $fileTmp = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    
    if ($fileError !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'File upload error'];
    }
    
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if (!in_array($fileExt, $allowedTypes)) {
        return ['success' => false, 'message' => 'Invalid file type. Only JPG, JPEG, PNG, GIF, and WEBP are allowed.'];
    }
    
    if ($fileSize > MAX_UPLOAD_SIZE) {
        return ['success' => false, 'message' => 'File too large. Maximum size is ' . (MAX_UPLOAD_SIZE / 1024 / 1024) . 'MB'];
    }
    
    $newFileName = uniqid() . '.' . $fileExt;
    $uploadDir = __DIR__ . '/../' . UPLOAD_DIR . $subfolder;
    
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $uploadPath = $uploadDir . '/' . $newFileName;
    
    if (move_uploaded_file($fileTmp, $uploadPath)) {
        return ['success' => true, 'filename' => $newFileName, 'path' => $subfolder . '/' . $newFileName];
    } else {
        return ['success' => false, 'message' => 'Failed to move uploaded file'];
    }
}

// Get upload URL
function getUploadUrl($filename, $subfolder = '') {
    $path = ltrim($subfolder . '/' . $filename, '/');
    return SITE_URL . '/' . UPLOAD_DIR . $path;
}

// Hash password using your existing salt
function hashPassword($password) {
    return hash(HASH_ALGO, $password . SALT);
}

// Verify password
function verifyPassword($password, $hash) {
    return hash_equals($hash, hashPassword($password));
}

// Generate CSRF token
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF token
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Pagination helper
function paginate($currentPage, $totalItems, $itemsPerPage, $baseUrl) {
    $totalPages = ceil($totalItems / $itemsPerPage);
    $pagination = '';
    
    if ($totalPages > 1) {
        $pagination .= '<div class="pagination">';
        
        // Previous button
        if ($currentPage > 1) {
            $pagination .= '<a href="' . $baseUrl . '?page=' . ($currentPage - 1) . '" class="pagination-btn">Previous</a>';
        }
        
        // Page numbers
        $start = max(1, $currentPage - 2);
        $end = min($totalPages, $currentPage + 2);
        
        if ($start > 1) {
            $pagination .= '<a href="' . $baseUrl . '?page=1" class="pagination-btn">1</a>';
            if ($start > 2) {
                $pagination .= '<span class="pagination-dots">...</span>';
            }
        }
        
        for ($i = $start; $i <= $end; $i++) {
            if ($i == $currentPage) {
                $pagination .= '<span class="pagination-current">' . $i . '</span>';
            } else {
                $pagination .= '<a href="' . $baseUrl . '?page=' . $i . '" class="pagination-btn">' . $i . '</a>';
            }
        }
        
        if ($end < $totalPages) {
            if ($end < $totalPages - 1) {
                $pagination .= '<span class="pagination-dots">...</span>';
            }
            $pagination .= '<a href="' . $baseUrl . '?page=' . $totalPages . '" class="pagination-btn">' . $totalPages . '</a>';
        }
        
        // Next button
        if ($currentPage < $totalPages) {
            $pagination .= '<a href="' . $baseUrl . '?page=' . ($currentPage + 1) . '" class="pagination-btn">Next</a>';
        }
        
        $pagination .= '</div>';
    }
    
    return $pagination;
}

// Send email (basic implementation)
function sendEmail($to, $subject, $message, $from = null) {
    if (!$from) {
        $from = 'noreply@' . parse_url(SITE_URL, PHP_URL_HOST);
    }
    
    $headers = "From: $from\r\n";
    $headers .= "Reply-To: $from\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    return mail($to, $subject, $message, $headers);
}

// Log activity
function logActivity($action, $details = '') {
    try {
        $db = Database::getInstance();
        $userId = $_SESSION['user_id'] ?? null;
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        $sql = "INSERT INTO activity_logs (user_id, action, details, ip_address, user_agent, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
        $db->execute($sql, [$userId, $action, $details, $ipAddress, $userAgent]);
    } catch (Exception $e) {
        error_log("Error logging activity: " . $e->getMessage());
    }
}

// Get site statistics
function getSiteStats() {
    try {
        $db = Database::getInstance();
        $stats = [];
        // Count projects
        $result = $db->fetch("SELECT COUNT(*) as count FROM projects WHERE status = 'published'");
        $stats['projects'] = $result['count'] ?? 0;
        // Count blog posts
        $result = $db->fetch("SELECT COUNT(*) as count FROM blog_posts WHERE status = 'published'");
        $stats['blog_posts'] = $result['count'] ?? 0;
        // Count users (no status column)
        $result = $db->fetch("SELECT COUNT(*) as count FROM users");
        $stats['users'] = $result['count'] ?? 0;
        return $stats;
    } catch (Exception $e) {
        error_log("Error fetching site stats: " . $e->getMessage());
        return ['projects' => 0, 'blog_posts' => 0, 'users' => 0];
    }
}

// Truncate text
function truncateText($text, $length = 150, $suffix = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    
    return substr($text, 0, $length) . $suffix;
}

// Time ago function
function timeAgo($datetime) {
    $time = time() - strtotime($datetime);
    
    if ($time < 60) return 'just now';
    if ($time < 3600) return floor($time/60) . ' minutes ago';
    if ($time < 86400) return floor($time/3600) . ' hours ago';
    if ($time < 2592000) return floor($time/86400) . ' days ago';
    if ($time < 31536000) return floor($time/2592000) . ' months ago';
    
    return floor($time/31536000) . ' years ago';
}

// Asset URL helper function
function asset($path = '') {
    $path = ltrim($path, '/');
    return ASSETS_URL . '/' . $path;
}

// URL helper function
function url($path = '') {
    $path = ltrim($path, '/');
    return SITE_URL . ($path ? '/' . $path : '');
}
?>
