<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// core configuration
include_once "config/core.php";
// include classes
include_once "config/database.php";
include_once "objects/user.php";
include_once "libs/utils.php";
 
// get database connection
$database = new Database();
$db = $database->getConnection();
// initialize objects
$user = new User($db);

// get given access code
if(isset($_GET['access_code'])){
    $access_code = ($_GET['access_code']);
    $_GET = array();
    // check if access code exists
    $user->access_code = $access_code;

    $user->accessCodeExists() ? header("Location: {$home_url}/verify_password")  : die('Access code not found.');
}else{
    $data = json_decode(file_get_contents('php://input'));
    // set values to object properties
    $user->password = $data->password;

    // reset password
    if($user->updatePassword()){
        // set response code
        http_response_code(200);

        // display message: user was created
        echo json_encode(array("message" => "Password was reset."));
    }
    else{
        //set response code
        http_response_code(400);
        echo json_encode(array("message" => "Unable to reset password"));
    }
}