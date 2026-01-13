<?php
require_once __DIR__ . '/../PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// header('Content-Type: application/json');

// ===============================
// 1. Validate request
// ===============================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit;
}

// ===============================
// 2. Get form values safely
// ===============================
$clientName  = trim($_POST['name'] ?? '');
$clientEmail = trim($_POST['email'] ?? '');
$clientPhone = trim($_POST['phone'] ?? '');
$date        = trim($_POST['date'] ?? '');
$department  = trim($_POST['department'] ?? '');
$doctor      = trim($_POST['doctor'] ?? '');
$message     = trim($_POST['message'] ?? '');

// ===============================
// 3. Basic validation
// ===============================
if (
    empty($clientName) ||
    empty($clientEmail) ||
    empty($clientPhone) ||
    empty($date)
) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'All required fields must be filled']);
    exit;
}

if (!filter_var($clientEmail, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid email address']);
    exit;
}

try {
    // ===============================
    // 4. Mail configuration
    // ===============================
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'rhmnramees730@gmail.com';
    $mail->Password   = 'eflfkxkpgasfgcmp'; // ⚠️ App password only
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('rhmnramees730@gmail.com', 'MindMate');
    $mail->isHTML(true);

    // ===============================
    // 5. CLIENT EMAIL
    // ===============================
    $clientEmailBody = "
        <h2>MindMate – Appointment Confirmation</h2>
        <p>Dear <strong>{$clientName}</strong>,</p>

        <p>We have received your appointment request.</p>

        <ul>
            <li><strong>Date & Time:</strong> {$date}</li>
            <li><strong>Service:</strong> {$department}</li>
            <li><strong>Specialist:</strong> {$doctor}</li>
            <li><strong>Phone:</strong> {$clientPhone}</li>
            <li><strong>Message:</strong> {$message}</li>
        </ul>

        <p>Our team will contact you shortly.</p>
        <p><strong>– MindMate Team</strong></p>
    ";

    $mail->clearAddresses();
    $mail->addAddress($clientEmail);
    $mail->Subject = 'Appointment Request Received – MindMate';
    $mail->Body    = $clientEmailBody;
    $mail->send();

    // ===============================
    // 6. ADMIN EMAIL
    // ===============================
    $adminEmailBody = "
        <h2>New Appointment Request</h2>
        <p><strong>Name:</strong> {$clientName}</p>
        <p><strong>Email:</strong> {$clientEmail}</p>
        <p><strong>Phone:</strong> {$clientPhone}</p>
        <p><strong>Date:</strong> {$date}</p>
        <p><strong>Service:</strong> {$department}</p>
        <p><strong>Specialist:</strong> {$doctor}</p>
        <p><strong>Message:</strong> {$message}</p>
    ";

    $mail->clearAddresses();
    $mail->addAddress('rhmnramees730@gmail.com');
    $mail->Subject = 'New Appointment – MindMate Website';
    $mail->Body    = $adminEmailBody;
    $mail->send();

    // ===============================
    // 7. Success response
    // ===============================
    echo 'OK';
    exit;


} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Mail error: ' . $e->getMessage()
    ]);
}
