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
        $body.="Please click the following link to reset your password: {$home_url}#reset-password/{$user->id}/{$user->access_code}";
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