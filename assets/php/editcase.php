<?php

session_start();

if (!isset($_SESSION['user'])) {
header('Location: login.html'); 
}

require ("../../config/php_conf.cfg");

try { 

  $dbh = new PDO('mysql:host='.$servername.';dbname='.$dbname, $username, $password);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $dbh->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
  $dbh->exec("set names utf8");
  // $dbh->setAttribute(PDO::ATTR_AUTOCOMMIT, FALSE);
  // die(json_encode(array('outcome' => true)));
  $return['status']  = "Success";
  $return['message'] = "Mysqli Connected";
} catch (Exception $e) {
  $return['status'] = "error";
  $return['message'] = "Unable to connect to Mysql";
  $return['data']['error'] = $e->getMessage();
  die(json_encode($return));
}

// echo 
// $_REQUEST['case_id']."\n".
// $_REQUEST['subject']."\n".
// $_REQUEST['c_name']."\n".
// $_REQUEST['c_number']."\n".
// $_REQUEST['c_email']."\n".
// $_REQUEST['c_company']."\n".
// $_REQUEST['estimate_id']."\n".
// $_REQUEST['project_name']."\n".
// $_REQUEST['urgent']."\n".
// $_REQUEST['desc'];

// ;


try {          

      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $dbh->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);      
      $dbh->beginTransaction();

      $stmt = $dbh->prepare(
      	"UPDATE `service_head` SET
		  
		  `service_subject` = '".$_REQUEST['subject']."',
		  `contact_name` 	= '".$_REQUEST['c_name']."',
		  `contact_number` 	= '".$_REQUEST['c_number']."',
		  `contact_email` 	= '".$_REQUEST['c_email']."',
		  `contact_company` = '".$_REQUEST['c_company']."',
		  `estimate_id` 	= '".$_REQUEST['estimate_id']."',
		  `project_name` 	= '".$_REQUEST['project_name']."',
		  `urgent` 			= '".$_REQUEST['urgent']."',
		  `description` 	= '".$_REQUEST['desc']."'

		WHERE

		  `service_id` 		= '".$_REQUEST['case_id']."'");

      $stmt -> execute();          
      $dbh->commit();
      $return['status']  = "Success";
      $return['message'] = "Edit complete at : ".date("d-m-y H:i:s").json_encode($dbh);

    } catch (Exception $e) {
      $dbh->rollBack();
      $return['status']  = "error";
      $return['message'] = "Rollback Transaction";
      $return['data']['error'] = $e->getMessage().json_encode($dbh);
      $dbh = null;
    }

echo json_encode($return);

?>