<?php
require_once __DIR__ . '/../PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

const ADMIN_EMAIL = 'mindmate.kvr@gmail.com';
const FROM_EMAIL  = 'mindmate.kvr@gmail.com';

const FROM_NAME   = 'MindMate';

// ===============================
// 1. Validate request
// ===============================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error'   => 'Invalid request'
    ]);
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
        'error'   => 'All required fields must be filled'
    ]);
    exit;
}

if (!filter_var($clientEmail, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error'   => 'Invalid email address'
    ]);
    exit;
}

// ===============================
// 4. Prepare safe values
// ===============================
$preferredModeText = $preferredMode !== '' ? $preferredMode : 'Not specified';
$subjectText       = $subject !== '' ? $subject : 'Not specified';
$messageText       = $message !== '' ? nl2br(htmlspecialchars($message)) : 'No additional message';

$data = [
    'clientName'        => htmlspecialchars($clientName),
    'clientEmail'       => htmlspecialchars($clientEmail),
    'clientPhone'       => htmlspecialchars($clientPhone),
    'date'              => htmlspecialchars($date),
    'department'        => htmlspecialchars($department),
    'doctor'            => htmlspecialchars($doctor),
    'preferredMode'     => htmlspecialchars($preferredModeText),
    'subject'           => htmlspecialchars($subjectText),
    'message'           => $messageText,
];

// ===============================
// 5. Mailer factory
// ===============================
function createMailer(): PHPMailer
{
    // $mail = new PHPMailer(true);
    // $mail->isSMTP();
    // $mail->Host       = 'smtp.gmail.com';
    // $mail->SMTPAuth   = true;
    // $mail->Username   = 'rhmnramees730@gmail.com';
    // $mail->Password   = 'eflfkxkpgasfgcmp'; // ⚠️ App password only
    // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    // $mail->Port       = 587;

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

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'mindmate.kvr@gmail.com';
    $mail->Password   = 'cpnjspxlbvsjikxq';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->isHTML(true);

    return $mail;
}
$data['date'] = date('l, d M Y • h:i A', strtotime($data['date']));

// ===============================
// 6. Email body: client
// ===============================
function getClientEmailBody(array $data): string
{
    return "
    <!DOCTYPE html>
    <html>
    <head>
      <meta charset='UTF-8'>
      <title>Appointment Confirmation</title>
    </head>
    <body style='margin:0; padding:0; background-color:#f4f7fb; font-family:Arial, Helvetica, sans-serif; color:#333333;'>
      <table width='100%' cellpadding='0' cellspacing='0' border='0' style='background-color:#f4f7fb; padding:30px 0;'>
        <tr>
          <td align='center'>
            <table width='100%' cellpadding='0' cellspacing='0' border='0' style='max-width:700px; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 8px 24px rgba(0,0,0,0.08);'>
              <tr>
                <td style='background:linear-gradient(135deg, #260172, #7b88ff); padding:30px 40px; text-align:center;'>
                  <h1 style='margin:0; font-size:28px; color:#ffffff; font-weight:700;'>MindMate</h1>
                  <p style='margin:8px 0 0; font-size:15px; color:#eef1ff;'>Appointment Confirmation</p>
                </td>
              </tr>

              <tr>
                <td style='padding:35px 40px 20px;'>
                  <p style='margin:0 0 15px; font-size:16px;'>Dear <strong>{$data['clientName']}</strong>,</p>
                  <p style='margin:0 0 20px; font-size:15px; line-height:1.7; color:#555;'>
                    Thank you for contacting MindMate. Your appointment request has been received successfully.
                    Our team will contact you shortly to confirm the session details.
                  </p>

                  <table width='100%' cellpadding='0' cellspacing='0' border='0' style='background:#f8faff; border:1px solid #e6ebf5; border-radius:12px; overflow:hidden;'>
                    <tr>
                      <td colspan='2' style='padding:16px 20px; background:#eef2ff; font-size:16px; font-weight:700; color:#2d3a8c;'>
                        Appointment Details
                      </td>
                    </tr>
                    <tr>
                      <td style='padding:14px 20px; font-weight:600; width:220px; border-bottom:1px solid #e6ebf5;'>Date & Time</td>
                       <td style='padding:14px 20px; border-bottom:1px solid #e6ebf5;'>{$data['date']}</td>                
                    </tr>
                    <tr>
                      <td style='padding:14px 20px; font-weight:600; border-bottom:1px solid #e6ebf5;'>Service</td>
                      <td style='padding:14px 20px; border-bottom:1px solid #e6ebf5;'>{$data['department']}</td>
                    </tr>
                    <tr>
                      <td style='padding:14px 20px; font-weight:600; border-bottom:1px solid #e6ebf5;'>Counselor</td>
                      <td style='padding:14px 20px; border-bottom:1px solid #e6ebf5;'>{$data['doctor']}</td>
                    </tr>
                    <tr>
                      <td style='padding:14px 20px; font-weight:600; border-bottom:1px solid #e6ebf5;'>Preferred Mode</td>
                      <td style='padding:14px 20px; border-bottom:1px solid #e6ebf5;'>{$data['preferredMode']}</td>
                    </tr>
                    <tr>
                      <td style='padding:14px 20px; font-weight:600; border-bottom:1px solid #e6ebf5;'>Reason for Appointment</td>
                      <td style='padding:14px 20px; border-bottom:1px solid #e6ebf5;'>{$data['subject']}</td>
                    </tr>
                    <tr>
                      <td style='padding:14px 20px; font-weight:600; border-bottom:1px solid #e6ebf5;'>Phone Number</td>
                      <td style='padding:14px 20px; border-bottom:1px solid #e6ebf5;'>{$data['clientPhone']}</td>
                    </tr>
                    <tr>
                      <td style='padding:14px 20px; font-weight:600; vertical-align:top;'>Additional Details</td>
                      <td style='padding:14px 20px;'>{$data['message']}</td>
                    </tr>
                  </table>

                  <p style='margin:24px 0 0; font-size:15px; line-height:1.7; color:#555;'>
                    We appreciate your trust in MindMate and look forward to supporting your well-being.
                  </p>
                </td>
              </tr>

              <tr>
                <td style='padding:20px 40px 35px;'>
                  <p style='margin:0; font-size:15px; color:#333;'>
                    Warm regards,<br>
                    <strong>MindMate Team</strong>
                  </p>
                </td>
              </tr>

              <tr>
                <td style='background:#f8faff; padding:18px 40px; text-align:center; border-top:1px solid #e6ebf5;'>
                  <p style='margin:0; font-size:13px; color:#7a7a7a;'>
                    This is an automated appointment confirmation email from MindMate.
                  </p>
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
// 7. Email body: admin
// ===============================
function getAdminEmailBody(array $data): string
{
    return "
    <!DOCTYPE html>
    <html>
    <head>
      <meta charset='UTF-8'>
      <title>New Appointment Registered</title>
    </head>
    <body style='margin:0; padding:0; background-color:#f4f7fb; font-family:Arial, Helvetica, sans-serif; color:#333333;'>
      <table width='100%' cellpadding='0' cellspacing='0' border='0' style='background-color:#f4f7fb; padding:30px 0;'>
        <tr>
          <td align='center'>
            <table width='100%' cellpadding='0' cellspacing='0' border='0' style='max-width:700px; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 8px 24px rgba(0,0,0,0.08);'>
              <tr>
                <td style='background:linear-gradient(135deg, #260172, #374151); padding:30px 40px; text-align:center;'>
                  <h1 style='margin:0; font-size:28px; color:#ffffff; font-weight:700;'>MindMate Admin</h1>
                  <p style='margin:8px 0 0; font-size:15px; color:#dbe3f0;'>A New Appointment Has Been Registered</p>
                </td>
              </tr>

              <tr>
                <td style='padding:35px 40px 20px;'>
                  <p style='margin:0 0 20px; font-size:15px; line-height:1.7; color:#555;'>
                    A new appointment request has been submitted on the website. Please review the details below and follow up with the client.
                  </p>

                  <table width='100%' cellpadding='0' cellspacing='0' border='0' style='background:#f8faff; border:1px solid #e6ebf5; border-radius:12px; overflow:hidden;'>
                    <tr>
                      <td colspan='2' style='padding:16px 20px; background:#eef2ff; font-size:16px; font-weight:700; color:#2d3a8c;'>
                        Appointment Information
                      </td>
                    </tr>
                    <tr>
                      <td style='padding:14px 20px; font-weight:600; width:220px; border-bottom:1px solid #e6ebf5;'>Client Name</td>
                      <td style='padding:14px 20px; border-bottom:1px solid #e6ebf5;'>{$data['clientName']}</td>
                    </tr>
                    <tr>
                      <td style='padding:14px 20px; font-weight:600; border-bottom:1px solid #e6ebf5;'>Email Address</td>
                      <td style='padding:14px 20px; border-bottom:1px solid #e6ebf5;'>{$data['clientEmail']}</td>
                    </tr>
                    <tr>
                      <td style='padding:14px 20px; font-weight:600; border-bottom:1px solid #e6ebf5;'>Phone Number</td>
                      <td style='padding:14px 20px; border-bottom:1px solid #e6ebf5;'>{$data['clientPhone']}</td>
                    </tr>
                    <tr>
                      <td style='padding:14px 20px; font-weight:600; border-bottom:1px solid #e6ebf5;'>Date & Time</td>
                      <td style='padding:14px 20px; border-bottom:1px solid #e6ebf5;'>{$data['date']}</td>
                    </tr>
                    <tr>
                      <td style='padding:14px 20px; font-weight:600; border-bottom:1px solid #e6ebf5;'>Service</td>
                      <td style='padding:14px 20px; border-bottom:1px solid #e6ebf5;'>{$data['department']}</td>
                    </tr>
                    <tr>
                      <td style='padding:14px 20px; font-weight:600; border-bottom:1px solid #e6ebf5;'>Counselor</td>
                      <td style='padding:14px 20px; border-bottom:1px solid #e6ebf5;'>{$data['doctor']}</td>
                    </tr>
                    <tr>
                      <td style='padding:14px 20px; font-weight:600; border-bottom:1px solid #e6ebf5;'>Preferred Mode</td>
                      <td style='padding:14px 20px; border-bottom:1px solid #e6ebf5;'>{$data['preferredMode']}</td>
                    </tr>
                    <tr>
                      <td style='padding:14px 20px; font-weight:600; border-bottom:1px solid #e6ebf5;'>Reason for Appointment</td>
                      <td style='padding:14px 20px; border-bottom:1px solid #e6ebf5;'>{$data['subject']}</td>
                    </tr>
                    <tr>
                      <td style='padding:14px 20px; font-weight:600; vertical-align:top;'>Additional Details</td>
                      <td style='padding:14px 20px;'>{$data['message']}</td>
                    </tr>
                  </table>
                </td>
              </tr>

              <tr>
                <td style='padding:20px 40px 35px;'>
                  <p style='margin:0; font-size:15px; color:#333;'>
                    Please contact the client and confirm the appointment.
                  </p>
                </td>
              </tr>

              <tr>
                <td style='background:#f8faff; padding:18px 40px; text-align:center; border-top:1px solid #e6ebf5;'>
                  <p style='margin:0; font-size:13px; color:#7a7a7a;'>
                    MindMate Website Appointment Notification
                  </p>
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
// 8. Send email to client
// ===============================
function sendClientConfirmation(string $clientEmail, array $data): void
{
    $mail = createMailer();
    $mail->addAddress($clientEmail);
    $mail->Subject = 'Appointment Confirmation - MindMate';
    $mail->Body    = getClientEmailBody($data);
    $mail->send();
}

// ===============================
// 9. Send email to admin
// ===============================
function sendAdminNotification(array $data): void
{
    $mail = createMailer();
    $mail->addAddress(ADMIN_EMAIL);
    $mail->Subject = 'New Appointment Registered - MindMate Website';
    $mail->Body    = getAdminEmailBody($data);
    $mail->send();
}

// ===============================
// 10. Execute mails
// ===============================
try {
    sendClientConfirmation($clientEmail, $data);
    sendAdminNotification($data);

    echo 'OK';
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'Mail error: ' . $e->getMessage()
    ]);
    exit;
}
?>