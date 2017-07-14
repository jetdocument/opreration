<?php

session_start();
require 'PHPMailer/PHPMailerAutoload.php';
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


try {

	// $_REQUEST['case_id'] = "OS20170601005";
	// $_REQUEST['action_desc'] = "Something";
	// $_REQUEST['action_email'] = "jetdocument@gmail.com";
	// $_REQUEST['status'] = "Active";

	$get_data_from_id = "SELECT * FROM `service_head` WHERE `service_id` = '".$_REQUEST['case_id']."'";

	$results = $dbh -> prepare($get_data_from_id);
    $results -> execute();
    $result = $results -> fetch();
    // $return['data']['case'] = $result['contact_number'];

    $get_email_csae_created_by = "SELECT * FROM `user` WHERE `user_id` = '".$result['created_by']."'";
    $results = $dbh -> prepare($get_email_csae_created_by);
    $results -> execute();
    $email_created_by = $results -> fetch();

    $get_user_from_user = "SELECT * FROM `user` WHERE `user_id` = '".$_SESSION['user']."'";
    $results = $dbh -> prepare($get_user_from_user);
    $results -> execute();
    $acion_user = $results -> fetch();

    $get_user_from_id = "SELECT * FROM `user` WHERE `user_id` = '".$_REQUEST['action_to']."'";
    $results = $dbh -> prepare($get_user_from_id);
    $results -> execute();
    $acion_to_user = $results -> fetch();

    $mail = new PHPMailer(); // create a new object
	$mail->IsSMTP(); // enable SMTP
	// $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = $mailSMTPAuth; // authentication enabled
	$mail->SMTPSecure = $mailSMTPSecure; // secure transfer enabled REQUIRED for Gmail
	$mail->Host = $mailHost;
	$mail->Port = $mailPort; // or 587
	$mail->IsHTML($mailIsHTML);
	$mail->Username = $mailUsername;
	$mail->Password = $mailPassword;
	$mail->CharSet = $mailCharSet;

	$mail->Subject = $result['service_subject'];
	$mail->Body = "<!DOCTYPE html>
	<html>

	<body>
	<h2>Case ID : ".$result['service_id']."</h2>
	<h3>Subject : ".$result['service_subject']."</h3>
	<h4>Case Status : ".$_REQUEST['action_status']."</h4>
	<hr >
	<table style='width:100%'>
	<tbody>
	<tr >
	<td >&nbsp;<h4>Action from</h4></td>
	<td >&nbsp;</td>
	</tr>
	<tr >
	<td >&nbsp;Name</td>
	<td >&nbsp;".$acion_user['fname']."  ".$acion_user['fname']."</td>
	</tr>	
	<tr >
	<td >&nbsp;Contact</td>
	<td >&nbsp;".$acion_user['phone']."</td>
	</tr>
	<tr >
	<td >&nbsp;Email</td>
	<td >&nbsp;".$acion_user['email']."</td>
	</tr>
	<tr >
	<td >&nbsp;</td>
	<td >&nbsp;</td>
	</tr>
	<tr >
	<td >&nbsp;<h4>Details</h4></td>
	<td >&nbsp;</td>
	</tr>
	<tr >
	<td >&nbsp;Project No.</td>
	<td >&nbsp;".$result['estimate_id']."</td>
	</tr>
	<tr >
	<td >&nbsp;Action To</td>
	<td >&nbsp;".$acion_to_user['fname']."  ".$acion_to_user['lname']."</td>
	</tr>
	<tr >
	<td >&nbsp;Description</td>
	<td >&nbsp;".$_REQUEST['action_desc']."</td>
	</tr>
	<tr >
	<td >&nbsp;</td>
	<td >&nbsp;</td>
	</tr>
	<tr > System Link Inter : ".$interlink."</tr>
	<tr > System Link Local : ".$locallink."</tr>
	</tbody>
	</table>

	</body>
	</html>
	";
	// $mail->AddAttachment("D.jpg"); //add more files -> AddAttachment("index.php", "index.php")


	$mail->AddAddress($_SESSION['data']['email']);
	$mail->AddCC($email_created_by['email']);		
	$mail->AddCC($_REQUEST['action_email']);
	$mail->AddCC($result['contact_email']);
	$mail->AddCC($manager_one);
	$mail->AddCC($manager_two);
	$mail->AddCC($admin);


	
	$mail->FromName = $mailFromName;

	// $mail->Send();

	$dbh = null;

	 if(!$mail->Send()) {

	 	$return['status']  = "error";
	    $return['message'] = "Email Can not sent";
	    $return['data']['error'] = $mail->ErrorInfo;
	    echo json_encode($return);

	 } else {

	 	$return['status']  = "Success";
	    $return['message'] = "Message has been sent";
	    echo json_encode($return);

	 }


          
} catch (Exception $e) {

  $return['status'] = "error";
  $return['message'] = "Query statement fail";
  $return['data']['error'] = $e->getMessage();

} finally {

  echo json_encode($return);
  $dbh = null;  

}