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
include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../objects/stove.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();

// initialize objectsm 
$user = new User($db);
$stove = new Stove($db);
 
// get access code
//$user->access_code=isset($_GET['access_code']) ? $_GET['access_code'] : "";
$data = json_decode(file_get_contents('php://input'));

if(isset($data->jwt)){
    $jwt = $data->jwt;
    try{
        //decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        //check if the user email is in database
        if(($user->emailExists($decoded->data->email))&&($decoded->data->access_level == 'superadmin')){
            $stove->imei = $data->imei;
            $stove->id = $data->id;
            //add stove record to the database
            if($stove->delete()){
                http_response_code(200);
                echo json_encode(
                    array("message" => "stove deleted successfully")
                );
            }else{
                http_response_code(500);
                echo json_encode(
                    array("message" => "unable to delete stove")
                );
            }
        }else{
            http_response_code(401);
            echo json_encode(
                array("message" => "access denied: invalid email")
            );
        }
    }catch (exception $e){
        http_response_code(401);
        echo json_encode(
            array(
                "message" => "access denied",
                "error" => $e->getMessage()
            )
        );
    }
}else{
    http_response_code(403);
    echo json_encode(
        array("message" => "error: incomplete request")
    );
}