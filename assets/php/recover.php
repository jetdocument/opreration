<?php

function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

$randomPassword = randomPassword();

// echo $_REQUEST['email'];
// echo $randomPassword;
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

$get_user_data_by_email = "SELECT * FROM `user` WHERE `email` = '".$_REQUEST['email']."'";
$results = $dbh -> prepare($get_user_data_by_email);
$results -> execute();
$result = $results->fetch(); 

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

$mail->Subject = "Password Recovered";
$mail->Body = "<!DOCTYPE html>
<html>

<body>
<h3>User id is : ".$result['user_id']."</h3>
<h3>New password is : ".$randomPassword."</h3>

<hr >
<table style='width:100%'>
<tbody>

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


$mail->AddAddress($_REQUEST['email']);

$mail->FromName = $mailFromName;

// $mail->Send();


 if(!$mail->Send()) {

 	$return['status']  = "error";
    $return['message'] = "Email Can not sent";
    $return['data']['error'] = $mail->ErrorInfo;
    echo json_encode($return);

 } else {

 	$return['status']  = "Success";
    $return['message'] = "Message has been sent";    
    # code...
	$update_pass = "UPDATE `user` SET `pass` = '".md5($randomPassword)."' WHERE `email` = '".$_REQUEST['email']."'";

	try {

		$results = $dbh -> prepare($update_pass);
	    $results -> execute();
	    $return['message'] = "Update Complete";  

	} catch (Exception $e) {

	  $return['status'] = "error";
	  $return['message'] = "Query statement fail";
	  $return['data']['error'] = $e->getMessage();

	} finally {
		echo json_encode($return);  
		$dbh = null;
	}

 }




