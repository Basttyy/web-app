<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
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

if($data->access-code){
    $user->access_code = $data->access_code;
    // verify if access code exists
    if(!$user->accessCodeExists()){
        http_response_code(401);
        return json_encode(
            array("message" => "access code not found")
        );
        //die("ERROR: Access code not found.");
    } 
    // redirect to login
    else{     
        // update status
        $user->status='1';
        $user->updateStatusByAccessCode();
        
        http_response_code(200);
        return json_encode(
            array("message" => "account verified")
        );
    }
}else{
    http_response_code(400);
    return json_encode(
        array("message" => "No token provided")
    );
}