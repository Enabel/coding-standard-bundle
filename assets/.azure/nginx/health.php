<?php
header('Content-Type: application/json');

$response = [
    "status" => "OK",
    "host" => $_SERVER['HTTP_HOST'],
    "timestamp" => date('c'),
];

echo json_encode($response);
