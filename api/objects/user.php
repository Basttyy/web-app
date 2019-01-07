<?php
// 'user' object
class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "users";
 
    // object properties
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $password;
    public $contact_number;
    public $address;
    public $access_level;
    public $access_code;
    public $status;
    public $avatar;
    public $created;
    public $modified;
 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    } 
    // check if given email exist in the database
    function emailExists(){
    
        // query to check if email exists
        $query = "SELECT id, firstname, lastname, password, access_level
                FROM " . $this->table_name . "
                WHERE email = ?
                LIMIT 0,1";
    
        // prepare the query
        $stmt = $this->conn->prepare( $query );
    
        // bind given email value
        $stmt->bindParam(1, $this->email);
    
        // execute the query
        $stmt->execute();
    
        // get number of rows
        $num = $stmt->rowCount();
    
        // if email exists, assign values to object properties for easy access and use for php sessions
        if($num>0){
    
            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // assign values to object properties
            $this->id = $row['id'];
            $this->firstname = $row['firstname'];
            $this->lastname = $row['lastname'];
            $this->password = $row['password'];
            $this->access_level = $row['access_level'];
    
            // return true because email exists in the database
            return true;
        }else{
            print_r($stmt->errorInfo());
            // return false if email does not exist in the database
            return false;
        }
    }
    // create new user record
    function create(){
    
        // insert query
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    firstname = :firstname,
                    lastname = :lastname,
                    email = :email,
                    password = :password,
                    contact_number = :contact_number,
                    address = :address,
                    access_level = :access_level,
                    access_code = :access_code,
                    status = :status";
    
        // prepare the query
        $stmt = $this->conn->prepare($query);
    
        // bind the values
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);
    
        // hash the password before saving to database
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);

        $stmt->bindParam(':contact_number', $this->contact_number);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':access_level', $this->access_level);
        $stmt->bindParam(':access_code', $this->access_code);
        $stmt->bindParam(':status', $this->status);
    
        // execute the query, also check if query was successful
        if($stmt->execute()){
            return true;
        }else{
            print_r($stmt->errorInfo());
            return false;
        }
    }
    // read all user records
    function readAll($from_record_num, $records_per_page){
    
        // query to read all user records, with limit clause for pagination
        $query = "SELECT
                    id,
                    firstname,
                    lastname,
                    email,
                    contact_number,
                    access_level,
                    created
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
    // used for paging users
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
    function updateStatusByAccessCode(){
    
        // update query
        $query = "UPDATE " . $this->table_name . "
                SET status = :status
                WHERE access_code = :access_code";
    
        // prepare the query
        $stmt = $this->conn->prepare($query);
    
        // bind the values from the form
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':access_code', $this->access_code);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }
    // check if given access_code exist in the database
    function accessCodeExists(){
    
        // query to check if access_code exists
        $query = "SELECT id
                FROM " . $this->table_name . "
                WHERE access_code = ?
                LIMIT 0,1";
    
        // prepare the query
        $stmt = $this->conn->prepare( $query );
    
        // bind given access_code value
        $stmt->bindParam(1, $this->access_code);
    
        // execute the query
        $stmt->execute();
    
        // get number of rows
        $num = $stmt->rowCount();
    
        // if access_code exists
        if($num>0){
    
            // return true because access_code exists in the database
            return true;
        }
    
        // return false if access_code does not exist in the database
        return false;
    
    }
    // used in forgot password feature
    function updateAccessCode(){
    
        // update query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    access_code = :access_code
                WHERE
                    email = :email";
    
        // prepare the query
        $stmt = $this->conn->prepare($query);
    
        // bind the values from the form
        $stmt->bindParam(':access_code', $this->access_code);
        $stmt->bindParam(':email', $this->email);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }
    // used in forgot password feature
    function updatePassword(){
    
        // update query
        $query = "UPDATE " . $this->table_name . "
                SET password = :password
                WHERE access_code = :access_code";
    
        // prepare the query
        $stmt = $this->conn->prepare($query);
    
        // bind the values from the form
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':access_code', $this->access_code);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }
    // update a user record
    public function update(){
    
        // if password needs to be updated
        $password_set=!empty($this->password) ? ", password = :password" : "";
    
        // if no posted password, do not update the password
        $query = "UPDATE " . $this->table_name . "
                SET
                    firstname = :firstname,
                    lastname = :lastname,
                    email = :email
                    {$password_set}
                WHERE id = :id";
    
        // prepare the query
        $stmt = $this->conn->prepare($query);
    
        // bind the values from the form
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);
    
        // hash the password before saving to database
        if(!empty($this->password)){
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password_hash);
        }
    
        // unique ID of record to be edited
        $stmt->bindParam(':id', $this->id);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }
}