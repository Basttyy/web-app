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
    try{
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        $user->email = $decoded->data->email;
        if($user->emailExists()){
            http_response_code(200);
            echo json_encode(
                array(
                    "message" => "get profile success",
                    "firstname" => $user->firstname,
                    "lastname" => $user->lastname,
                    "email" => $user->email,
                    "usertype" => $user->access_level,
                    "country" => $user->country,
                    "state" => $user->state,
                    "postal_code" => $user->postal_code,
                    "contact_number" => $user->contact_number,
                    "address" => $user->address
                )
            );
        }else{
            //set http response header
            http_response_code(401);
            echo json_encode(
                array(
                    "message" => "access denied",
                    "error" => $e->getMessage()
                )
            );
        }
    }
    catch(exception $e){
        //set http response header
        http_response_code(401);
        echo json_encode(
            array(
                "message" => "access denied",
                "error" => $e->getMessage()
            )
        );
    }

}else{//json web token not set
    //set http response header
    http_response_code(400);
    //return error
    echo json_encode(
        array(
            "message" => "incomplete request parameter"
        )
    );
}