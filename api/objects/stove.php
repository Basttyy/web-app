<?php
//stove object
class Stove{
    //database connection and table name
    private $conn;
    private $table_name = 'stoves';

    //object properties
    public $id;
    public $imei;
    public $phone_num;
    public $owned;
    public $email;
    public $agentid;
    public $adminid;
    public $payment_status;
    public $payment_time;
    public $purchase_time;
    public $working_status;
    public $refurbished;
    public $cycle_period;

    //constructor
    public function __construct($db){
        $this->conn = $db;
    }
    // create new user record
    function create(){    
        // insert query
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    imei = :imei,
                    phone_num = :phone_num,
                    owned = :owned,
                    email = :email,
                    agentid = :agentid,
                    adminid = :adminid,
                    payment_status = :payment_status,
                    payment_time = :payment_time,
                    purchase_time = :purchase_time,
                    working_status = :working_status,
                    refurbished = :refurbished,
                    cycle_period = :cycle_period";
    
        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->imei = htmlspecialchars(strip_tags($this->imei));
        $this->phone_num = htmlspecialchars(strip_tags($this->phone_num));
        $this->owned = htmlspecialchars(strip_tags($this->owned));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->agentid = htmlspecialchars(strip_tags($this->agentid));
        $this->adminid = htmlspecialchars(strip_tags($this->adminid));
        $this->payment_status = htmlspecialchars(strip_tags($this->payment_status));
        $this->payment_time = htmlspecialchars(strip_tags($this->payment_time));
        $this->purchase_time = htmlspecialchars(strip_tags($this->purchase_time));
        $this->working_status = htmlspecialchars(strip_tags($this->working_status));
        $this->refurbished = htmlspecialchars(strip_tags($this->refurbished));
        $this->cycle_period = htmlspecialchars(strip_tags($this->cycle_period));
    
        // bind the values
        $stmt->bindParam(':imei', $this->imei);
        $stmt->bindParam(':phone_num', $this->phone_num);
        $stmt->bindParam(':owned', $this->owned);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':agentid', $this->agentid);
        $stmt->bindParam(':adminid', $this->adminid);
        $stmt->bindParam(':payment_status', $this->payment_status);
        $stmt->bindParam(':payment_time', $this->payment_time);
        $stmt->bindParam(':purchase_time', $this->purchase_time);
        $stmt->bindParam(':working_status', $this->working_status);
        $stmt->bindParam(':refurbished', $this->refurbished);
        $stmt->bindParam(':cycle_period', $this->cycle_period);
    
        // execute the query, also check if query was successful
        if($stmt->execute()){
            return true;
        }else{
            print_r($stmt->errorInfo());
            return false;
        }
    }
    // read the next stove not paired
    function getNextPair(){    
        // query to read all user records, with limit clause for pagination
        $query = "SELECT
                    id,
                    imei,
                    phone_num,
                    owned,
                    email,
                    agentid,
                    adminid,
                    payment_status,
                    payment_time,
                    purchase_time,
                    working_status,
                    refurbished,
                    cycle_period
                FROM " . $this->table_name . "
                WHERE owned = ?
                ORDER BY id DESC
                LIMIT 0, 1";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        //sanitize data
        $this->owned = htmlspecialchars(strip_tags($this->owned));

        //bind statement variables
        $stmt->bindParam(1, $this->owned);
    
        // execute query
        $stmt->execute();

        //get number of rows
        $num = $stmt->rowCount();

        //set the next stove in the list
        if($num>0){
            //get record details/values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            //assign values to object properties
            $this->name = $row['imei'];
            $this->id = $row['id'];

            return true;
        }else{
            return false;
        }
    } 
    // read all stoves records
    function readAll($from_record_num, $records_per_page){
    
        // query to read all user records, with limit clause for pagination
        $query = "SELECT
                    id,
                    imei,
                    phone_num,
                    owned,
                    email,
                    agentid,
                    adminid,
                    payment_status,
                    payment_time,
                    purchase_time,
                    working_status,
                    refurbished,
                    cycle_period
                FROM " . $this->table_name . "
                ORDER BY id DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // execute query
        $stmt->execute();
    
        // return values
        return $stmt;
    }
    // read all stoves and paginate
    function readAllPaging($from_record_num, $records_per_page){

        // query to read all user records, with limit clause for pagination
        $query = "SELECT
                    id,
                    imei,
                    phone_num,
                    owned,
                    email,
                    agentid,
                    adminid,
                    payment_status,
                    payment_time,
                    purchase_time,
                    working_status,
                    refurbished,
                    cycle_period
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
    // search products
    function search($keywords){

        // select all query
        $query = "SELECT
                    id,
                    imei,
                    phone_num,
                    owned,
                    email,
                    agentid,
                    adminid,
                    payment_status,
                    payment_time,
                    purchase_time,
                    working_status,
                    refurbished,
                    cycle_period
                FROM
                    " . $this->table_name . "
                WHERE
                    imei LIKE ? OR phone_num LIKE ? OR email LIKE ? OR payment_status LIKE ? OR cycle_period LIKE ?
                ORDER BY
                    created DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $keywords=htmlspecialchars(strip_tags($keywords));
        $user->email=htmlspecialchars(strip_tags($user->email));
        $keywords = "%{$keywords}%";
    
        // bind
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);
        $stmt->bindParam(4, $keywords);
        $stmt->bindParam(5, $keywords);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
    // search products with pagination
    function searchPaging($keywords, $from_record_num, $records_per_page){

        // select all query
        $query = "SELECT
                    id,
                    imei,
                    phone_num,
                    owned,
                    email,
                    agentid,
                    adminid,
                    payment_status,
                    payment_time,
                    purchase_time,
                    working_status,
                    refurbished,
                    cycle_period
                FROM
                    " . $this->table_name . "
                WHERE
                    imei LIKE ? OR phone_num LIKE ? OR email LIKE ? OR payment_status LIKE ? OR cycle_period LIKE ?
                ORDER BY
                    created DESC
                LIMIT
                    ?, ?";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $keywords=htmlspecialchars(strip_tags($keywords));
        $user->email=htmlspecialchars(strip_tags($user->email));
        $keywords = "%{$keywords}%";
    
        // bind
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);
        $stmt->bindParam(4, $keywords);
        $stmt->bindParam(5, $keywords);
        $stmt->bindParam(6, $records_per_page, PDO::PARAM_INT);
        $stmt->bindParam(7, $from_record_num, PDO::PARAM_INT);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
    // used for paging stoves
    public function countAll(){
    
        // query to select all user records
        $query = "SELECT COUNT(*) as 'row_counts' FROM " . $this->table_name . "";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // return row count
        return $row['row_counts'];
    }        
    // used in email verification feature
    function updatePaymentStatus(){    
        // update query
        $query = "UPDATE " . $this->table_name . "
                SET payment_status = :payment_status
                WHERE id = :id";
    
        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->payment_status = htmlspecialchars(strip_tags($this->payment_status));
        $this->username = htmlspecialchars(strip_tags($this->username));
    
        // bind the values from the form
        $stmt->bindParam(':payment_status', $this->payment_status);
        $stmt->bindParam(':id', $this->id);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }    
        return false;
    }
    // used in recognizing stove working state feature
    function updateWorkingStatus(){
        // update query
        $query = "UPDATE " . $this->table_name . "
                SET working_status = :working_status
                WHERE id = :id";
    
        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->working_status = htmlspecialchars(strip_tags($this->working_status));
        $this->username = htmlspecialchars(strip_tags($this->username));
    
        // bind the values from the form
        $stmt->bindParam(':working_status', $this->working_status);
        $stmt->bindParam(':id', $this->id);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }    
        return false;
    }
    // update a user record
    public function pairStoveToUser(){    
        $query = "UPDATE " . $this->table_name . "
                SET
                    owned = :owned,
                    email = :email,
                    payment_status = :payment_status,
                    payment_time = :payment_time,
                    purchase_time = :purchase_time,
                    cycle_period = :cycle_period
                WHERE id = :id";
    
        // prepare the query
        $stmt = $this->conn->prepare($query);

        //sanitize data
        $this->owned = htmlspecialchars(strip_tags($this->owned));
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->payment_status = htmlspecialchars(strip_tags($this->payment_status));
        $this->payment_time = htmlspecialchars(strip_tags($this->payment_time));
        $this->purchase_time = htmlspecialchars(strip_tags($this->purchase_time));
        // $this->working_status = htmlspecialchars(strip_tags($this->working_status));
        // $this->refurbished = htmlspecialchars(strip_tags($this->refurbished));
        $this->cycle_period = htmlspecialchars(strip_tags($this->cycle_period));
    
        // bind the values from the form
        $stmt->bindParam(':owned', $this->owned);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':payment_status', $this->payment_status);
        $stmt->bindParam(':payment_time', $this->payment_time);
        $stmt->bindParam(':purchase_time', $this->purchase_time);
        // $stmt->bindParam(':working_status', $this->working_status);
        // $stmt->bindParam(':refurbished', $this->refurbished);
        $stmt->bindParam(':cycle_period', $this->cycle_period);
        //unique id of record
        $stmt->bindParam(':id', $this->id);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }
}