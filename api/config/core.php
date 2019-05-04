<?php
// show error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// set your default time-zone
date_default_timezone_set('Africa/Lagos');

// home page url
$prod_url = "www.{$_SERVER['SERVER_NAME']}/";
$dev_url = "http://{$_SERVER['SERVER_NAME']}:8080/";
$home_url = $dev_url;
$api_url = "{$home_url}api";
//page given in url parameter, dafault is one
$page = isset($_GET['page']) ? $_GET['page'] : 1;
//set number of records per page
$records_per_page = 5;
//calculate for the query LIMIT clause
$from_record_num = ($records_per_page * $page) - $records_per_page;

// variables used for jwt
$key = "example_key";
$iss = "http://example.org";
$aud = "http://example.com";
$iat = 1356999524;
$nbf = 1357000000;