<?php

header('Content-Type: application/json');

echo json_encode([
    "status" => "success",
    "message" => "appointment.php reached successfully",
    "time" => date("Y-m-d H:i:s")
]);

exit;