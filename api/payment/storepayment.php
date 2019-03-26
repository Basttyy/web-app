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

// core configuration
include_once "../config/core.php";
 
// include classes
include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../objects/payments.php';
include_once '../objects/stove.php';
// include_once '../libs/PayantNG/payant/payant.php';
//require '../vendor/autoload.php';
//use PayantNG\Payant;
 
// get database connection
$database = new Database();
$db = $database->getConnection();

// initialize objects
$user = new User($db);
$stove = new Stove($db);
$payment = new Payment($db);
 
// get access code
//$user->access_code=isset($_GET['access_code']) ? $_GET['access_code'] : "";
$data = json_decode(file_get_contents('php://input'));
$jwt = isset($data->jwt) ? $data->jwt : '';

//make sure the invoce data is set
if(isset($data->invoice_ref)){
    try{
        //decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        $payment->status = $data->status;
        $payment->invoice_ref = $data->invoice_ref;
        //update transaction status to success
        if($payment->updateTransStatus()){
            if($data->paytype == 'purchase'){
                //get next available stove to be paired to user
                if($stove->getNextPair()){
                    $stove->owned = 1;
                    $stove->email = $data->email;
                    $stove->payment_status = $data->status;
                    $stove->payment_time = DateTime('d/m/Y', microtime);
                    $stove->purchase_time = DateTime('d/m/Y:h/i/s', microtime);
                    //pair stove with a user
                    if($stove->pairUser()){
                        //set http response header
                        http_response_code(200);
                        echo json_encode(
                            array(
                                "message" => "success: stove paired with user",
                                "stovename" => $stove->name
                            )
                        );
                    }else{
                        //set http response header
                        http_response_code(500);
                        echo json_encode(
                            array(
                                "message" => "couldn't pair with a stove"
                            )
                        );
                    }
                }else{
                    //set http response header
                    http_response_code(500);
                    echo json_encode(
                        array(
                            "message" => "couldn't pair with a stove"
                        )
                    );
                }
            }
            else if($data->paytype == 'renew'){
                //set http response header
                http_response_code(200);
                echo json_encode(
                    array(
                        "message" => "payment renewed success",
                        "stovename" => $stove->name
                    )
                );
            }
        }else{
            //set http response header
            http_response_code(500);
            echo json_encode(
                array(
                    "message" => "couldn't make payment"
                )
            );
        }
    }catch(exception $e){
        //set http response header
        http_response_code(401);
        echo json_encode(
            array(
                "message" => "access denied",
                "error" => $e->getMessage()
            )
        );
    }
}else{
    //set http response header
    http_response_code(403);
    echo json_encode(
        array(
            "message" => "access denied: incomplete request"
        )
    );
}