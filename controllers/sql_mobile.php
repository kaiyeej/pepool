<?php
include '../core/config.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Content-Type: application/json');

$response = array();

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['input'])) {
    $inputs = (array) $data['input'];
} else {
    $inputs = [];
}



$query = $_GET['q'];
$class = $_GET['c'];

$ClassName = new $class;
$ClassName->inputs = $inputs;

$response['data'] = $ClassName->$query();

echo json_encode($response);