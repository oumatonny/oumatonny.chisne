<?php
// For testing only. Do not use plain credentials in production.
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$admin_email = 'admin@taskmentor.chisne.co.ke';
$admin_password = 'Pisaboke#2025';
$host = 'mail.chisne.co.ke'; // Change if needed
$port = 587; // Change if needed

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = trim($_POST['name'] ?? '');
    $user_email = trim($_POST['email'] ?? '');
    $user_message = trim($_POST['message'] ?? '');

    if ($user_name && filter_var($user_email, FILTER_VALIDATE_EMAIL) && $user_message) {
        $mail = new PHPMailer(true);
        try {
            // Send to admin
            $mail->isSMTP();
            $mail->Host = $host;
            $mail->SMTPAuth = true;
            $mail->Username = $admin_email;
            $mail->Password = $admin_password;
            $mail->SMTPSecure = 'tls';
            $mail->Port = $port;
            $mail->SMTPSecure = false;
            $mail->SMTPAutoTLS = false;

            $mail->setFrom($admin_email, 'TaskMentor Contact');
            $mail->addAddress($admin_email);
            $mail->addReplyTo($user_email, $user_name);

            $mail->Subject = 'New Contact Form Submission';
            $mail->Body = "Name: $user_name\nEmail: $user_email\nMessage:\n$user_message";

            $mail->send();

            // Auto-reply to user
            $mail2 = new PHPMailer(true);
            $mail2->isSMTP();
            $mail2->Host = $host;
            $mail2->SMTPAuth = true;
            $mail2->Username = $admin_email;
            $mail2->Password = $admin_password;
            $mail2->SMTPSecure = 'tls';
            $mail2->Port = $port;


            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ];
            // ...existing code...
            $mail2->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ];

            $mail2->setFrom($admin_email, 'TaskMentor');
            $mail2->addAddress($user_email, $user_name);

            $mail2->Subject = 'Thank you for contacting TaskMentor';
            $mail2->Body = "Dear $user_name,\n\nThank you for reaching out to us. We have received your message and will get back to you soon.\n\nBest regards,\nTaskMentor Team";

            $mail2->send();

            $success = 'Your message has been sent successfully. Please check your email for confirmation.';
        } catch (Exception $e) {
            $error = 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
        }
    } else {
        $error = 'Please fill in all fields with valid information.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us - TaskMentor</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .form-container { max-width: 400px; margin: auto; }
        .success { color: green; }
        .error { color: red; }
        label { display: block; margin-top: 15px; }
        input, textarea { width: 100%; padding: 8px; margin-top: 5px; }
        button { margin-top: 15px; padding: 10px 20px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Contact Us</h2>
        <?php if ($success): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php elseif ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

            <label for="message">Message:</label>
            <textarea name="message" id="message" rows="5" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>

            <button type="submit">Send Message</button>
        </form>
    </div>
</body>
</html>