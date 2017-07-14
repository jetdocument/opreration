<?php
session_start();
require 'PHPMailer/PHPMailerAutoload.php';
require ("../../config/php_conf.cfg");

// header('Content-Type: text/html; charset=utf-8');

$dbh = new PDO('mysql:host='.$servername.';dbname='.$dbname, $username, $password);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
$dbh->exec("set names utf8");

$get_user_by_id = "

SELECT *
FROM
  `user`
WHERE
  `user_id` = '".$_REQUEST['duty']."'
";

$results = $dbh -> prepare($get_user_by_id);
$results -> execute();
$result = $results->fetch();

/* Just data from js post

*case_id       : json.data.case_id,
*subject       : subject,
*c_name        : c_name,
*c_number      : c_number,
*c_email       : c_email,
*action_email  : "thawatchai.bacom@gmail.com",
*c_company     : c_company,
*estimate_id   : estimate_id,
*duty          : duty,
*urgent        : urgent,
*desc          : desc,
*request_date  : request_date
*/

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

$get_desc_by_id = "SELECT * FROM `service_details` WHERE `service_id` = '".$_REQUEST['case_id']."'";

$detail_results = $dbh -> prepare($get_desc_by_id);
$detail_results -> execute();
$detail_result = $detail_results->fetchAll();
$service_details = null;
foreach ($detail_result as $keys => $values) {                                            
# code...
$keys = $keys+1;
$service_details = $service_details."<br>".
$keys." : ".$values['type']." : ".$values['brand']." : ".$values['model']." : ".$values['serial']." : ".$values['description'];
}




$mail->Subject = $_REQUEST['subject'];
$mail->Body = "<!DOCTYPE html>
<html>
<body>
<h2>Case ID : ".$_REQUEST['case_id']."</h2>
<h3>Subject : ".$_REQUEST['subject']."</h3>
<h3>The Serverity of Problem : ".$_REQUEST['urgent']."</h3>
<hr >
<table style='width:100%'>
<tbody>
<tr >
<td >&nbsp;<h4>Request Information</h4></td>
<td >&nbsp;</td>
</tr>
<tr >
<td >&nbsp;Customer Name</td>
<td >&nbsp;".$_REQUEST['c_name']."</td>
</tr>
<tr >
<td >&nbsp;Customer Company</td>
<td >&nbsp;".$_REQUEST['c_company']."</td>
</tr>
<tr >
<td >&nbsp;Contact Number</td>
<td >&nbsp;".$_REQUEST['c_number']."</td>
</tr>
<tr >
<td >&nbsp;Customer Email</td>
<td >&nbsp;".$_REQUEST['c_email']."</td>
</tr>
<tr >
<td >&nbsp;</td>
<td >&nbsp;</td>
</tr>
<tr >
<td >&nbsp;<h4>Case Description</h4></td>
<td >&nbsp;</td>
</tr>
<tr >
<td >&nbsp;Project No.</td>
<td >&nbsp;".$_REQUEST['estimate_id']."</td>
</tr>
<tr >
<td >&nbsp;Project Name.</td>
<td >&nbsp;".$_REQUEST['project_name']."</td>
</tr>
<tr >
<td >&nbsp;Action To</td>
<td >&nbsp;".$result['fname']." ".$result['lname']."</td>
</tr>
<tr >
<td >&nbsp;Action Date</td>
<td >&nbsp;".$_REQUEST['request_date']."</td>
</tr>
<tr >
<td >&nbsp;Devices</td>
<td >&nbsp;".$service_details."</td>
</tr>
<tr >
<td >&nbsp;Comment</td>
<td >&nbsp;".$_REQUEST['desc']."</td>
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


$mail->AddAddress($_REQUEST['c_email']);
$mail->AddCC($_REQUEST['action_email']);
$mail->AddCC($_SESSION['data']['email']);
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



