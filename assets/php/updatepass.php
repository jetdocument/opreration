<?php

# Validate Session

session_start();

#if($_SESSION['user']){ echo $_SESSION['user'];}

if (!isset($_SESSION['user'])) {
header('Location: login.html'); 
}

# get resource

require ("../../config/php_conf.cfg");

try { 

  $dbh = new PDO('mysql:host='.$servername.';dbname='.$dbname, $username, $password);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $dbh->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
  $dbh->exec("set names utf8");
  // $dbh->setAttribute(PDO::ATTR_AUTOCOMMIT, FALSE);
  // die(json_encode(array('outcome' => true)));
  $return['message'] = "Mysqli Connected";
} catch (Exception $e) {
  $return['status'] = "error";
  $return['message'] = "Unable to connect to Mysql";
  $return['data']['error'] = $e->getMessage();
  die(json_encode($return));
} 

if ($_SESSION['data']['pass'] != $_REQUEST['current']) {
	# code...
	$return['message'] = "Current Password Incorrect";
} else {
	# code...
	$update_pass = "UPDATE `user` SET `pass` = '".$_REQUEST['new_pass']."' WHERE `user_id` = '".$_SESSION['user']."'";

	try {

		$results = $dbh -> prepare($update_pass);
	    $results -> execute();
	    $return['message'] = "Update Complete";  

	} catch (Exception $e) {

	  $return['status'] = "error";
	  $return['message'] = "Query statement fail";
	  $return['data']['error'] = $e->getMessage();

	} 
}

echo json_encode($return);  
$dbh = null;


