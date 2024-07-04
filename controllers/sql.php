<?php
include '../core/config.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

$response = array();

if (isset($_POST['input'])) {
    $inputs = $_POST['input'];
} else {
    $inputs = "";
}

$query = $_GET['q'];
$class = $_GET['c'];

$ClassName = new $class;
$ClassName->inputs = $inputs;

$response['data'] = $ClassName->$query();

echo json_encode($response);
