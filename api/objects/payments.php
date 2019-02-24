<?php
//payment object
class Payment{
    //database connection and table name
    private $conn;
    private $table_name = 'payments';

    //object properties
    public $id;
    public $user_id;
    public $user_email;
    public $trans_id;
    public $order_type;
    public $pay_category;
    public $amount;
    public $order_time;
    public $status;                 //Options: success, pending, unpaid

    //constructor
    public function __construct($db){
        $this->conn = $db;
    }
    // create new payment record
    function create(){    
        // insert query
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    user_id = :user_id,
                    trans_id = :trans_id,
                    order_type = :order_type,
                    pay_category = :pay_category,
                    amount = :amount,
                    order_time = :order_time,
                    status = :status";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->trans_id = htmlspecialchars(strip_tags($this->trans_id));
        $this->order_type = htmlspecialchars(strip_tags($this->order_type));
        $this->pay_category = htmlspecialchars(strip_tags($this->pay_category));
        $this->amount = htmlspecialchars(strip_tags($this->amount));
        $this->order_time = htmlspecialchars(strip_tags($this->order_time));
    
        // bind the values
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':trans_id', $this->trans_id);
        $stmt->bindParam(':order_type', $this->order_type);
        $stmt->bindParam(':pay_category', $this->pay_category);
        $stmt->bindParam(':amount', $this->amount);
        $stmt->bindParam(':order_time', $this->order_time);
    
        // execute the query, also check if query was successful
        if($stmt->execute()){
            return true;
        }else{
            print_r($stmt->errorInfo());
            return false;
        }
    }
    // read all payment records
    function readAll($from_record_num, $records_per_page){
    
        // query to read all user records, with limit clause for pagination
        $query = "SELECT
                    id,
                    user_id,
                    trans_id,
                    order_type,
                    pay_category,
                    amount,
                    order_time
                FROM " . $this->table_name . "
                ORDER BY id DESC
                LIMIT ?, ?";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // bind limit clause variables
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
    
        // execute query
        $stmt->execute();
    
        // return values
        return $stmt;
    }    
    // used for paging payments
    public function countAll(){
    
        // query to select all user records
        $query = "SELECT id FROM " . $this->table_name . "";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        // get number of rows
        $num = $stmt->rowCount();
    
        // return row count
        return $num;
    }
    // used for paging payments
    public function countUserPayments($email){
    
        // query to select all user records
        $query = "SELECT id FROM " . $this->table_name . "WHERE user_id =:user_id";

        $stmt->bindParam(':user_id', $this->user_id);
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        // get number of rows
        $num = $stmt->rowCount();
    
        // return row count
        return $num;
    }
    //used in email verification feature
    function updateTransStatus(){    
        // update query
        $query = "UPDATE " . $this->table_name . "
                SET status = :status
                WHERE trans_id = :trans_id";
    
        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->trans_id = htmlspecialchars(strip_tags($this->trans_id));
    
        // bind the values from the form
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':trans_id', $this->trans_id);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }    
        return false;
    }
    // delete a payment record
    function delete(){
    
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
    
        // bind id of record to delete
        $stmt->bindParam(1, $this->id);
    
        // execute query
        if($stmt->execute()){
            return true;
        }    
        return false;        
    }
    // update a user record
    // public function update(){    
    //     // if no posted password, do not update the password
    //     $query = "UPDATE " . $this->table_name . "
    //             SET
    //                 owned = :owned,
    //                 username = :username,
    //                 payment_status = :payment_status,
    //                 payment_time = :payment_time,
    //                 purchase_time = :purchase_time,
    //                 working_status = :working_status,
    //                 refurbished = :refurbished,
    //                 cycle_period = :cycle_period,
    //             WHERE id = :id";
    
    //     // prepare the query
    //     $stmt = $this->conn->prepare($query);

    //     //sanitize data
    //     $this->owned = htmlspecialchars(strip_tags($this->owned));
    //     $this->username = htmlspecialchars(strip_tags($this->username));
    //     $this->payment_status = htmlspecialchars(strip_tags($this->payment_status));
    //     $this->payment_time = htmlspecialchars(strip_tags($this->payment_time));
    //     $this->purchase_time = htmlspecialchars(strip_tags($this->purchase_time));
    //     $this->working_status = htmlspecialchars(strip_tags($this->working_status));
    //     $this->refurbished = htmlspecialchars(strip_tags($this->refurbished));
    //     $this->cycle_period = htmlspecialchars(strip_tags($this->cycle_period));
    
    //     // bind the values from the form
    //     $stmt->bindParam(':firstname', $this->firstname);
    //     $stmt->bindParam(':owned', $this->owned);
    //     $stmt->bindParam(':payment_status', $this->payment_status);
    //     $stmt->bindParam(':payment_time', $this->payment_time);
    //     $stmt->bindParam(':purchase_time', $this->purchase_time);
    //     $stmt->bindParam(':working_status', $this->working_status);
    //     $stmt->bindParam(':refurbished', $this->refurbished);
    //     $stmt->bindParam(':cycle_period', $this->cycle_period);
    
    //     // unique ID of record to be edited
    //     $stmt->bindParam(':id', $this->id);
    
    //     // execute the query
    //     if($stmt->execute()){
    //         return true;
    //     }
    
    //     return false;
    // }
}