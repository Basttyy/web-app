<?php
// required headers
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: http://localhost:8080');
    header('Access-Control-Allow-Methods: POST, GET');
    header('Access-Control-Allow-Headers: token, Content-Type');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    die();
}

header('Access-Control-Allow-Origin: http://localhost:8080');
header('Content-Type: application/json');

// files needed to connect to database
include_once '../config/database.php';
include_once '../objects/stove.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate user object
$stove = new Stove($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));


try{
    if($data->access_key == $access_key){
        if($stove->getPaymentStatus){
            //set http response header
            http_response_code(400);
            echo json_encode(
                array(
                    "message" => $stove->paidstatus
                )
            );
        }else{
            //set http response header
            http_response_code(401);
            echo json_encode(
                array(
                    "message" => "access denied"
                )
            );
        }
    }else{
            //set http response header
        http_response_code(400);
        //return error
        echo json_encode(
            array(
                "message" => "incomplete request parameter"
            )
        );
    }
}
catch(exception $e){
    //set http response header
    http_response_code(500);
    echo json_encode(
        array(
            "message" => "internal server error",
            "error" => $e->getMessage()
        )
    );
}