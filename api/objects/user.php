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
    public $country;
    public $state;
    public $postal_code;
    public $address;
    public $access_level;
    public $agentid;
    public $adminid;
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
        $query = "SELECT id, firstname, lastname, contact_number, email, agentid, adminid, country, state, postal_code, address, status, password, access_level
                FROM " . $this->table_name . "
                WHERE email = ?
                LIMIT 0,1";
    
        // prepare the query
        $stmt = $this->conn->prepare( $query );

        // sanitize
        $this->email=htmlspecialchars(strip_tags($this->email));
    
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
            $this->contact_number = $row['contact_number'];
            $this->email = $row['email'];
            $this->agentid = $row['agentid'];
            $this->adminid = $row['adminid'];
            $this->country = $row['country'];
            $this->state = $row['state'];
            $this->postal_code = $row['postal_code'];
            $this->status = $row['status'];
            $this->address = $row['address'];
            $this->access_level = $row['access_level'];
    
            // return true because email exists in the database
            return true;
        }else{
            //print_r($stmt->errorInfo());
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
                    agentid = :agentid,
                    adminid = :adminid,
                    contact_number = :contact_number,
                    country = :country,
                    state = :state,
                    postal_code = :postal_code,
                    address = :address,
                    access_level = :access_level,
                    access_code = :access_code,
                    status = :status,
                    password = :password";
    
        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->agentid = htmlspecialchars(strip_tags($this->agentid));
        $this->adminid = htmlspecialchars(strip_tags($this->adminid));
        $this->contact_number = htmlspecialchars(strip_tags($this->contact_number));
        $this->country = htmlspecialchars(strip_tags($this->country));
        $this->state = htmlspecialchars(strip_tags($this->state));
        $this->postal_code = htmlspecialchars(strip_tags($this->postal_code));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->password = htmlspecialchars(strip_tags($this->password));
        // $this->access_level = htmlspecialchars(strip_tags($this->access_level));
        // $this->access_code = htmlspecialchars(strip_tags($this->access_code));
        // $this->status = htmlspecialchars(strip_tags($this->status));
    
        // bind the values
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':agentid', $this->agentid);
        $stmt->bindParam(':adminid', $this->adminid);
        $stmt->bindParam(':contact_number', $this->contact_number);
        $stmt->bindParam(':country', $this->country);
        $stmt->bindParam(':state', $this->state);
        $stmt->bindParam(':postal_code', $this->postal_code);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':access_level', $this->access_level);
        $stmt->bindParam(':access_code', $this->access_code);
        $stmt->bindParam(':status', $this->status);
    
        // hash the password before saving to database
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);
    
        // execute the query, also check if query was successful
        $status = $stmt->execute();
        $this->id = $this->conn->lastInsertId();
        if($status){
            return true;
        }
        //print_r($stmt->errorInfo());
        return false;
    }
    // read all user records
    function readAll(){

        // query to read all user records, with limit clause for pagination
        $query = "SELECT
                    id,
                    firstname,
                    lastname,
                    email,
                    contact_number,
                    country,
                    state,
                    postal_code,
                    address,
                    access_level,
                    agentid,
                    adminid,
                    status,
                    avatar,
                    created
                FROM " . $this->table_name . "
                ORDER BY id DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // execute query
        $stmt->execute();
    
        // return values
        return $stmt;
    }
    // read all user records
    function readAllPaging($from_record_num, $records_per_page){
    
        // query to read all user records, with limit clause for pagination
        $query = "SELECT
                    id,
                    firstname,
                    lastname,
                    email,
                    contact_number,
                    country,
                    state,
                    postal_code,
                    address,
                    access_level,
                    agentid,
                    adminid,
                    status,
                    avatar,
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
    // search users
    function search(){

        // select all query
        $query = "SELECT
                    id,
                    firstname,
                    lastname,
                    email,
                    contact_number,
                    country,
                    state,
                    postal_code,
                    address,
                    access_level,
                    agentid,
                    adminid,
                    status,
                    avatar,
                    created
                FROM
                    " . $this->table_name . "
                WHERE
                    firstname LIKE ? OR lastname LIKE ? OR email LIKE ? OR contact_number LIKE ? OR access_level LIKE ? OR created LIKE ?
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
        $stmt->bindParam(6, $keywords);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
    // search users and paginate
    function searchPaging($keywords, $from_record_num, $records_per_page){

        // select all query
        $query = "SELECT
                    id,
                    firstname,
                    lastname,
                    email,
                    contact_number,
                    country,
                    state,
                    postal_code,
                    address,
                    access_level,
                    agentid,
                    adminid,
                    status,
                    avatar,
                    created
                FROM
                    " . $this->table_name . "
                WHERE
                    firstname LIKE ? OR lastname LIKE ? OR email LIKE ? OR contact_number LIKE ? OR access_level LIKE ? OR created LIKE ?
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
        $stmt->bindParam(6, $keywords);
        $stmt->bindParam(7, $records_per_page, PDO::PARAM_INT);
        $stmt->bindParam(8, $from_record_num, PDO::PARAM_INT);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
    // used for paging users
    public function countAll(){
    
        // query to select all user records
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // return row count
        return $row['total_rows'];
    }    
    // used in email verification feature
    function updateStatusByAccessCode(){
    
        // update query
        $query = "UPDATE " . $this->table_name . "
                SET status = :status
                WHERE access_code = :access_code";
    
        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->access_code = htmlspecialchars(strip_tags($this->access_code));
    
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

        //sanitize data
        $this->access_code = htmlspecialchars(strip_tags($this->access_code));
        $this->email = htmlspecialchars(strip_tags($this->email));
    
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

        //sanitize data
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->access_code = htmlspecialchars(strip_tags($this->access_code));
    
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
    function update(){     
        // if password needs to be updated
        $password_set=!empty($this->password) ? ", password = :password" : "";
    
        // if no posted password, do not update the password
        $query = "UPDATE " . $this->table_name . "
                SET
                    firstname = :firstname,
                    lastname = :lastname,
                    email = :email,
                    contact_number = :contact_number,
                    country = :country,
                    state = :state,
                    postal_code = :postal_code,
                    address = :address
                    {$password_set}
                WHERE id = :id";
    
        // prepare the query
        $stmt = $this->conn->prepare($query);

        //sanitize data
        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->contact_number = htmlspecialchars(strip_tags($this->contact_number));
        $this->country = htmlspecialchars(strip_tags($this->country));
        $this->state = htmlspecialchars(strip_tags($this->state));
        $this->postal_code = htmlspecialchars(strip_tags($this->postal_code));
        $this->address = htmlspecialchars(strip_tags($this->address));
    
        // bind the values from the form
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':contact_number', $this->contact_number);
        $stmt->bindParam(':country', $this->country);
        $stmt->bindParam(':state', $this->state);
        $stmt->bindParam(':postal_code', $this->postal_code);
        $stmt->bindParam(':address', $this->address);
    
        // hash the password before saving to database
        if(!empty($this->password)){
            $this->password = htmlspecialchars(strip_tags($this->password));
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