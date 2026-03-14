<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    $name = 'ABDUL RAHMAN';
    $parentsName = 'MOHAMMED KUNHI';
    $address = 'THEKKIL FERRY, KASARAGOD';
    $phone = '9876543210';
    $standard = '7';
    $division = 'A';
    $bloodGroup = 'O+';
    $admissionNo = 'ADM1025';
    $dob = '10-06-2012';

    $mail = new PHPMailer(true);

    // $mail->isSMTP();
    // $mail->Host = 'sandbox.smtp.mailtrap.io';
    // $mail->SMTPAuth = true;
    // $mail->Username = '1e789f2db08f40';
    // $mail->Password = 'd472a998b827a2';
    // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    // $mail->Port = 587;

    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'rhmnramees730@gmail.com';
    $mail->Password   = 'eflfkxkpgasfgcmp'; // ⚠️ App password only
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // very important for live debugging
    $mail->Timeout = 15;
    $mail->SMTPDebug = SMTP::DEBUG_OFF;

    $mail->setFrom('no-reply@example.com', 'ID Card Generator');
    $mail->addAddress('rhmnramees730@gmail.com');
    $mail->addCC('rhmnramees730@gmail.com');

    $mail->isHTML(true);
    $mail->Subject = "Student Details - $name";
    $mail->Body = "
        <h2>Student Details</h2>
        <table border='1' cellpadding='6' cellspacing='0'>
            <tr><td><b>Name</b></td><td>{$name}</td></tr>
            <tr><td><b>Parent</b></td><td>{$parentsName}</td></tr>
            <tr><td><b>Phone</b></td><td>{$phone}</td></tr>
            <tr><td><b>Address</b></td><td>{$address}</td></tr>
            <tr><td><b>Standard</b></td><td>{$standard} {$division}</td></tr>
            <tr><td><b>Admission No</b></td><td>{$admissionNo}</td></tr>
            <tr><td><b>Blood Group</b></td><td>{$bloodGroup}</td></tr>
            <tr><td><b>DOB</b></td><td>{$dob}</td></tr>
        </table>
    ";

    $mail->send();

    echo json_encode([
        'success' => true,
        'message' => 'Email sent successfully.'
    ]);
    exit;

} catch (\Throwable $e) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
    exit;
}