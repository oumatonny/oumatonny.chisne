<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/security.php';
require_once 'mailing/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$currentPage = 'contact';
$pageTitle = 'Contact Me';
$pageDescription = 'Get in touch with Ouma Tonny for collaborations, projects, or inquiries';

// Get personal info
$personalInfo = getPersonalInfo();

$success = false;
$error = '';

// SMTP Configuration
$smtp_host = 'mail.chisne.co.ke';
$smtp_username = 'oumatonny@chisne.co.ke';
$smtp_password = 'Tonny#2025';
$smtp_port = 587;
$admin_email = 'oumatonny@chisne.co.ke';

// Process contact form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check CSRF token
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid form submission. Please try again.';
    } else {
        // Get form data
        $name = clean($_POST['name'] ?? '');
        $email = clean($_POST['email'] ?? '');
        $subject = clean($_POST['subject'] ?? 'New Message');
        $message = clean($_POST['message'] ?? '');
        
        // Validate form data
        if (empty($name) || empty($email) || empty($message)) {
            $error = 'Please fill in all required fields';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address';
        } else {
            try {
                // Send email to admin
                $mail = new PHPMailer(true);
                
                // Server settings
                $mail->isSMTP();
                $mail->Host = $smtp_host;
                $mail->SMTPAuth = true;
                $mail->Username = $smtp_username;
                $mail->Password = $smtp_password;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = $smtp_port;
                
                // Disable SSL verification (for local/testing only)
                $mail->SMTPOptions = [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ]
                ];
                
                // Recipients
                $mail->setFrom($smtp_username, 'Website Contact Form');
                $mail->addAddress($admin_email, 'Ouma Tonny');
                $mail->addReplyTo($email, $name);
                
                // Content
                $mail->isHTML(true);
                $mail->Subject = "Contact Form: $subject";
                $mail->Body = "
                    <h2>New Contact Form Submission</h2>
                    <p><strong>Name:</strong> $name</p>
                    <p><strong>Email:</strong> $email</p>
                    <p><strong>Subject:</strong> $subject</p>
                    <p><strong>Message:</strong></p>
                    <p>" . nl2br(htmlspecialchars($message)) . "</p>";
                $mail->AltBody = "Name: $name\nEmail: $email\nSubject: $subject\n\nMessage:\n$message";
                
                $mail->send();
                
                // Send confirmation email to user
                $userMail = new PHPMailer(true);
                $userMail->isSMTP();
                $userMail->Host = $smtp_host;
                $userMail->SMTPAuth = true;
                $userMail->Username = $smtp_username;
                $userMail->Password = $smtp_password;
                $userMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $userMail->Port = $smtp_port;
                $userMail->SMTPOptions = [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ]
                ];
                
                $userMail->setFrom($smtp_username, 'Ouma Tonny');
                $userMail->addAddress($email, $name);
                
                $userMail->isHTML(true);
                $userMail->Subject = "Thank you for contacting Ouma Tonny";
                $userMail->Body = "
                    <h2>Thank you for your message, $name!</h2>
                    <p>I have received your message and will get back to you as soon as possible.</p>
                    <p><strong>Your Message:</strong></p>
                    <p>" . nl2br(htmlspecialchars($message)) . "</p>
                    <p>Best regards,<br>Ouma Tonny</p>";
                $userMail->AltBody = "Thank you for your message, $name!\n\nI have received your message and will get back to you as soon as possible.\n\nYour Message:\n$message\n\nBest regards,\nOuma Tonny";
                
                $userMail->send();
                
                $success = true;
                
            } catch (Exception $e) {
                error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
                $error = 'Failed to send message. Please try again later or contact me directly at ' . $admin_email;
            }
        }
    }
}

// Generate CSRF token
$csrfToken = generateCSRFToken();

include 'includes/header.php';
?>

<section class="hero-section mb-5">
    <div class="container">
        <div class="hero-content-wrapper" style="min-height: 30vh;">
            <div class="hero-text">
                <h1 class="animated-text">
                    <span class="name text-center">Contact Me</span>
                </h1>
                <p class="dynamic-description" id="dynamicDescription" style="max-width: 600px; text-align: center;">Have a project, a question, or just want to connect? I'd love to hear from you.</p>
                <div class="hero-buttons animated-text-delay-3">
                    <a href="mailto:<?php echo isset($personalInfo['email']) ? $personalInfo['email'] : 'ouatonny8@gmail.com'; ?>" class="btn btn-primary">Email Me</a>
                    <a href="<?php echo BASE_PATH; ?>/portfolio/" class="btn btn-outline">View My Work</a>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-background">
        <div class="animated-bg"></div>
    </div>
</section>

<section class="contact-section">
    <div class="container">
        <div class="contact-content">
            <div class="contact-info">
                <div class="contact-info-card">
                    <h2 style="color: blue;">Let's Connect</h2>
                    <p style="color: black;">Feel free to reach out if you have any questions, project ideas, or just want to say hello. I'm always open to discussing new opportunities and collaborations.</p>
                    
                    <div class="contact-details">
                        <?php if (isset($personalInfo['email']) && !empty($personalInfo['email'])): ?>
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="contact-text">
                                    <h3 style="color: black;" >Email</h3>
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
                                    <h3 style="color: black;">Phone</h3>
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
                                    <h3 style="color: black;">Location</h3>
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
                        <h3 style="color: green;">Message Sent!</h3>
                        <p style="color: green;">Thank you for reaching out. I'll get back to you as soon as possible.</p>
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
    background: linear-gradient(145deg, #ffffff, #f8f9fa);
    padding: 2.5rem;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
    animation: fadeInLeft 0.8s ease-out;
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.contact-info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
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
    background: rgba(255, 255, 255, 0.7);
    padding: 1rem;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.contact-item:hover {
    background: rgba(255, 255, 255, 0.9);
    transform: translateX(5px);
}

.contact-icon {
    background: linear-gradient(135deg, #6e45e2, #88d3ce);
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    flex-shrink: 0;
    box-shadow: 0 4px 10px rgba(110, 69, 226, 0.3);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.contact-icon:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 15px rgba(110, 69, 226, 0.4);
    background: var(--accent-color, #3498db);
}

.contact-text h3 {
    font-size: 1.1rem;
    color: var(--text-color,rgb(8, 19, 30));
    margin-bottom: 0.25rem;
}

.contact-text p {
    color: var(--text-secondary, #666);
    margin: 0;
}

.contact-text a {
    color: var(--accent-color,rgb(6, 20, 30));
    text-decoration: none;
    transition: color 0.3s ease;
}

.contact-text a:hover {
    color: var(--primary-color,rgb(0, 35, 70));
}

.social-links {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
}

.social-link {
    background: var(--bg-color,rgb(44, 3, 56));
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
    background: linear-gradient(145deg, #ffffff, #f8f9fa);
    padding: 2.5rem;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    animation: fadeInRight 0.8s ease-out;
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.contact-form-container:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
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
    color: #333;
    font-weight: 600;
    font-size: 0.95rem;
    margin-bottom: 0.5rem;
}

.form-group input,
.form-group textarea {
    padding: 0.85rem 1rem;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #ffffff;
    color: #333;
    width: 100%;
    box-sizing: border-box;
}

.form-group input::placeholder,
.form-group textarea::placeholder {
    color: #999;
    opacity: 1;
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: #3498db;
    outline: none;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
    background: #ffffff;
    color: #000;
}

/* Ensure text is visible in all states */
.form-group input,
.form-group textarea,
.form-group input:focus,
.form-group textarea:focus {
    color: #333;
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
