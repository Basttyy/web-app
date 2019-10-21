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
// include_once '../libs/PayantNG/payant/payant.php';
require '../vendor/autoload.php';
use PayantNG\Payant;
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// initialize objects
$user = new User($db);
$payment = new Payment($db);
 
// get access code
//$user->access_code=isset($_GET['access_code']) ? $_GET['access_code'] : "";
$data = json_decode(file_get_contents('php://input'));

if(isset($data->jwt)){
    //$jwt = $data->jwt;
    try{
        //$decoded = JWT::decode($jwt, $key, 'HS256');
        $user->email = $data->email;
        if($user->emailExists()){
            //initialize payant api service
            $Payant = new Payant\Payant("cefed6114ffbbbbb6a6d808da17e5f950797fa59dab6010143dd999d", true);
            $payment->email = $user->email;
            $payment->order_type = $data->order_type;
            $payment->status = "unpaid";                //Options: unpaid, pending, success, failed
            $payment->agentid = $user->agentid;
            $payment->adminid = $user->adminid;
            //$payment->trans_id = "trans".substr(md5(uniqid(rand())), 10).$payment->user_id;
            //set the unit cost base on the type of order
            if($data->order_type == "purch_install"){
                $payment->unit_cost = "10000.00";
            }else if($data->order_type == "purch_full"){
                $payment->unit_cost = "28000.00";
            }else if($data->order_type == "purch_renew"){
                $payment->unit_cost = "3000.00";
            }
            $payment->item = 'single burner stove';
            $payment->quantity = $data->quantity;
            $payment->description = 'one burner cooking stove initial purchase';
            if(($user->access_level == 'sadmin')||($user->access_level == 'admin')){
                $type = 'Customer';
            }
            else if($user->access_level == 'vendor'){
                $type = 'Vendor';
            }
            else if($user->access_level == 'customer'){
                $type = 'Customer';
            }
            //client payment data
            $client_data = array(
                'first_name' => $user->firstname,
                'last_name' => $user->lastname,
                'email' => $user->email,
                'phone' => "+234".$user->contact_number,
                'type' => $type
            );
            $payment->due_date = date("d/m/Y", time()+60*60*24*1);  //todo: change time to date format
            $fee_bearer = 'client';
            $items = [array(
                'item' => $payment->item,
                'description' => 'one burner cooking stove initial purchase',
                'unit_cost' => $payment->unit_cost,
                'quantity' => $data->quantity
            )];
            $resp = ($Payant->addInvoice(null, $client_data, $payment->due_date, $fee_bearer, $items));
            if($resp->status == "success"){
                $payment->invoice_ref = $resp->data->reference_code;
                if($payment->create()){
                    http_response_code(200);
                    echo json_encode(
                        array("message" => $resp->message,
                                //"unit_cost" => $payment->unit_cost,
                                "invoice_ref" => $payment->invoice_ref
                                // "order_type" => $payment->order_type,
                                // "firstname" => $user->firstname,
                                // "lastname" => $user->lastname,
                                // "email" => $payment->email,
                                // "status" => $payment->status,
                                // "description" => $payment->description,
                                // "due_date" => $payment->due_date,
                                // "item" => $payment->item,
                                // "quantity" => $payment->quantity
                            )
                    );
                }else{
                    http_response_code(500);
                    echo json_encode(
                        array("message" => "error creating payment record")
                    );
                }
            }else{
                http_response_code(500);
                echo json_encode(
                    array("message" => $resp->message
                            //"due_data" => $due_date,
                            //"client_data" => $client_data,
                            //"fee_bearer" => $fee_bearer,
                            //"items" => $items
                    )   
                );
            }     
        }else{
            http_response_code(401);
            echo json_encode(
                array("message" => "error: access denied")
            );
        }
    }
    catch(exception $e){
        http_response_code(401);
        echo json_encode(array(
            "message" => "access denied",
            "error" => $e->getMessge()
        ));
    }
}else{
    http_response_code(400);
    echo json_encode(
        array("message" => "access denied: incomplete request")
    );
}