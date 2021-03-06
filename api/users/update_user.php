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
 
// required to decode json web token
include_once '../config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
 
// files needed to connect to database
include_once '../config/database.php';
include_once '../objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate user object
$user = new User($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// if jwt is not empty
if(isset($data->jwt)){
    $jwt = $data->jwt;
    // if decode succeed, show user details
    try { 
        // decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        // set user property values
        $user->firstname = $data->firstname;
        $user->lastname = $data->lastname;
        $user->email = $data->email;
        $user->country = $data->update_country;
        $user->state = $data->update_state;
        $user->postal_code = $data->update_postal_code;
        $user->address = $data->address;
        $user->contact_number = $data->contact_number;
        $user->password = $data->password;
        $user->id = $decoded->data->id;
 
        // update the user
        if($user->update()){
            // we need to re-generate jwt because user details might be different
            $token = array(
                "iss" => $iss,
                "aud" => $aud,
                "iat" => $iat,
                "nbf" => $nbf,
                "data" => array(
                    "id" => $user->id,
                    "firstname" => $user->firstname,
                    "lastname" => $user->lastname,
                    "email" => $user->email,
                    "country" => $user->country,
                    "state" => $user->state,
                    "postal_code" => $user->postal_code,
                    "address" => $user->address,
                    "contact_number" => $user->contact_number
                )
            );
            $jwt = JWT::encode($token, $key);            
            // set response code
            http_response_code(200);            
            // response in json format
            echo json_encode(
                    array(
                        "message" => "user was updated.",
                        "jwt" => $jwt
                    )
            );
        }        
        // message if unable to update user
        else{
            // set response code
            http_response_code(401);
        
            // show error message
            echo json_encode(array("message" => "unable to update user"));
        }
    }
    // if decode fails, it means jwt is invalid
    catch (Exception $e){
    
        // set response code
        http_response_code(401);
    
        // show error message
        echo json_encode(array(
            "message" => "access denied.",
            "error" => $e->getMessage()
        ));
    }
}
// show error message if jwt is empty
else{
 
    // set response code
    http_response_code(400);
 
    // tell the user access denied
    echo json_encode(array("message" => "incomplete request parameter"));
}