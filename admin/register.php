<?php
// Start session
session_start();

require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';
require_once '../includes/security.php';

$pageTitle = 'Register Admin Account';
$error = '';
$success = '';
$username = '';
$email = '';
$firstName = '';
$lastName = '';

// Check if any users exist - if not, this is initial setup
$auth = new Auth();
$isInitialSetup = !$auth->hasUsers();

// If not initial setup, check if user is logged in and is admin
if (!$isInitialSetup) {
    if (!isLoggedIn() || !isAdmin()) {
        $_SESSION['error'] = "You must be logged in as an admin to register new users";
        redirect('admin/login.php');
    }
}

// Process registration form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check CSRF token
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid form submission. Please try again.';
    } else {
        $username = clean($_POST['username'] ?? '');
        $email = clean($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $firstName = clean($_POST['first_name'] ?? '');
        $lastName = clean($_POST['last_name'] ?? '');
        
        // Validate inputs
        if (empty($username) || empty($email) || empty($password) || empty($confirmPassword) || empty($firstName) || empty($lastName)) {
            $error = 'All fields are required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address';
        } elseif ($password !== $confirmPassword) {
            $error = 'Passwords do not match';
        } elseif (strlen($password) < 8) {
            $error = 'Password must be at least 8 characters long';
        } elseif (strlen($username) < 3) {
            $error = 'Username must be at least 3 characters long';
        } else {
            // Create user
            $result = $auth->register($username, $email, $password, $firstName, $lastName);
            
            if ($result['success']) {
                $success = $isInitialSetup ? 
                    'Admin account created successfully! You can now log in.' : 
                    'Admin account created successfully!';
                
                // If this is initial setup, redirect to login after a delay
                if ($isInitialSetup) {
                    echo "<script>
                        setTimeout(function() {
                            window.location.href = '" . url('admin/login.php') . "';
                        }, 3000);
                    </script>";
                }
                
                // Clear form fields on success
                $username = '';
                $email = '';
                $firstName = '';
                $lastName = '';
            } else {
                $error = $result['message'];
            }
        }
    }
}

// Generate CSRF token
$csrfToken = generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> | <?php echo SITE_NAME; ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/dark-theme.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/admin.css'); ?>">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="dark-theme admin-login">
    <div class="login-container">
        <div class="login-box register-box">
            <div class="login-header">
                <div class="logo">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h1><?php echo $isInitialSetup ? 'Welcome! Setup Your Admin Account' : 'Register New Admin'; ?></h1>
                <p><?php echo $isInitialSetup ? 'Create your administrator account to get started with your portfolio' : 'Create a new administrator account'; ?></p>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $success; ?>
                    <?php if ($isInitialSetup): ?>
                        <br><small>Redirecting to login page in 3 seconds...</small>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <?php if (empty($success)): ?>
            <form method="post" action="" class="login-form register-form">
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                
                <div class="form-row">
                    <div class="form-group half">
                        <label for="first_name">First Name</label>
                        <div class="input-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" id="first_name" name="first_name" value="<?php echo escapeHTML($firstName); ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group half">
                        <label for="last_name">Last Name</label>
                        <div class="input-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" id="last_name" name="last_name" value="<?php echo escapeHTML($lastName); ?>" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-icon">
                        <i class="fas fa-user-circle"></i>
                        <input type="text" id="username" name="username" value="<?php echo escapeHTML($username); ?>" required minlength="3">
                    </div>
                    <small class="form-text">At least 3 characters</small>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" value="<?php echo escapeHTML($email); ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" required minlength="8">
                        <span class="password-toggle" onclick="togglePasswordVisibility('password')">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    <div class="password-strength">
                        <div class="password-strength-bar"></div>
                    </div>
                    <div class="password-strength-text"></div>
                    <small class="form-text">At least 8 characters</small>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
                        <span class="password-toggle" onclick="togglePasswordVisibility('confirm_password')">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    <div class="password-match-indicator"></div>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-user-plus"></i>
                        <?php echo $isInitialSetup ? 'Create Admin Account' : 'Register Admin'; ?>
                    </button>
                </div>
            </form>
            <?php endif; ?>
            
            <div class="login-footer">
                <?php if ($isInitialSetup): ?>
                    <p><i class="fas fa-info-circle"></i> This will be your main administrator account</p>
                <?php else: ?>
                    <a href="<?php echo url('admin/dashboard.php'); ?>"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
                <?php endif; ?>
                <a href="<?php echo url(); ?>"><i class="fas fa-home"></i> Back to Website</a>
            </div>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script>
        // Toggle password visibility
        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        // Password strength meter
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.querySelector('.password-strength-bar');
            const strengthText = document.querySelector('.password-strength-text');
            
            // Remove all classes
            strengthBar.className = 'password-strength-bar';
            
            if (password.length === 0) {
                strengthBar.style.width = '0';
                strengthText.textContent = '';
                return;
            }
            
            // Calculate strength
            let strength = 0;
            let strengthLevel = '';
            
            // Length check
            if (password.length >= 8) strength += 1;
            if (password.length >= 12) strength += 1;
            
            // Complexity checks
            if (/[A-Z]/.test(password)) strength += 1;
            if (/[a-z]/.test(password)) strength += 1;
            if (/[0-9]/.test(password)) strength += 1;
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;
            
            // Set strength level
            const percentage = (strength / 6) * 100;
            strengthBar.style.width = percentage + '%';
            
            if (strength <= 2) {
                strengthBar.classList.add('strength-weak');
                strengthText.textContent = 'Weak';
                strengthText.style.color = '#f44336';
            } else if (strength <= 3) {
                strengthBar.classList.add('strength-fair');
                strengthText.textContent = 'Fair';
                strengthText.style.color = '#ff9800';
            } else if (strength <= 4) {
                strengthBar.classList.add('strength-good');
                strengthText.textContent = 'Good';
                strengthText.style.color = '#2196f3';
            } else {
                strengthBar.classList.add('strength-strong');
                strengthText.textContent = 'Strong';
                strengthText.style.color = '#4caf50';
            }
        });
        
        // Password match indicator
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            const indicator = document.querySelector('.password-match-indicator');
            
            if (confirmPassword.length === 0) {
                indicator.textContent = '';
                return;
            }
            
            if (password === confirmPassword) {
                indicator.textContent = '✓ Passwords match';
                indicator.style.color = '#4caf50';
                indicator.style.fontSize = '0.8rem';
                indicator.style.marginTop = '0.25rem';
            } else {
                indicator.textContent = '✗ Passwords do not match';
                indicator.style.color = '#f44336';
                indicator.style.fontSize = '0.8rem';
                indicator.style.marginTop = '0.25rem';
            }
        });
        
        // Form validation
        document.querySelector('.register-form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
            
            if (password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long!');
                return false;
            }
        });
    </script>
    
    <!-- JavaScript -->
    <script src="<?php echo asset('js/admin.js'); ?>"></script>
</body>
</html>
