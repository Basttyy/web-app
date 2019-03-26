<?php
// required headers
// if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
//     header('Access-Control-Allow-Origin: http://localhost:5500');
//     header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
//     header('Access-Control-Allow-Headers: token, Content-Type');
//     header('Access-Control-Max-Age: 1728000');
//     header('Content-Length: 0');
//     header('Content-Type: text/plain');
//     die();
// }

// header('Access-Control-Allow-Origin: http://localhost:5500');
// header('Content-Type: application/json');

// core configuration
include_once "../config/core.php";
 
// include classes
include_once '../config/database.php';
include_once '../objects/stove.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();

// initialize objects
$stove = new Stove($db);
$payment = new Payment($db);
 
// get access code
//$user->access_code=isset($_GET['access_code']) ? $_GET['access_code'] : "";
$data = json_decode(file_get_contents('php://input'));

if(isset($data->i)){
    $payment->status = $data->status;
    if($payment->updateTransStatus()){
        //$stove->;
        if($stove->update()){

        }else{

        }
    }else{

    }
}else{

}