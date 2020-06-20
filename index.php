<?php

use app\engine\Db;
use app\engine\Email;

require_once 'vendor/autoload.php';

$json = file_get_contents("php://input");
$data = json_decode($json);
if(!$data->email) {
    die('0 emails received');
}
$email = new Email($data->email);
if($email->save()) {
    $data = json_encode([
        'count' => $email->checkUniqueness(),
        'email' => $email->email(),
        'result' => 'Email saved successfully'
    ]);
    header("HTTP/1.0 200 Operation successful");
} else {
    $data = json_encode([
        'count' => $email->checkUniqueness(),
        'email' => $email->email(),
        'result' => 'Email not saved'
    ]);
    header("HTTP/1.0 400 Bad Request");
}

//header("Content-Type: application/json");
$response = json_encode($data);
echo $response;