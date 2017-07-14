<?php
require ("../../../config/php_conf.cfg");

// Create connection
if (!$link = mysqli_connect($servername, $username, $password)) {
	# code...
	$return['status'] = "Unable to connect to MySQL";
	$return['value']['error'] = mysqli_connect_error();
	echo json_encode($return);
    exit;	
} else {
	# code...
	$return['status'] = "Connected successfully";	
	echo json_encode($return);
}


// $conn = new mysqli($servername, $username, $password);

// // Check connection
// if ($conn->connect_error) {
//     // die("Connection failed: " . $conn->connect_error);
//     // return $conn->connect_error;
//     $return['status'] = "Unable to connect to MySQL";
//     // $return['value']['error'] = $link->error;
//     $return['value']['error'] = mysqli_connect_error();
//     echo json_encode($return);
//     exit;
// } 
// // return "Connected successfully";
// $return['status'] = "Connected successfully";
// echo json_encode($return);
?>