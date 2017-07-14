<?php
require ("../config/php_conf.cfg");
if (!$link = mysqli_connect($servername, $username, $password, $dbname)) {
	# code...
	$return['status'] = "Unable to connect to MySQL";
	$return['value']['error'] = mysqli_connect_error();
	echo json_encode($return);
    exit;	
} else {
	# code...
	$return['status'] = "Connected successfully";	
	echo json_encode($return);

	$prefix = "OS";
	$today = "2017-05-03";
	$number = "002";
	$_REQUEST['subject'] = "ss";	
	$_REQUEST['contact_name'] = "";
	$_REQUEST['contact_number'] = "";
	$_REQUEST['contact_email'] = "";
	$_REQUEST['contact_company'] = "";
	$_REQUEST['estimate_id'] = "";
	$_SESSION['user'] = "";
	$_REQUEST['duty'] = "";
	$_REQUEST['file'] = "";
	$_REQUEST['picture'] = "";
	$_REQUEST['urgent'] = "";
	$_REQUEST['description'] = "";
	$_REQUEST['request_date'] = "2017-05-03 00:00:00";

	$in = "INSERT INTO `service_head`(
				    
				    `service_id`,
				    `service_subject`,
				    `date`,
				    `contact_name`,
				    `contact_number`,
				    `contact_email`,
				    `contact_company`,
				    `estimate_id`,
				    `created_by`,
				    `duty`,
				    `file`,
				    `picture`,
				    `urgent`,
				    `description`,
				    `request_date`,
				    `status`				    
				  )
				VALUES(
				  
				  'sdds',
				  'Active',
				  '2017-05-04',
				  'Active',
				  'Active',
				  'Active',
				  'Active',
				  'Active',
				  'Active',
				  'Active',
				  'Active',
				  'Active',
				  'Active',
				  'Active',
				  '2017-05-04 00:00:00',
				  'Active'
				)";

	
	$insert_new_case = "INSERT INTO `service_head`(
						 
						`service_id`, 
						`service_subject`, 
						`date`, 
						`contact_name`, 
						`contact_number`, 
						`contact_email`, 
						`contact_company`, 
						`estimate_id`, 
						`created_by`, 
						`duty`, 
						`file`, 
						`picture`, 
						`urgent`, 
						`description`, 
						`request_date`, 
						`status`)

						VALUES (

						'".$prefix.$today.$number."',
						'".$_REQUEST['subject']."',
						'".$today."',
						'".$_REQUEST['contact_name']."',
						'".$_REQUEST['contact_number']."',
						'".$_REQUEST['contact_email']."',
						'".$_REQUEST['contact_company']."',
						'".$_REQUEST['estimate_id']."',
						'".$_SESSION['user']."',
						'".$_REQUEST['duty']."',
						'".$_REQUEST['file']."',
						'".$_REQUEST['picture']."',
						'".$_REQUEST['urgent']."',
						'".$_REQUEST['description']."',
						'".$_REQUEST['request_date']."',
						'Active')";

	

	// Set autocommit to off
	mysqli_autocommit($link,FALSE);

	// Insert some values 
	// mysqli_query($link,$insert_Current_Number_Sql);
	mysqli_query($link,$insert_new_case);
	echo mysqli_error($link);
	//$ss = "INSERT INTO `service_header`(`case_id` ) VALUES('[ VALUE -18 ]')";
	// mysqli_query($link,$ss);
	//$link->query("INSERT INTO `service_header`(`case_id` ) VALUES('[ VALUE -18 ]')");
	// mysqli_query($link,"INSERT INTO `service_header`(`case_id` ) VALUES('Some')");
	// Commit transaction
	mysqli_commit($link);

	// Close connection

	$return['status'] = "Insert successfully";
	echo json_encode($return);
	echo $link->error;

	mysqli_close($link);
}

