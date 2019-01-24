<?php
//stove object
class stove{
    //database connection and table name
    private $conn;
    private $table_name = 'stoves';

    //object properties
    public $id;    
    public $owned;
    public $username;
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
                    owned = :owned,
                    username = :username,
                    payment_status = :payment_status,
                    payment_time = :payment_time,
                    purchase_time = :purchase_time,
                    working_status = :working_status,
                    refurbished = :refurbished,
                    $cycle_period = :cycle_period";
    
        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->owned = htmlspecialchars(strip_tags($this->owned));
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->payment_status = htmlspecialchars(strip_tags($this->payment_status));
        $this->payment_time = htmlspecialchars(strip_tags($this->payment_time));
        $this->purchase_time = htmlspecialchars(strip_tags($this->purchase_time));
        $this->working_status = htmlspecialchars(strip_tags($this->working_status));
        $this->refurbished = htmlspecialchars(strip_tags($this->refurbished));
        $this->cycle_period = htmlspecialchars(strip_tags($this->cycle_period));
    
        // bind the values
        $stmt->bindParam(':owned', $this->owned);
        $stmt->bindParam(':username', $this->username);
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
    // read all stoves records
    function readAll($from_record_num, $records_per_page){
    
        // query to read all user records, with limit clause for pagination
        $query = "SELECT
                    id,
                    owned,
                    username,
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
    // used for paging stoves
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
    // used in email verification feature
    function updatePaymentStatus(){    
        // update query
        $query = "UPDATE " . $this->table_name . "
                SET payment_status = :payment_status
                WHERE username = :username";
    
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
                WHERE username = :username";
    
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
    public function update(){    
        // if no posted password, do not update the password
        $query = "UPDATE " . $this->table_name . "
                SET
                    owned = :owned,
                    username = :username,
                    payment_status = :payment_status,
                    payment_time = :payment_time,
                    purchase_time = :purchase_time,
                    working_status = :working_status,
                    refurbished = :refurbished,
                    cycle_period = :cycle_period,
                WHERE id = :id";
    
        // prepare the query
        $stmt = $this->conn->prepare($query);

        //sanitize data
        $this->owned = htmlspecialchars(strip_tags($this->owned));
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->payment_status = htmlspecialchars(strip_tags($this->payment_status));
        $this->payment_time = htmlspecialchars(strip_tags($this->payment_time));
        $this->purchase_time = htmlspecialchars(strip_tags($this->purchase_time));
        $this->working_status = htmlspecialchars(strip_tags($this->working_status));
        $this->refurbished = htmlspecialchars(strip_tags($this->refurbished));
        $this->cycle_period = htmlspecialchars(strip_tags($this->cycle_period));
    
        // bind the values from the form
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':owned', $this->owned);
        $stmt->bindParam(':payment_status', $this->payment_status);
        $stmt->bindParam(':payment_time', $this->payment_time);
        $stmt->bindParam(':purchase_time', $this->purchase_time);
        $stmt->bindParam(':working_status', $this->working_status);
        $stmt->bindParam(':refurbished', $this->refurbished);
        $stmt->bindParam(':cycle_period', $this->cycle_period);
    
        // unique ID of record to be edited
        $stmt->bindParam(':id', $this->id);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }
}