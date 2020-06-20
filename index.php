<?php

use app\engine\Db;

require_once 'vendor/autoload.php';

$sql = 'SELECT * FROM emails';
$userData = Db::getInstance()->queryOne($sql);
$json = file_get_contents("php://input");
$data = json_decode($json);
if($data->email) {
    $send = ['received' => $data->email];
}
$response = json_encode($send);
header('Content-Type: application/json');
echo $response;