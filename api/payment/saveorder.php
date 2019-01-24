<?php
require __DIR__ . '/../resource/session.php';
require __DIR__ . '/../resource/database.php';
require __DIR__ . '/../resource/utilities.php';
//prepare variables for database connection
if(isset($_POST['userid'])){
    $userid = $_POST['userid'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $paycategory = ' ';
    if($_POST['paycategory'] != null) $paycategory = $_POST['paycategory'];
    $amount = 0;
    $count = 0;

    $sql = "SELECT * FROM users WHERE id = :id";
    //use pdo to sanitize data
    $statement = $db->prepare($sql);
    //add data into database
    $statement->execute(array(':id' => $userid));
    if($row = $statement->fetch()){            
        if($paycategory == "purch_install"){
            $amount = 1000000;
        }else if($paycategory == "purch_full"){
            $amount = 2800000;
        }else if($paycategory == "purch_renew"){
            $amount = 400000;
        }  
        $query = $db->prepare("SELECT (id) FROM payments ORDER BY id DESC LIMIT 1");
        $query->execute();
        if($row = $query->fetch()){
            $count = $row['id'];                
        } 
        $count += 1;
            
        $sql = $db->prepare("INSERT INTO payments(user_id, trans_id, order_type, pay_category, amount, order_date)
                            VALUES(:id, :trans_id, :order_type, :pay_category, :amount, now())");
        $sql->execute(array(':id'=>$userid, ':trans_id'=>'trans'.$count, ':order_type'=>'installment', ':pay_category'=>$paycategory, ':amount'=>$amount));
        if($sql->rowcount()===1){
            $data = (object)[];
            $data->amount = $amount;
            $data->orderid = $paycategory.(string)$count;
            $data->cartid = "cart".(string)$count;
        }
    }
}
echo json_encode($data);