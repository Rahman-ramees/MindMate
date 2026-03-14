<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

const ADMIN_EMAIL = 'mindmate.kvr@gmail.com';
const FROM_EMAIL  = 'mindmate.kvr@gmail.com';
const FROM_NAME   = 'MindMate';

// ===============================
// Validate request
// ===============================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Invalid request';
    exit;
}

// ===============================
// Get form values
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
// Validation
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
    echo 'All required fields must be filled';
    exit;
}

if (!filter_var($clientEmail, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo 'Invalid email address';
    exit;
}

// ===============================
// Prepare safe values
// ===============================
$preferredModeText = $preferredMode ?: 'Not specified';
$subjectText       = $subject ?: 'Not specified';
$messageText       = $message ? nl2br(htmlspecialchars($message)) : 'No additional message';

$formattedDate = date('l, d M Y • h:i A', strtotime($date));

$data = [
    'clientName'    => htmlspecialchars($clientName),
    'clientEmail'   => htmlspecialchars($clientEmail),
    'clientPhone'   => htmlspecialchars($clientPhone),
    'date'          => $formattedDate,
    'department'    => htmlspecialchars($department),
    'doctor'        => htmlspecialchars($doctor),
    'preferredMode' => htmlspecialchars($preferredModeText),
    'subject'       => htmlspecialchars($subjectText),
    'message'       => $messageText,
];

// ===============================
// Mailer
// ===============================
function createMailer(): PHPMailer
{
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'rhmnramees730@gmail.com';
    $mail->Password   = 'eflfkxkpgasfgcmp'; // ⚠️ App password only
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // $mail->isHTML(true);

    // $mail = new PHPMailer(true);
    // $mail->isSMTP();
    // $mail->Host       = 'sandbox.smtp.mailtrap.io';
    // $mail->SMTPAuth   = true;
    // $mail->Username   = '1a7b6577b2fd1c';
    // $mail->Password   = '7f9b9f0269bd05'; // ⚠️ App password only
    // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    // $mail->Port       = 587;

    // $mail->setFrom('rhmnramees730@gmail.com', 'MindMate');
    // $mail->isHTML(true);

    // $mail = new PHPMailer(true);
    // $mail->isSMTP();
    // $mail->Host       = 'smtp.gmail.com';
    // $mail->SMTPAuth   = true;
    // $mail->Username   = 'mindmateweb@gmail.com';
    // $mail->Password   = 'fpzzqrnuoxdipyqg'; // ⚠️ App password only
    // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    // $mail->Port       = 587;

    // $mail->setFrom('mindmateweb@gmail.com', 'MindMate');
    // $mail->isHTML(true);

    // $mail = new PHPMailer(true);
    // $mail->isSMTP();
    // $mail->Host       = 'smtp.gmail.com';
    // $mail->SMTPAuth   = true;
    // $mail->Username   = 'mindmate.kvr@gmail.com';
    // $mail->Password   = 'cpnjspxlbvsjikxq';
    // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    // $mail->Port       = 587;
$mail->setFrom(FROM_EMAIL, FROM_NAME);
    $mail->isHTML(true);

    $mail->SMTPDebug = 2;

    $mail->CharSet = 'UTF-8';

    return $mail;
}

// ===============================
// Client Email (YOUR CARD DESIGN)
// ===============================
function getClientEmailBody($data)
{
return "
<!DOCTYPE html>
<html>
<body style='margin:0;background:#f4f7fb;font-family:Arial'>

<table width='100%' style='padding:30px'>
<tr>
<td align='center'>

<table style='max-width:700px;background:#fff;border-radius:16px;box-shadow:0 8px 24px rgba(0,0,0,0.08);overflow:hidden'>

<tr>
<td style='background:linear-gradient(135deg,#260172,#7b88ff);padding:30px;text-align:center'>
<h1 style='color:#fff;margin:0'>MindMate</h1>
<p style='color:#eef1ff;margin:5px 0 0'>Appointment Confirmation</p>
</td>
</tr>

<tr>
<td style='padding:35px'>

<p>Dear <strong>{$data['clientName']}</strong>,</p>

<p>Your appointment request has been received successfully.</p>

<table width='100%' style='border:1px solid #e6ebf5;border-radius:12px;border-collapse:collapse'>

<tr>
<td style='padding:12px;font-weight:600;border-bottom:1px solid #e6ebf5'>Date & Time</td>
<td style='padding:12px;border-bottom:1px solid #e6ebf5'>{$data['date']}</td>
</tr>

<tr>
<td style='padding:12px;font-weight:600;border-bottom:1px solid #e6ebf5'>Service</td>
<td style='padding:12px;border-bottom:1px solid #e6ebf5'>{$data['department']}</td>
</tr>

<tr>
<td style='padding:12px;font-weight:600;border-bottom:1px solid #e6ebf5'>Counselor</td>
<td style='padding:12px;border-bottom:1px solid #e6ebf5'>{$data['doctor']}</td>
</tr>

<tr>
<td style='padding:12px;font-weight:600;border-bottom:1px solid #e6ebf5'>Preferred Mode</td>
<td style='padding:12px;border-bottom:1px solid #e6ebf5'>{$data['preferredMode']}</td>
</tr>

<tr>
<td style='padding:12px;font-weight:600;border-bottom:1px solid #e6ebf5'>Reason</td>
<td style='padding:12px;border-bottom:1px solid #e6ebf5'>{$data['subject']}</td>
</tr>

<tr>
<td style='padding:12px;font-weight:600;border-bottom:1px solid #e6ebf5'>Phone</td>
<td style='padding:12px;border-bottom:1px solid #e6ebf5'>{$data['clientPhone']}</td>
</tr>

<tr>
<td style='padding:12px;font-weight:600'>Message</td>
<td style='padding:12px'>{$data['message']}</td>
</tr>

</table>

<p style='margin-top:25px'>Our team will contact you shortly.</p>

<p><strong>MindMate Team</strong></p>

</td>
</tr>

</table>

</td>
</tr>
</table>

</body>
</html>
";
}

// ===============================
// Admin Email
// ===============================
function getAdminEmailBody($data)
{
return "
<h2>New Appointment Registered</h2>

<p><strong>Name:</strong> {$data['clientName']}</p>
<p><strong>Email:</strong> {$data['clientEmail']}</p>
<p><strong>Phone:</strong> {$data['clientPhone']}</p>
<p><strong>Date:</strong> {$data['date']}</p>
<p><strong>Service:</strong> {$data['department']}</p>
<p><strong>Counselor:</strong> {$data['doctor']}</p>
<p><strong>Preferred Mode:</strong> {$data['preferredMode']}</p>
<p><strong>Reason:</strong> {$data['subject']}</p>
<p><strong>Message:</strong><br>{$data['message']}</p>
";
}

// ===============================
// Send Client Email
// ===============================
function sendClientConfirmation($clientEmail,$data)
{
$mail=createMailer();

$mail->addAddress($clientEmail);

$mail->Subject='Appointment Confirmation - MindMate';

$mail->Body=getClientEmailBody($data);

$mail->AltBody='Your appointment request has been received.';

$mail->send();
}

// ===============================
// Send Admin Email
// ===============================
function sendAdminNotification($data)
{
$mail=createMailer();

$mail->addAddress(ADMIN_EMAIL);

$mail->Subject='New Appointment Registered - MindMate Website';

$mail->Body=getAdminEmailBody($data);

$mail->AltBody='New appointment registered';

$mail->send();
}

// ===============================
// Execute
// ===============================
try {

sendClientConfirmation($clientEmail,$data);

sendAdminNotification($data);

echo 'OK';

exit;

} catch (Exception $e) {

http_response_code(500);

echo 'Mailer Error: '.$e->getMessage();

exit;

}
?>