<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Generate CSRF token
// function generateCSRFToken() {
//     if (!isset($_SESSION['csrf_token'])) {
//         $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
//     }
//     return $_SESSION['csrf_token'];
// }

// Validate CSRF token
function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Sanitize input
function sanitizeInput($input) {
    if (is_array($input)) {
        return array_map('sanitizeInput', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Check if request is POST
function isPostRequest() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

// Rate limiting (simple implementation)
function checkRateLimit($identifier, $maxAttempts = 5, $timeWindow = 300) {
    $key = 'rate_limit_' . md5($identifier);
    
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = ['count' => 0, 'first_attempt' => time()];
    }
    
    $data = $_SESSION[$key];
    
    // Reset if time window has passed
    if (time() - $data['first_attempt'] > $timeWindow) {
        $_SESSION[$key] = ['count' => 1, 'first_attempt' => time()];
        return true;
    }
    
    // Check if limit exceeded
    if ($data['count'] >= $maxAttempts) {
        return false;
    }
    
    // Increment counter
    $_SESSION[$key]['count']++;
    return true;
}

// Log security events
function logSecurityEvent($event, $details = '') {
    $logFile = __DIR__ . '/../logs/security.log';
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    
    $logEntry = "[$timestamp] $event - IP: $ip - User Agent: $userAgent";
    if ($details) {
        $logEntry .= " - Details: $details";
    }
    $logEntry .= PHP_EOL;
    
    // Create logs directory if it doesn't exist
    $logDir = dirname($logFile);
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}

// Validate email format
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Check password strength
function checkPasswordStrength($password) {
    $score = 0;
    $feedback = [];
    
    // Length check
    if (strlen($password) >= 8) {
        $score += 25;
    } else {
        $feedback[] = 'Password should be at least 8 characters long';
    }
    
    // Uppercase check
    if (preg_match('/[A-Z]/', $password)) {
        $score += 25;
    } else {
        $feedback[] = 'Password should contain at least one uppercase letter';
    }
    
    // Lowercase check
    if (preg_match('/[a-z]/', $password)) {
        $score += 25;
    } else {
        $feedback[] = 'Password should contain at least one lowercase letter';
    }
    
    // Number or special character check
    if (preg_match('/[0-9]/', $password) || preg_match('/[^A-Za-z0-9]/', $password)) {
        $score += 25;
    } else {
        $feedback[] = 'Password should contain at least one number or special character';
    }
    
    return [
        'score' => $score,
        'feedback' => $feedback,
        'strength' => $score < 50 ? 'weak' : ($score < 75 ? 'medium' : 'strong')
    ];
}

// Prevent XSS attacks
function preventXSS($data) {
    if (is_array($data)) {
        return array_map('preventXSS', $data);
    }
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Generate secure random token
function generateSecureToken($length = 32) {
    return bin2hex(random_bytes($length));
}

// Check if IP is blocked
function isIPBlocked($ip) {
    $blockedIPs = [
        // Add blocked IPs here if needed
    ];
    
    return in_array($ip, $blockedIPs);
}

// Block IP address
function blockIP($ip, $reason = '') {
    logSecurityEvent('IP_BLOCKED', "IP: $ip, Reason: $reason");
    // In a real application, you might want to store this in database
}

// Helper functions for requireAdmin
// function isLoggedIn() {
//     // Implement your login check logic here
//     return isset($_SESSION['user_id']);
// }

// function isAdmin() {
//     // Implement your admin check logic here
//     return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
// }

// function redirect($url) {
//     header("Location: $url");
//     exit();
// }

// Check login attempts
function checkLoginAttempts($identifier) {
    return checkRateLimit($identifier, 5, 300); // 5 attempts in 5 minutes
}

// Increment login attempts
function incrementLoginAttempts($identifier) {
    return checkRateLimit($identifier, 5, 300); // 5 attempts in 5 minutes
}
?>
