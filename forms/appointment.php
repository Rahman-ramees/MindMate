<?php
header('Content-Type: application/json; charset=utf-8');

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

    $apiKey = getenv('BREVO_API_KEY');


    $payload = [
        'sender' => [
            'name' => 'ID Card Generator',
            'email' => 'mindmateweb@gmail.com'
        ],
        'to' => [
            ['email' => 'rhmnramees730@gmail.com']
        ],
        'cc' => [
            ['email' => 'rhmnramees730@gmail.com']
        ],
        'subject' => "Student Details - $name",
        'htmlContent' => "
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

    echo json_encode([
        'success' => true,
        'message' => 'Email sent successfully using Brevo API.',
        'response' => json_decode($response, true)
    ]);
} catch (\Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}