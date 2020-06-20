<?php

use app\engine\Email;

require_once 'vendor/autoload.php';

$json = file_get_contents("php://input");
$data = json_decode($json);

if(empty($data->email)) {
    die('0 emails received');
}

$email = new Email($data->email);

$response = [
    'count' => $email->checkUniqueness(),
    'email' => $email->email(),
];

if($email->save()) {
    $response['result'] = 'Email saved successfully';
    header("HTTP/1.0 200 Operation successful");
} else {
    $response['result'] = 'Email not saved';
    header("HTTP/1.0 400 Bad Request");
}

header("Content-Type: application/json");
echo json_encode($response);