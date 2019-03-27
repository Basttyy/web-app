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

require_once 'vendor/autoload.php';
use PayantNG\Payant;
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

if(isset($data->jwt)){
    $jwt = $data->jwt;
    try{
        //decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        // set user email property 
        $user->email = $data->email;

        //check if email already exists
        if($user->emailExists()){
            // set response code
            http_response_code(409);

            // display message: unable to create user
            echo json_encode(array("message" => "Error! Email already exist"));
        }else{
            $user->firstname = $data->firstname;
            $user->lastname = $data->lastname;
            $user->password = $data->password;
            $user->contact_number = $data->contact_number;
            $user->country = $data->country;
            $user->state = $data->state;
            $user->postal_code = $data->postal_code;
            $user->address = $data->address;
            $user->status = '0';
            $user->agentid = $decoded->data->email;
            $user->adminid = $decoded->data->agentid;
            //set access level based on parent access level
            switch($decoded->data->access_level){
                case 'sadmin':
                    $user->access_level = 'admin';
                    break;
                case 'admin':
                    $user->access_level = 'vendor';
                    break;
                case 'agent':
                    $user->access_level = 'user';
                    break;
                default:
                    throw new Exception('access_level not specified');
            }
            //access code for email verification
            $access_code = $utils->getToken();
            $user->access_code = $access_code;
            // create the user
            if($user->create()){
                // send confimation email
                $send_to_email = $user->email;
                $body="Hi {$user->firstname} {$user->lastname}.<br /><br />";
                //$body.="Please use the following code to verify your email and login: {$user->access_code}";  
                $body.="Please click the following link to verify your email and login:<a href='{$home_url}#verify-account/{$user->access_code}'>Verify Account...</a>";
                $subject="Account Confirmation";

                //send activation link to user
                if($utils->sendEmailViaPhpMail($send_to_email, $subject, $body)){
                    //catch errors if client not added to payant
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
                http_response_code(500);
            
                // display message: unable to create user
                echo json_encode(array("message" => "Error! Unable to create user."));
            }
        }
    }
    catch(exception $e){
        //set response code
        http_response_code(401);
        //display message: unable to authenticate
        echo json_encode(array("message" => "Error! access denied"));
    }
}else{
    //set response code
    http_response_code(400);
    //display message: bad request
    echo json_encode(array("message" => "Error! incomplete request"));
}

// $username = ($_POST['email']);
// echo($_FILES);

// uploadAvatar($username);

// function uploadAvatar($username){
//     if($_FILES['avatar']['tmp_name']){
//         //files in the temporary location
//         $temp_file = $_FILES['avatar']['tmp_name'];
//         $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION); //get uploaded image extension
//         $filename = $username.md5(microtime()).".{$ext}";       //concatenate a random no and file ext to username

//         $path = "app/assets/img/profilephotos/{$filename}";     // uploads/username.jpg
//         move_uploaded_file($temp_file, $path);
//     }
//     return $path;
// }