<?php
//confirm that reference has not already gotten value
//this would have happened most times if you handle the charge.success event.
//If it has already gotten value by your records, you may call
//perform_success()

//get this information from https://github.com/yabacon/paystack-class
require 'Paystack.php';
//if using https://github.como/yabacoon/paystack-php
//require 'paystack/autoload.php';

$paystack = new Paystack('sk_test_d98838a864632da0998763d1e33a3998ea9ce812');
//the code below throws an exception if there was a problem completing the request,
//else returns an object created from the json response
$trx = $paystack->transaction->verify(
    [
        'reference'=>$_GET['reference']
    ]
);
//status should be true if there was a successful call
if(!$trx->status){
    exit($trx->message);
}
//full sample verify response is here: https://developers.paystack.co/docs/verifying-transactions
if('success' == $trx->data->status){
    //use trx info including metadata and session info to confirm that cartid
    //matches the one for which we accepted payment
    given_value($reference, $trx);
    perform_success();
}
//functions
function give_value($reference, $trx){
    //Be sure to log the reference as having gotten value
    //write code to give value
}

function perform_success(){
    //inline
    echo json_encode(['verified'=>true]);
    //standard
    //header('Location: /powerstove/paysuccess.php');
    exit();
}