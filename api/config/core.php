<?php
// show error reporting
error_reporting(E_ALL);
 
// set your default time-zone
date_default_timezone_set('Africa/Lagos');

// home page url
$home_url = "www.{$_SERVER['SERVER_NAME']}/";
 
// variables used for jwt
$key = "example_key";
$iss = "http://example.org";
$aud = "http://example.com";
$iat = 1356999524;
$nbf = 1357000000;
?>