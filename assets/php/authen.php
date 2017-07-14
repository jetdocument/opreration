<?php
# get resource

require ("../../config/php_conf.cfg");

# prepare parameter

# connect database
try {
  $dbh = new PDO('mysql:host='.$servername.';dbname='.$dbname, $username, $password);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $dbh->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
  $return['status'] = "Success";
  $return['message'] = "Mysqli Connected";
} catch (Exception $e) {
  $return['status'] = "error";
  $return['message'] = "Unable to connect to Mysql";
  $return['data']['error'] = $e->getMessage();
  die(json_encode($return));
} 

// $return['status'] = "Success";
// $return['message'] = "Response";
// $return['data']['user'] = $_REQUEST['user'];
// $return['data']['pass'] = $_REQUEST['pass'];
// $return['data']['company'] = $_REQUEST['company'];

// echo json_encode($return);

// $add_user = "INSERT
//                   INTO
//                     `user`(
//                       `user_id`,
//                       `pass`,
//                       `fname`,
//                       `lname`,
//                       `gender`,
//                       `email`,
//                       `phone`,
//                       `company`
//                     )
//                   VALUES(
//                     '".$_REQUEST['user']."',
//                     '".$_REQUEST['pass']."',
//                     '".$_REQUEST['fname']."',
//                     '".$_REQUEST['lname']."',
//                     '".$_REQUEST['gender']."',
//                     '".$_REQUEST['email']."',
//                     '".$_REQUEST['phone']."',
//                     '".$_REQUEST['company']."'
//                   )";
                  
$get_user_data = "	SELECT
					  *
					FROM
					  `user`
					WHERE
					  `user_id` = '".$_REQUEST['user']."' AND `pass` = '".$_REQUEST['pass']."'";

try {
       
    $results = $dbh -> prepare($get_user_data);
    $results -> execute();    

    if ($results->rowCount() > 0) {
    	# code...    	 
    	// ini_set('session.gc_maxlifetime', 30);
		session_start();
		// session_start([
		// 	'cache_limiter' => 'private',
		// 	'read_and_close' => true,
		// 	]);
		// session_destroy();
		// $maxlifetime = ini_get("session.gc_maxlifetime");			
    	// $return['data']['session'] = json_encode($_SESSION['data']);
    	$_SESSION['user'] =  $_REQUEST['user'];
    	if (isset($_SESSION['user'])) {
    		# code...    		
			 $_SESSION['data'] = $results -> fetch();
        $_SESSION['email'] = $_SESSION['data']['email'];
    		$return['message'] = $_SESSION['user'];
    		$return['data']['session'] = json_encode($_SESSION['data']);
    	} else {
    		$return['status'] = "error";
    		$return['message'] = "Session not create";
    	}    	

    } else {
    	# code...
    	$return['status'] = "error";
		$return['message'] = "Please SignUp";
    }                  

  } catch (Exception $e) {
           
    $return['status']  = "error";
    $return['message'] = "Rollback Transaction";
    $return['data']['error'] = $e->getMessage(); 

  } finally {

    echo json_encode($return);
    $dbh = null; 
  }

