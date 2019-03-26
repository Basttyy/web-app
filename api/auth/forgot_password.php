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

//includes
// required to decode json web token
include_once '../config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

// core configuration
include_once "../config/core.php"; 
// classes
include_once "../config/database.php";
include_once '../objects/user.php';
include_once "../libs/utils.php";
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// initialize objects
$user = new User($db);
$utils = new Utils();

$data = json_decode(file_get_contents("php://input"));
// check if username and password are in the database
$user->email=$data->email;

if($user->emailExists()){

    // update access code for user
    $access_code = $utils->getToken();

    $user->access_code = $access_code;
    if($user->updateAccessCode()){

        // send reset link
        $body="Hi there.<br /><br />";
        $body.="Please click the following link to reset your password: <a href='http://{$home_url}#reset-password/{$user->access_code}'>Reset Password</a>";
        $subject="Reset Password";
        $send_to_email=$user->email;

        if($utils->sendEmailViaPhpMail($send_to_email, $subject, $body)){
            //set response code
            http_response_code(200);
            //display message email was sent
            echo json_encode(array("message" => "Password reset code sent"));
        }else{//if email sending failed
            //set response code
            http_response_code(500);
            //display message email wasn't sent
            echo json_encode(array("message" => "Unable to send password reset code."));
        }
    }else{//if access code update failed
        //set response code
        http_response_code(400);
        //display message email was sent
        echo json_encode(array("message" => "Unable to update access code."));
    }
}else{//if email does not exist
    //set response code
    http_response_code(404);
    //display message email not exist
    echo json_encode(array("message" => "Your email cannot be found"));
}