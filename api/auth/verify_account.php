<?php
// required headers
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: http://localhost:5500');
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: token, Content-Type');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    die();
}

header('Access-Control-Allow-Origin: http://localhost:5500');
header('Content-Type: application/json');

// core configuration
include_once "../config/core.php";
 
// include classes
include_once '../config/database.php';
include_once '../objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// initialize objects
$user = new User($db);
 
// get access code
//$user->access_code=isset($_GET['access_code']) ? $_GET['access_code'] : "";
$data = json_decode(file_get_contents('php://input'));

if(isset($data->access_code)){
    $user->access_code = $data->access_code;
    // verify if access code exists
    if(!$user->accessCodeExists()){
        http_response_code(401);
        echo json_encode(
            array("message" => "access code not found")
        );
        //die("ERROR: Access code not found.");
    } 
    // redirect to login
    else{     
        // update status
        $user->status=1;
        $user->updateStatusByAccessCode();
        
        http_response_code(200);
        echo json_encode(
            array("message" => "account verified")
        );
    }
}else{
    http_response_code(400);
    echo json_encode(
        array("message" => "No token provided")
    );
}