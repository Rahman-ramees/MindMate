<?php
require_once __DIR__ . '/../PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

try {

    // ===============================
    // Get form data safely
    // ===============================
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? 'New Contact Message');
    $message = trim($_POST['message'] ?? '');

    if (!$name || !$email || !$message) {
        http_response_code(400);
        echo 'Invalid form data';
        exit;
    }

    // ===============================
    // Mail setup
    // ===============================
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'rhmnramees730@gmail.com';   // your email
    $mail->Password   = 'eflfkxkpgasfgcmp';                         // Gmail App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->isHTML(true);
    $mail->setFrom('rhmnramees730@gmail.com', 'MindMate Website');

    // ===============================
    // ADMIN EMAIL (MindMate)
    // ===============================
    $adminBody = "
    <div style='font-family:Arial,sans-serif'>
        <h2>New Contact Message</h2>
        <p><strong>Name:</strong> {$name}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Subject:</strong> {$subject}</p>
        <p><strong>Message:</strong><br>{$message}</p>
    </div>
    ";

    $mail->addAddress('rhmnramees730@gmail.com');
    $mail->Subject = 'New Contact Message – MindMate';
    $mail->Body    = $adminBody;
    $mail->send();

    // ===============================
    // AUTO-REPLY TO USER (Optional but recommended)
    // ===============================
    $mail->clearAddresses();

    $userBody = "
    <div style='font-family:Arial,sans-serif'>
        <h2>Thank you for contacting MindMate</h2>
        <p>Dear <strong>{$name}</strong>,</p>
        <p>We have received your message and will get back to you shortly.</p>

        <p><strong>Your Message:</strong></p>
        <blockquote>{$message}</blockquote>

        <p>Warm regards,<br><strong>MindMate Team</strong></p>
    </div>
    ";

    $mail->addAddress($email);
    $mail->Subject = 'We received your message – MindMate';
    $mail->Body    = $userBody;
    $mail->send();

    // ===============================
    // SUCCESS RESPONSE (IMPORTANT)
    // ===============================
    echo 'OK';
    exit;

} catch (Exception $e) {
    http_response_code(500);
    echo 'Message could not be sent';
}
