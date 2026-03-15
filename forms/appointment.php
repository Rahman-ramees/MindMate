<?php
header('Content-Type: application/json; charset=utf-8');

try {
    $clientName    = trim($_POST['name'] ?? '');
    $clientEmail   = trim($_POST['email'] ?? '');
    $clientPhone   = trim($_POST['phone'] ?? '');
    $date          = trim($_POST['date'] ?? '');
    $department    = trim($_POST['department'] ?? '');
    $doctor        = trim($_POST['doctor'] ?? '');
    $preferredMode = trim($_POST['preferred_mode'] ?? '');
    $subject       = trim($_POST['subject'] ?? '');
    $message       = trim($_POST['message'] ?? '');

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
    $apiKey = getenv('BREVO_API_KEY');
    $payload = [
        'sender' => [
            'name' => 'Team Mind Mate',
            'email' => 'mindmateweb@gmail.com'
        ],
        'to' => [
            ['email' => $data['clientEmail']]
        ],
        'cc' => [
            ['email' => 'mindmate.kvr@gmail.com']
        ],
        'subject' => "Appointment Confirmation",
        'htmlContent' => "
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
        "
    ];

    $ch = curl_init('https://api.brevo.com/v3/smtp/email');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'accept: application/json',
        'api-key: ' . $apiKey,
        'content-type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($response === false) {
        throw new Exception('cURL Error: ' . curl_error($ch));
    }

    curl_close($ch);

    if ($httpCode < 200 || $httpCode >= 300) {
        throw new Exception("Brevo API failed: HTTP $httpCode - $response");
    }

    // echo json_encode([
    //     'success' => true,
    //     'message' => 'Email sent successfully using Brevo API.',
    //     'response' => json_decode($response, true)
    // ]);
    echo 'OK';
} catch (\Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}