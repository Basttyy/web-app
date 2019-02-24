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
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// initialize objects
$user = new User($db);
$payment = new Payment($db);
$utils = new Utils();
 
// get access code
//$user->access_code=isset($_GET['access_code']) ? $_GET['access_code'] : "";
$data = json_decode(file_get_contents('php://input'));

if(isset($data->user_email)){
    $user->email = $data->email;
    if($user->emailExists()){
        $payment->user_id = $data->user_id;
        $payment->pay_category = $data->pay_category;
        $payment->order_type = $data->order_type;
        $payment->status = "unpaid";                //the payment has not been made

        $payment->trans_id = "trans".substr(md5(uniqid(rand())), 10).$payment->user_id;
        if($data->pay_category == "purch_install"){
            $payment->amount = 1000000;
        }else if($data->pay_category == "purch_full"){
            $payment->amount = 2800000;
        }else if($data->pay_category == "purch_renew"){
            $payment->amount = 400000;
        }
        if($payment->create()){
            http_response_code(200);
            echo json_encode(
                array("message" => "payment record created",
                        "amount" => $payment->amount,
                        "order_id" => $payment->order_id,
                        "order_type" => $payment->order_type,
                        "pay_category" => $payment->pay_category,
                        "firstname" => $user->firstname,
                        "lastname" => $user->lastname,
                        "email" => $payment->email,
                        "status" => $payment->status
                    )
            );
        }else{
            http_response_code(500);
            echo json_encode(
                array("message" => "error creating payment record")
            );
        }
    }else{
        http_response_code(401);
        echo json_encode(
            array("message" => "no account with this email")
        );
    }
}else{
    http_response_code(400);
    echo json_encode(
        array("message" => "email not provided")
    );
}