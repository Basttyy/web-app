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
$user->status = '0';
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
        //$body.="Please use the following code to verify your email and login: {$user->access_code}";  
        $body.="Please click the following link to verify your email and login:<a href='{$home_url}#verify-account/{$user->access_code}' >Verify Account...</a>";
        $subject="Account Confirmation";

        //send activation link to user
        if($utils->sendEmailViaPhpMail($send_to_email, $subject, $body)){
            //catch errors if client not added to payant
            if(($user->access_level == 'sadmin')||($user->access_level == 'admin')){
                $type = 'Customer';
            }
            else if($user->access_level == 'vendor'){
                $type = 'Vendor';
            }
            else if($user->access_level == 'Customer'){
                $type = 'Customer';
            }
            $Payant = new Payant\Payant('13337b87ee76gew87fg87gfweugf87w7ge78f229c');
            $client_data = ['first_name' => $user->firstname,
                            'last_name' => $user->lastname,
                            'email' => $user->email,
                            'phone' => $user->contact_number,
                            'type' => $type];
                $Payant->addClient($client_data);
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