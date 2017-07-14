<?php 

session_start();

if (!isset($_SESSION['user'])) {
header('Location: login.html'); 
}
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

$get_user_data = $dbh -> prepare("SELECT * FROM `user` WHERE `user_id` = '".$_SESSION['user']."'");
$get_user_data -> execute();
$user_data = $get_user_data -> fetch();

$get_case_device = $dbh -> prepare("SELECT * FROM `service_details` WHERE `service_id` = '".$_REQUEST['case_id']."'");
$get_case_device -> execute();
$device_data = $get_case_device -> fetchAll();

$device_out = "";

if (sizeof($device_data) > 0) {
	# code...
	$device_out = "<style> table {font-family: arial, sans-serif; border-collapse: collapse; width: 100%; } td, th {border: 1px solid #dddddd; text-align: left; padding: 8px; width: 50%;} tr:nth-child(even) {background-color: #dddddd; } </style><table> <thead> <tr>
                        <th>No.</th>
                        <th>Type</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Serial</th>
                        <th>Description</th>                                        
                    </tr>
                    </thead>
                    <tbody> ";
	foreach ($device_data as $key => $value) {
		# code...
		$no = $key+1;
		$device_out = $device_out."<tr>"
			."<td>".$no."</td>"
			."<td>".$value['type']."</td>"
			."<td>".$value['brand']."</td>"
			."<td>".$value['model']."</td>"
			."<td>".$value['serial']."</td>"
			."<td>".$value['description']."</td></tr>";
	}
	$device_out = $device_out."</tbody> </table>";
}

$get_case_action = $dbh -> prepare("SELECT * FROM `service_action` WHERE `service_id` = '".$_REQUEST['case_id']."' ORDER BY `action_time` DESC");
$get_case_action -> execute();
$action_data = $get_case_action -> fetchAll();

$action_out = "";

if (sizeof($device_data) > 0) {
	# code...
	$action_out = "<style> table {font-family: arial, sans-serif; border-collapse: collapse; width: 100%; } td, th {border: 1px solid #dddddd; text-align: left; padding: 8px; width: 50%;} tr:nth-child(even) {background-color: #dddddd; } </style><table> <thead> <tr>
                                        <th>Status</th>
                                        <th>Time</th>
                                        <th>Action by</th>
                                        <th>Desc</th>
                                        <th>Assign to</th>                                        
                                    </tr>
                                    </thead>
                                    <tbody> ";
	foreach ($action_data as $key => $value) {
		# code...		
		$action_out = $action_out."<tr>"
				."<td>".$value['status']."</td>"
				."<td>".$value['action_time']."</td>"
				."<td>".$value['action_by']."</td>"
				."<td>".$value['action_desc']."</td>"
				."<td>".$value['action_to']."</td></tr>";
	}

	$action_out = $action_out."</tbody> </table>";
}

$get_case_data = $dbh -> prepare("SELECT * FROM `service_head` WHERE `service_id` = '".$_REQUEST['case_id']."'");
$get_case_data -> execute();
$case_data = $get_case_data -> fetch();

$get_created_user = $dbh -> prepare("SELECT * FROM `user` WHERE `user_id` = '".$case_data['created_by']."'");
$get_created_user -> execute();
$created_user = $get_created_user -> fetch();

$_CurrentDuty	= "";
if ($_REQUEST['assign'] == "" ) {
		# code...
	$_CurrentDuty	= $_SESSION['user'];
} else {
	# code...
	$_CurrentDuty = $_REQUEST['assign'];
}

$get_duty_data = $dbh -> prepare("SELECT * FROM `user` WHERE `user_id` = '".$_REQUEST['assign']."'");
$get_duty_data -> execute();
$assign_data = $get_duty_data -> fetch();

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

$mail->Subject = $case_data['service_subject'];
$mail->Body = "<!DOCTYPE html>
<html>

<body>
<h2>Case ID : ".$case_data['service_id']."</h2>
<h3>Subject : ".$case_data['service_subject']."</h3>
<h4>Case Status : ".$_REQUEST['status']."</h4>
<hr >
<table style='width:100%'>
<tbody>
<tr >
<td >&nbsp;<h4>Action by</h4></td>
<td >&nbsp;</td>
</tr>
<tr >
<td >&nbsp;Name</td>
<td >&nbsp;".$user_data['fname']."  ".$user_data['lname']."</td>
</tr>	
<tr >
<td >&nbsp;Contact</td>
<td >&nbsp;".$user_data['phone']."</td>
</tr>
<tr >
<td >&nbsp;Email</td>
<td >&nbsp;".$user_data['email']."</td>
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
<td >&nbsp;".$case_data['estimate_id']."</td>
</tr>
<tr >
<td >&nbsp;Project Name.</td>
<td >&nbsp;".$case_data['project_name']."</td>
</tr>
<tr >
<td >&nbsp;Assign to</td>
<td >&nbsp;".$assign_data['fname']."  ".$assign_data['lname']."</td>
</tr>
<tr >
<td >&nbsp;Device</td>
<td >&nbsp;".$device_out."</td>
</tr>
<tr >
<td >&nbsp;Description</td>
<td >&nbsp;".$_REQUEST['desc']."</td>
</tr>
<tr >
<tr >
<td >&nbsp;History</td>
<td >&nbsp;".$action_out."</td>
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
$mail->AddCC($created_user['email']);		
$mail->AddCC($_REQUEST['assign_email']);
$mail->AddCC($case_data['contact_email']);
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

echo json_encode($return)
	."Email has been sent \n"
	.$user_data['fname'].$user_data['lname']."\n"
	.$case_data['service_subject']."\n"
	.$device_out."\n"
	.$action_out."\n"
	.$_REQUEST['case_id']."\n"
	.$_REQUEST['old_status']."\n"
	.$_REQUEST['status']."\n"
	.$_REQUEST['desc']."\n"
	.$_REQUEST['assign']."\n"
	.$_REQUEST['assign_email'];

?>