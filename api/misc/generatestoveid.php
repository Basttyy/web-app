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
// generate json web token
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
 
// include classes
include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../objects/stove_id.php';
include_once '../libs/utils.php';
// include_once '../libs/PayantNG/payant/payant.php';
//require '../vendor/autoload.php';
//use PayantNG\Payant;
 
// get database connection
$database = new Database();
$db = $database->getConnection();

// initialize objectsm 
$user = new User($db);
$stove_id = new Stove_id($db);
$utils = new Utils();

// get access code
//$user->access_code=isset($_GET['access_code']) ? $_GET['access_code'] : "";
$data = json_decode(file_get_contents('php://input'));

if(isset($data->jwt)){
    $jwt = $data->jwt;
    try{
        //decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        //check if the user email is in database
        if(($user->emailExists($decoded->data->email))&&($decoded->data->access_level == 'sadmin')){
            $amount = $data->amount; $i = 0;
            $message = "stove id created successfully           ..........the generated id's are.........";
            $codes = "";
            $prev_amount = $stove_id->countAll();
            $codesTxt = fopen($stoveidtxt, "w") or die ("Unable to open file!");
            if(file_put_contents($stoveidtxt, "Generating Stove ID's Batch ".++$stoveidbatch."    On ".time())){
                while($amount > 0){
                    --$amount; ++$i;
                    $token = $utils->getToken($data->amount);
                    //add stove record to the database
                    if($stove_id->create()){
                        $codes .= $token."   ";
                    }else{
                        http_response_code(500);
                        echo json_encode(
                            array("message" => "unable to create stove id")
                        );
                        break;
                    }
                }
            }
            http_response_code(200);
            echo json_encode(
                array(
                    "message" => $message,
                    "codes" => $codes,
                    "total previous" => $prev_amount,
                    "total generated" => $i,
                    "total codes" => $i + $prev_amount
                    )
            );
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