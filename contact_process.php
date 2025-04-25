<?php
session_start();

// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

require "vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Initialize messages
$_SESSION['errors'] = [];
$_SESSION['success'] = '';

// Validate CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['errors'][] = "Security token mismatch";
    header("Location: contact.php#contact");
    exit;
}

// Sanitize inputs
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

// Validate inputs
if (empty($name)) $_SESSION['errors'][] = "Name is required";
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['errors'][] = "Valid email is required";
}
if (empty($subject)) $_SESSION['errors'][] = "Subject is required";
if (empty($message)) $_SESSION['errors'][] = "Message is required";

// Redirect if errors
if (!empty($_SESSION['errors'])) {
    header("Location: contact.php#contact");
    exit;
}

try {
    $mail = new PHPMailer(true);
    
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'aboderindaniel482@gmail.com'; // Your Gmail
    $mail->Password = 'rhbwpurtuifkzxtz'; // App password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Recipients
    $mail->setFrom('aboderindaniel4@gmail.com', 'Website Contact Form');
    $mail->addAddress('dnlcodes4@email.com', 'Dnlcodes');
    $mail->addReplyTo($email, $name);

    // Content
    $mail->isHTML(true);
    $mail->Subject = "New Contact Form Submission: $subject";
    $mail->Body = "
        <h3>New Contact Form Submission</h3>
        <p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>
        <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
        <p><strong>Subject:</strong> " . htmlspecialchars($subject) . "</p>
        <p><strong>Message:</strong></p>
        <p>" . nl2br(htmlspecialchars($message)) . "</p>
    ";

    $mail->send();
    $_SESSION['success'] = "Your message has been sent successfully!";
} catch (Exception $e) {
    $_SESSION['errors'][] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

header("Location: contact.php#contact");
exit;