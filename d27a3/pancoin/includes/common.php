<?php

// txt-db-api library: http://www.c-worker.ch/txtdbapi/index_eng.php
require_once("php-txt-db/txt-db-api.php");
require_once("login.php");
require_once("auth.php");
require_once("navigation.php");

// Allow users to use the back button without re-posting data
header ("Cache-Control: private");
// Bypass Chrome's XSS check/block
header ("X-XSS-Protection: 0");

// Init global variables
$db = new Database("pancoin");
$user = new User($db);

// Check for logout and maybe display login page
if($_GET['action'] == 'logout') { 
  $user->_logout();
  display_login();
  exit();
}

// Validate user and maybe display login page
if(!validate_user($user)) {
  display_login();
  exit();
}

?>
