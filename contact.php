<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/security.php';

$currentPage = 'contact';
$pageTitle = 'Contact Me';
$pageDescription = 'Get in touch with Ouma Tonny for collaborations, projects, or inquiries';

// Get personal info
$personalInfo = getPersonalInfo();

$success = false;
$error = '';

// Process contact form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check CSRF token
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid form submission';
    } else {
        // Get form data
        $name = clean($_POST['name'] ?? '');
        $email = clean($_POST['email'] ?? '');
        $subject = clean($_POST['subject'] ?? '');
        $message = clean($_POST['message'] ?? '');
        
        // Validate form data
        if (empty($name) || empty($email) || empty($message)) {
            $error = 'Please fill in all required fields';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address';
        } else {
            // Send email
            $to = ADMIN_EMAIL;
            $subject = "Contact Form: " . ($subject ?: "New Message");
            $emailMessage = "Name: $name\n";
            $emailMessage .= "Email: $email\n\n";
            $emailMessage .= "Message:\n$message";
            $headers = "From: $email";
            
            if (mail($to, $subject, $emailMessage, $headers)) {
                $success = true;
            } else {
                $error = 'Failed to send message. Please try again later.';
            }
        }
    }
}

// Generate CSRF token
$csrfToken = generateCSRFToken();

include 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>Contact Me</h1>
        <p>Get in touch for collaborations or inquiries</p>
    </div>
</section>

<section class="contact-section">
    <div class="container">
        <div class="contact-content">
            <div class="contact-info">
                <div class="contact-info-card">
                    <h2>Let's Connect</h2>
                    <p>Feel free to reach out if you have any questions, project ideas, or just want to say hello. I'm always open to discussing new opportunities and collaborations.</p>
                    
                    <div class="contact-details">
                        <?php if (isset($personalInfo['email']) && !empty($personalInfo['email'])): ?>
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="contact-text">
                                    <h3>Email</h3>
                                    <p><a href="mailto:<?php echo $personalInfo['email']; ?>"><?php echo $personalInfo['email']; ?></a></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($personalInfo['phone']) && !empty($personalInfo['phone'])): ?>
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="contact-text">
                                    <h3>Phone</h3>
                                    <p><a href="tel:<?php echo $personalInfo['phone']; ?>"><?php echo $personalInfo['phone']; ?></a></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($personalInfo['location']) && !empty($personalInfo['location'])): ?>
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="contact-text">
                                    <h3>Location</h3>
                                    <p><?php echo $personalInfo['location']; ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="social-links">
                        <?php if (isset($personalInfo['github_url']) && !empty($personalInfo['github_url'])): ?>
                            <a href="<?php echo $personalInfo['github_url']; ?>" target="_blank" class="social-link">
                                <i class="fab fa-github"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php if (isset($personalInfo['linkedin_url']) && !empty($personalInfo['linkedin_url'])): ?>
                            <a href="<?php echo $personalInfo['linkedin_url']; ?>" target="_blank" class="social-link">
                                <i class="fab fa-linkedin"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php if (isset($personalInfo['twitter_url']) && !empty($personalInfo['twitter_url'])): ?>
                            <a href="<?php echo $personalInfo['twitter_url']; ?>" target="_blank" class="social-link">
                                <i class="fab fa-twitter"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="contact-form-container">
                <?php if ($success): ?>
                    <div class="success-message">
                        <i class="fas fa-check-circle"></i>
                        <h3>Message Sent!</h3>
                        <p>Thank you for reaching out. I'll get back to you as soon as possible.</p>
                        <button class="btn btn-primary" onclick="resetForm()">Send Another Message</button>
                    </div>
                <?php else: ?>
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="post" action="" class="contact-form" id="contactForm">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                        
                        <div class="form-group">
                            <label for="name">Name *</label>
                            <input type="text" id="name" name="name" required value="<?php echo isset($_POST['name']) ? escapeHTML($_POST['name']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? escapeHTML($_POST['email']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" value="<?php echo isset($_POST['subject']) ? escapeHTML($_POST['subject']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea id="message" name="message" rows="6" required><?php echo isset($_POST['message']) ? escapeHTML($_POST['message']) : ''; ?></textarea>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<style>
.page-header {
    background: var(--primary-gradient, linear-gradient(135deg, #1a1a1a, #2c3e50));
    color: white;
    padding: 4rem 0;
    text-align: center;
    margin-bottom: 3rem;
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="2"/></svg>') center/cover;
    opacity: 0.1;
    animation: rotate 20s linear infinite;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.page-header h1 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    animation: fadeInUp 0.8s ease-out;
}

.page-header p {
    animation: fadeInUp 0.8s ease-out 0.2s backwards;
}

.contact-section {
    padding: 2rem 0 4rem;
    background: var(--bg-color, #f8f9fa);
}

.contact-content {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 3rem;
    max-width: 1200px;
    margin: 0 auto;
}

.contact-info-card {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    animation: fadeInLeft 0.8s ease-out;
}

.contact-info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

.contact-info h2 {
    color: var(--text-color, #2c3e50);
    margin-bottom: 1rem;
    font-size: 1.8rem;
}

.contact-details {
    margin: 2rem 0;
}

.contact-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    transition: transform 0.3s ease;
}

.contact-item:hover {
    transform: translateX(5px);
}

.contact-icon {
    background: var(--primary-color, #2c3e50);
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    color: white;
    transition: transform 0.3s ease, background-color 0.3s ease;
}

.contact-item:hover .contact-icon {
    transform: scale(1.1);
    background: var(--accent-color, #3498db);
}

.contact-text h3 {
    font-size: 1.1rem;
    color: var(--text-color, #2c3e50);
    margin-bottom: 0.25rem;
}

.contact-text p {
    color: var(--text-secondary, #666);
    margin: 0;
}

.contact-text a {
    color: var(--accent-color, #3498db);
    text-decoration: none;
    transition: color 0.3s ease;
}

.contact-text a:hover {
    color: var(--primary-color, #2c3e50);
}

.social-links {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
}

.social-link {
    background: var(--bg-color, #f8f9fa);
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-color, #2c3e50);
    text-decoration: none;
    transition: all 0.3s ease;
    border: 2px solid var(--border-color, #eee);
}

.social-link:hover {
    background: var(--primary-color, #2c3e50);
    color: white;
    transform: translateY(-3px);
    border-color: var(--primary-color, #2c3e50);
}

.contact-form-container {
    background: white;
    padding: 2.5rem;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    animation: fadeInRight 0.8s ease-out;
}

.contact-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-group label {
    color: var(--text-color, #2c3e50);
    font-weight: 500;
    font-size: 0.95rem;
}

.form-group input,
.form-group textarea {
    padding: 0.85rem;
    border: 2px solid var(--border-color, #eee);
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: var(--bg-color, #f8f9fa);
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: var(--accent-color, #3498db);
    outline: none;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    background: white;
}

.form-actions {
    margin-top: 1.5rem;
}

.btn-primary {
    background: var(--primary-color, #2c3e50);
    color: white;
    padding: 0.85rem 2rem;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 500;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-primary:hover {
    background: var(--accent-color, #3498db);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
}

.success-message {
    text-align: center;
    padding: 2rem;
    animation: fadeIn 0.5s ease-out;
}

.success-message i {
    font-size: 3.5rem;
    color: var(--success-color, #2ecc71);
    margin-bottom: 1rem;
    animation: scaleIn 0.5s ease-out;
}

.alert-danger {
    background: var(--error-bg, #fee);
    color: var(--error-color, #c00);
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    border-left: 4px solid var(--error-color, #c00);
    animation: shake 0.5s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInLeft {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes fadeInRight {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes scaleIn {
    from {
        transform: scale(0);
    }
    to {
        transform: scale(1);
    }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

@media (max-width: 768px) {
    .contact-content {
        grid-template-columns: 1fr;
    }
    
    .contact-info-card,
    .contact-form-container {
        padding: 1.5rem;
    }
    
    .page-header {
        padding: 3rem 0;
    }
    
    .page-header h1 {
        font-size: 2rem;
    }
}
</style>

<script>
    function resetForm() {
        document.querySelector('.success-message').style.display = 'none';
        document.getElementById('contactForm').style.display = 'block';
        document.getElementById('contactForm').reset();
    }
</script>

<?php include 'includes/footer.php'; ?>
