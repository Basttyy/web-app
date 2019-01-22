<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost/php_api_jwtAuth/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
//includes
include_once '../libs/utils.php';
include_once '../config/core.php';
// files needed to connect to database
include_once '../config/database.php';
include_once '../objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate product object
$user = new User($db);
$utils = new Utils();
 
// get posted data
$data = json_decode(file_get_contents("php://input"));

// set product property values
$user->email = $data->email;
$user->firstname = $data->firstname;
$user->lastname = $data->lastname;
$user->password = $data->password;
$user->contact_number = $data->contact_number;
$user->country = $data->country;
$user->state = $data->state;
$user->postal_code = $data->postal_code;
$user->address = $data->address;
$user->access_level = 'admin';
$user->status = 0;
//access code for email verification
$access_code = $utils->getToken();
$user->access_code = $access_code;
//check if email already exists
if($user->emailExists()){
    // set response code
    http_response_code(409);

    // display message: unable to create user
    echo json_encode(array("message" => "Error! Email already exist"));
}else{
    // create the user
    if($user->create()){
        // send confimation email
        $send_to_email = $user->email;
        $body="Hi {$user->firstname} {$user->lastname}.<br /><br />";
        $body.="Please use the following code to verify your email and login: {$user->access_code}";
        //$body.="Please click the following link to verify your email and login: {$home_url}verify/?access_code={$user->access_code}";
        $subject="Account Confirmation";
    
        if($utils->sendEmailViaPhpMail($send_to_email, $subject, $body)){
            // set response code
            http_response_code(200);
        
            // display message: user was created
            echo json_encode(array("message" => "User was created, Verification link sent"));
        }else{      
            //Todo: add code to delete last inserted user from database
            // set response code
            http_response_code(500);
        
            // display message: user was created
            echo json_encode(array("message" => "Error! unable to send verification link"));
        }
    }
    // message if unable to create user
    else{
    
        // set response code
        http_response_code(400);
    
        // display message: unable to create user
        echo json_encode(array("message" => "Error! Unable to create user."));
    }
}