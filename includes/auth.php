<?php
require_once 'db.php';

class Auth {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Register new user (admin only)
    public function register($username, $email, $password, $firstName, $lastName) {
        // Check if username already exists
        $sql = "SELECT id FROM users WHERE username = :username";
        $user = $this->db->fetch($sql, [':username' => $username]);
        
        if ($user) {
            return ['success' => false, 'message' => 'Username already exists'];
        }
        
        // Check if email already exists
        $sql = "SELECT id FROM users WHERE email = :email";
        $user = $this->db->fetch($sql, [':email' => $email]);
        
        if ($user) {
            return ['success' => false, 'message' => 'Email already exists'];
        }
        
        // Hash password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new user
        $sql = "INSERT INTO users (username, password_hash, email, full_name, created_at, updated_at)
                VALUES (:username, :password_hash, :email, :full_name, NOW(), NOW())";
        $params = [
            ':username' => $username,
            ':password_hash' => $passwordHash,
            ':email' => $email,
            ':full_name' => $firstName . ' ' . $lastName
        ];
        
        $result = $this->db->query($sql, $params);
        
        if ($result) {
            return ['success' => true, 'user_id' => $this->db->lastInsertId()];
        } else {
            return ['success' => false, 'message' => 'Registration failed'];
        }
    }
    
    // Login user
    public function login($username, $password) {
        // Get user by username
        $sql = "SELECT id, username, password_hash, full_name FROM users WHERE username = :username";
        $user = $this->db->fetch($sql, [':username' => $username]);
        
        if (!$user) {
            return ['success' => false, 'message' => 'Invalid username or password'];
        }
        
        // Verify password
        if (password_verify($password, $user['password_hash'])) {
            // Update last login time
            $sql = "UPDATE users SET last_login = NOW() WHERE id = :id";
            $this->db->query($sql, [':id' => $user['id']]);
            
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['user_role'] = 'admin';
            
            return ['success' => true, 'user' => $user];
        } else {
            return ['success' => false, 'message' => 'Invalid username or password'];
        }
    }
    
    // Logout user
    public function logout() {
        // Unset all session variables
        $_SESSION = [];
        
        // Destroy the session
        session_destroy();
        
        return true;
    }
    
    // Check if any users exist
    public function hasUsers() {
        $sql = "SELECT COUNT(*) as count FROM users";
        $result = $this->db->fetch($sql);
        return $result['count'] > 0;
    }
}
