<?php
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

header('Content-Type: application/json');

try {

    /*
    |--------------------------------------------------------------------------
    | HARD CODED TEST DATA
    |--------------------------------------------------------------------------
    */

    $name = 'ABDUL RAHMAN';
    $parentsName = 'MOHAMMED KUNHI';
    $address = 'THEKKIL FERRY, KASARAGOD';
    $phone = '9876543210';
    $standard = '7';
    $division = 'A';
    $bloodGroup = 'O+';
    $admissionNo = 'ADM1025';
    $dob = '10-06-2012';

    /*
    |--------------------------------------------------------------------------
    | SEND EMAIL
    |--------------------------------------------------------------------------
    */

    $mail = new PHPMailer(true);

    $mail->SMTPDebug = SMTP::DEBUG_OFF;
    $mail->isSMTP();
    $mail->Host = 'sandbox.smtp.mailtrap.io';
    $mail->SMTPAuth = true;
    $mail->Username = '1e789f2db08f40';
    $mail->Password = 'd472a998b827a2';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Sender
    $mail->setFrom('no-reply@example.com', 'ID Card Generator');

    // Receiver
    $mail->addAddress('makeomaaz13@gmail.com');
    $mail->addCC('rhmnramees730@gmail.com');

    $mail->isHTML(true);

    $mail->Subject = "Student Details - $name";

    $mail->Body = "
        <h2>Student Details</h2>

        <table border='1' cellpadding='6' cellspacing='0'>
            <tr><td><b>Name</b></td><td>$name</td></tr>
            <tr><td><b>Parent</b></td><td>$parentsName</td></tr>
            <tr><td><b>Phone</b></td><td>$phone</td></tr>
            <tr><td><b>Address</b></td><td>$address</td></tr>
            <tr><td><b>Standard</b></td><td>$standard $division</td></tr>
            <tr><td><b>Admission No</b></td><td>$admissionNo</td></tr>
            <tr><td><b>Blood Group</b></td><td>$bloodGroup</td></tr>
            <tr><td><b>DOB</b></td><td>$dob</td></tr>
        </table>
    ";

    $mail->send();

    echo json_encode([
        'success' => true,
        'message' => 'Email sent successfully (no PDF).'
    ]);

} catch (Exception $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}