<?php
//show error reporting
error_reporting(E_ALL);

//set homepage url
$home_url = "http://localhost/web-app/";

// page given in URL parameter, default page is one
$page = isset($_GET['page']) ? $_GET['page'] : 1; 
 
// set number of records per page
$records_per_page = 5;
 
// calculate for the query LIMIT clause
$from_record_num = ($records_per_page * $page) - $records_per_page;

// show error reporting
error_reporting(E_ALL);
 
// set your default time-zone
date_default_timezone_set('Central Africa/Manila');
 
// variables used for jwt
$key = "example_key";
$iss = "http://example.org";
$aud = "http://example.com";
$iat = 1356999524;
$nbf = 1357000000;