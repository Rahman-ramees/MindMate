<?php
require_once __DIR__ . '/../PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
$clientName    = trim($_POST['name'] ?? '');
$clientEmail   = trim($_POST['email'] ?? '');
$clientPhone   = trim($_POST['phone'] ?? '');
$date          = trim($_POST['date'] ?? '');
$department    = trim($_POST['department'] ?? '');
$doctor        = trim($_POST['doctor'] ?? '');
$preferredMode = trim($_POST['preferred_mode'] ?? '');
$subject       = trim($_POST['subject'] ?? '');
$message       = trim($_POST['message'] ?? '');

// ===============================
// 3. Basic validation
// ===============================
if (
    empty($clientName) ||
    empty($clientEmail) ||
    empty($clientPhone) ||
    empty($date) ||
    empty($department) ||
    empty($doctor)
) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'All required fields must be filled'
    ]);
    exit;
}

if (!filter_var($clientEmail, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Invalid email address'
    ]);
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

    // Optional display values
    $preferredModeText = !empty($preferredMode) ? $preferredMode : 'Not specified';
    $subjectText       = !empty($subject) ? $subject : 'Not specified';
    $messageText       = !empty($message) ? nl2br(htmlspecialchars($message)) : 'No additional message';

    // Escape output for email safety
    $safeClientName    = htmlspecialchars($clientName);
    $safeClientEmail   = htmlspecialchars($clientEmail);
    $safeClientPhone   = htmlspecialchars($clientPhone);
    $safeDate          = htmlspecialchars($date);
    $safeDepartment    = htmlspecialchars($department);
    $safeDoctor        = htmlspecialchars($doctor);
    $safePreferredMode = htmlspecialchars($preferredModeText);
    $safeSubject       = htmlspecialchars($subjectText);

    // ===============================
    // 5. CLIENT EMAIL
    // ===============================
    $clientEmailBody = "
        <h2>MindMate – Appointment Confirmation</h2>
        <p>Dear <strong>{$safeClientName}</strong>,</p>

        <p>We have received your appointment request successfully.</p>

        <ul>
            <li><strong>Date & Time:</strong> {$safeDate}</li>
            <li><strong>Service:</strong> {$safeDepartment}</li>
            <li><strong>Counselor:</strong> {$safeDoctor}</li>
            <li><strong>Preferred Mode:</strong> {$safePreferredMode}</li>
            <li><strong>Reason for Appointment:</strong> {$safeSubject}</li>
            <li><strong>Phone:</strong> {$safeClientPhone}</li>
            <li><strong>Additional Details:</strong> {$messageText}</li>
        </ul>

        <p>Our team will contact you shortly to confirm the details.</p>
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
        <p><strong>Name:</strong> {$safeClientName}</p>
        <p><strong>Email:</strong> {$safeClientEmail}</p>
        <p><strong>Phone:</strong> {$safeClientPhone}</p>
        <p><strong>Date & Time:</strong> {$safeDate}</p>
        <p><strong>Service:</strong> {$safeDepartment}</p>
        <p><strong>Counselor:</strong> {$safeDoctor}</p>
        <p><strong>Preferred Mode:</strong> {$safePreferredMode}</p>
        <p><strong>Reason for Appointment:</strong> {$safeSubject}</p>
        <p><strong>Additional Details:</strong><br>{$messageText}</p>
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
?>