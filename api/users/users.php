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
    // if decode succeed, get the users list
    try {
        // decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        //query users record
        $stmt = $user->readAll;
        $num = $stmt->rowCount();
        if($num > 0){
            // products array
            $users_arr=array();
            $users_arr["records"]=array();
            if($decoded->data->access_level == "superadmin"){            
                // retrieve our table contents
                // fetch() is faster than fetchAll()
                // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    // extract row
                    // this will make $row['name'] to
                    // just $name only
                    extract($row);
                    
                    $user_item=array(
                        "id" => $id,
                        "firstname" => $firstname,
                        "lastname" => $lastname,
                        "email" => $email,
                        "contact_number" => $contact_number,
                        "country" => $country,
                        "state" => $state,
                        "postal_code" => $postal_code,
                        "address" => $address,
                        "access_level" => $access_level,
                        "agent" => $agent,
                        "admin" => $admin,
                        "status" => $status,
                        "avatar" => $avatar,
                        "created" => $created
                    );
                    //add the item to the record
                    array_push($users_arr["records"], $user_item);
                }
            }else if($decoded->data->access_level == "admin"){
                // retrieve our table contents
                // fetch() is faster than fetchAll()
                // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    // extract row
                    // this will make $row['name'] to
                    // just $name only
                    extract($row);
                    if($admin == $decoded->data->email){
                        $user_item=array(
                            "id" => $id,
                            "firstname" => $firstname,
                            "lastname" => $lastname,
                            "email" => $email,
                            "contact_number" => $contact_number,
                            "country" => $country,
                            "state" => $state,
                            "postal_code" => $postal_code,
                            "address" => $address,
                            "access_level" => $access_level,
                            "agent" => $agent,
                            "admin" => $admin,
                            "status" => $status,
                            "avatar" => $avatar,
                            "created" => $created
                        );
                        //add the item to the record
                        array_push($users_arr["records"], $user_item);
                    }            
                }
            }else if($decoded->data->access_level == "agent"){
                                // retrieve our table contents
                // fetch() is faster than fetchAll()
                // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    // extract row
                    // this will make $row['name'] to
                    // just $name only
                    extract($row);
                    if($agent == $decoded->data->email){
                        $user_item=array(
                            "id" => $id,
                            "firstname" => $firstname,
                            "lastname" => $lastname,
                            "email" => $email,
                            "contact_number" => $contact_number,
                            "country" => $country,
                            "state" => $state,
                            "postal_code" => $postal_code,
                            "address" => $address,
                            "access_level" => $access_level,
                            "agent" => $agent,
                            "admin" => $admin,
                            "status" => $status,
                            "avatar" => $avatar,
                            "created" => $created
                        );
                        //add the the item to the record
                        array_push($users_arr["records"], $user_item);
                    }
                }
            }
            //set response code - 200 OK
            http_response_code(200);
            echo json_encode($users_arr);
        }else{
            //report no record matching
            http_response_code(404);
            //show error message
            echo json_encode(array(
                "message" => "no records matching"
            ));
        }
    }
    // if decode fails, it means jwt is invalid
    catch (Exception $e){
    
        // set response code
        http_response_code(401);
    
        // show error message
        echo json_encode(array(
            "message" => "Access denied.",
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