<?php
//stove object
class Stove_id{
    //database connection and table name
    private $conn;
    private $table_name = 'stove_ids';

    //object properties
    public $id;
    public $value;
    public $status;
    public $created;
    public $updated;

    //constructor
    public function __construct($db){
        $this->conn = $db;
    }
    // create new user record
    function create(){    
        // insert query
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    value = :value";
    
        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->value = htmlspecialchars(strip_tags($this->value));
    
        // bind the values
        $stmt->bindParam(':value', $this->value);
    
        // execute the query, also check if query was successful
        if($stmt->execute()){
            return true;
        }else{
            print_r($stmt->errorInfo());
            return false;
        }
    }
    // read the next stove not paired
    function getStatus(){    
        // query to read all user records, with limit clause for pagination
        $query = "SELECT
                    id,
                    status
                FROM " . $this->table_name . "
                WHERE value = ?";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        //sanitize data
        $this->value = htmlspecialchars(strip_tags($this->value));

        //bind statement variables
        $stmt->bindParam(1, $this->value);
    
        // execute query
        $stmt->execute();

        //get number of rows
        $num = $stmt->rowCount();

        //set the next stove in the list
        if($num>0){
            //get record details/values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            //assign values to object properties
            $this->status = $row['status'];
            $this->id = $row['id'];

            return true;
        }else{
            return false;
        }
    } 
    // read the next stove not paired
    function getNextPair(){    
        // query to read all user records, with limit clause for pagination
        $query = "SELECT
                    id,
                    value,
                    status,
                    created,
                    updated
                FROM " . $this->table_name . "
                WHERE status = 0
                ORDER BY id DESC
                LIMIT 0, 1";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // execute query
        $stmt->execute();

        //get number of rows
        $num = $stmt->rowCount();

        //set the next stove in the list
        if($num>0){
            //get record details/values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            //assign values to object properties
            $this->value = $row['value'];
            $this->status = $row['status'];
            $this->created = $row['created'];
            $this->updated = $row['updated'];
            $this->id = $row['id'];

            return true;
        }else{
            return false;
        }
    } 
    // read all stoves records
    function readAll(){
    
        // query to read all user records, with limit clause for pagination
        $query = "SELECT
                    id,
                    value,
                    status,
                    created,
                    updated
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
                    value,
                    status,
                    created,
                    updated
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
                    value,
                    status,
                    created,
                    updated
                FROM
                    " . $this->table_name . "
                WHERE
                    value LIKE ? OR status LIKE ? OR created LIKE ? OR updated LIKE ?
                ORDER BY
                    id DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $keywords=htmlspecialchars(strip_tags($keywords));
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
                    value,
                    status,
                    created,
                    updated
                FROM
                    " . $this->table_name . "
                WHERE
                    value LIKE ? OR status LIKE ? OR created LIKE ? OR updated LIKE ?
                ORDER BY
                    id DESC
                LIMIT
                    ?, ?";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $keywords=htmlspecialchars(strip_tags($keywords));
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
    function updateStatus(){    
        // update query
        $query = "UPDATE " . $this->table_name . "
                SET status = :status
                WHERE id = :id";
    
        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->status = htmlspecialchars(strip_tags($this->status));
    
        // bind the values from the form
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }    
        return false;
    }
}