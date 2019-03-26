<?php
//payment object
class Payment{
    //database connection and table name
    private $conn;
    private $table_name = 'payments';

    //object properties
    public $id;
    public $email;
    public $due_date;
    public $item;
    public $description;
    public $unit_cost;
    public $quantity;
    public $order_type;
    public $order_time;
    public $invoice_ref;
    public $status;                 //Options: unpaid, pending, success, failed

    //constructor
    public function __construct($db){
        $this->conn = $db;
    }
    // create new payment record
    function create(){    
        // insert query
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    email = :email,                    
                    item = :item,
                    description = :description,
                    unit_cost = :unit_cost,
                    quantity = :quantity,
                    order_type = :order_type,
                    invoice_ref = :invoice_ref,
                    status = :status,
                    due_date = :due_date";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->due_date = htmlspecialchars(strip_tags($this->due_date));
        $this->item = htmlspecialchars(strip_tags($this->item));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->unit_cost = htmlspecialchars(strip_tags($this->unit_cost));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $this->order_type = htmlspecialchars(strip_tags($this->order_type));
        $this->invoice_ref = htmlspecialchars(strip_tags($this->invoice_ref));
        $this->status = htmlspecialchars(strip_tags($this->status));
    
        // bind the values
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':item', $this->item);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':unit_cost', $this->unit_cost);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':order_type', $this->order_type);
        $stmt->bindParam(':invoice_ref', $this->invoice_ref);
        $stmt->bindParam(':status', $this->status);        
        $stmt->bindParam(':due_date', $this->due_date);
    
        // execute the query, also check if query was successful
        $status = $stmt->execute();
        if($status){
            return true;
        }
        print_r($stmt->errorInfo());
        return false;
    }
    // read all payment records
    function readAll(){
    
        // query to read all user records, with limit clause for pagination
        $query = "SELECT
                    id,
                    email,
                    agent,
                    admin,
                    description,
                    unit_cost,
                    quantity,
                    order_type,
                    invoice_ref,
                    status,
                    amount,
                    order_time,
                    paid_time
                FROM " . $this->table_name . "
                ORDER BY id DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // execute query
        $stmt->execute();
    
        // return values
        return $stmt;
    }
    // read all payment records
    function readAllPaging($from_record_num, $records_per_page){

        // query to read all user records, with limit clause for pagination
        $query = "SELECT
                    id,
                    email,
                    agent,
                    admin,
                    description,
                    unit_cost,
                    quantity,
                    order_type,
                    invoice_ref,
                    status,
                    amount,
                    order_time,
                    paid_time
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
                    email,
                    agent,
                    admin,
                    description,
                    unit_cost,
                    quantity,
                    order_type,
                    invoice_ref,
                    status,
                    amount,
                    order_time,
                    paid_time
                FROM
                    " . $this->table_name . "
                WHERE
                    description LIKE ? OR quantity LIKE ? OR order_type LIKE ? OR invoice_ref LIKE ? OR status LIKE ? OR amount LIKE ?
                ORDER BY
                    created DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $keywords=htmlspecialchars(strip_tags($keywords));
        $email=htmlspecialchars(strip_tags($email));
        $from_record_num=htmlspecialchars(strip_tags($from_record_num));
        $records_per_page=htmlspecialchars(strip_tags($records_per_page));
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
    // search products
    function searchPaging($keywords, $from_record_num, $records_per_page){

        // select all query
        $query = "SELECT
                    id,
                    email,
                    agent,
                    admin,
                    description,
                    unit_cost,
                    quantity,
                    order_type,
                    invoice_ref,
                    status,
                    amount,
                    order_time,
                    paid_time
                FROM
                    " . $this->table_name . "
                WHERE
                    description LIKE ? OR quantity LIKE ? OR order_type LIKE ? OR invoice_ref LIKE ? OR status LIKE ? OR amount LIKE ?
                ORDER BY
                    created DESC
                LIMIT
                    ?, ?";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $keywords=htmlspecialchars(strip_tags($keywords));
        $email=htmlspecialchars(strip_tags($email));
        $from_record_num=htmlspecialchars(strip_tags($from_record_num));
        $records_per_page=htmlspecialchars(strip_tags($records_per_page));
        $keywords = "%{$keywords}%";
    
        // bind
        $stmt->bindParam(1, $email);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);
        $stmt->bindParam(4, $keywords);
        $stmt->bindParam(5, $keywords);
        $stmt->bindParam(6, $keywords);
        $stmt->bindParam(7, $keywords);
        $stmt->bindParam(8, $records_per_page, PDO::PARAM_INT);
        $stmt->bindParam(9, $from_record_num, PDO::PARAM_INT);
    
        // execute query
        $stmt->execute();
    
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
    // used for paging payments by user
    public function countUserPayments($email){    
        // query to select all user records
        $query = "SELECT id FROM " . $this->table_name . "WHERE email =:email";

        $stmt->bindParam(':email', $this->email);
    
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
                WHERE invoice_ref = :invoice_ref";
    
        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->invoice_ref = htmlspecialchars(strip_tags($this->invoice_ref));
    
        // bind the values from the form
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':trans_id', $this->invoice_ref);
    
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
}