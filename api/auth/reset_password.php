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
include_once "../config/database.php";
include_once "../objects/user.php";
include_once "../libs/utils.php";
 
// get database connection
$database = new Database();
$db = $database->getConnection();
// initialize objects
$user = new User($db);

//get data from client
$data = json_decode(file_get_contents('php://input'));

// get given access code
if(isset($data->access_code)){
    // check if access code exists
    $user->access_code = $data->access_code;

    if($user->accessCodeExists()){
        // set values to object properties
        $user->password = $data->password1;

        // reset password
        if($user->updatePassword()){
            // set response code
            http_response_code(200);

            // display message: user was created
            echo json_encode(array("message" => "Password was reset."));
        }
        else{
            //set response code
            http_response_code(401);
            echo json_encode(array("message" => "Unable to reset password"));
        }
    }else{
        http_response_code(401);
        echo json_encode(array("message", "unauthorized request"));
    }
}else{
    http_response_code(400);
    echo json_encode(array("message", "no token provided"));
}
// // get given access code
// if(isset($_GET['access_code'])){
//     $access_code = ($_GET['access_code']);
//     $_GET = array();
//     // check if access code exists
//     $user->access_code = $access_code;

//     $user->accessCodeExists() ? header("Location: {$home_url}/verify_password")  : die('Access code not found.');
// }else{
//     $data = json_decode(file_get_contents('php://input'));
//     // set values to object properties
//     $user->password = $data->password;

//     // reset password
//     if($user->updatePassword()){
//         // set response code
//         http_response_code(200);

//         // display message: user was created
//         echo json_encode(array("message" => "Password was reset."));
//     }
//     else{
//         //set response code
//         http_response_code(400);
//         echo json_encode(array("message" => "Unable to reset password"));
//     }
// }