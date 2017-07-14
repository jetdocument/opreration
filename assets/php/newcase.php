<?php
# Validate Session

session_start();

#if($_SESSION['user']){ echo $_SESSION['user'];}

if (!isset($_SESSION['user'])) {
header('Location: login.html'); 
}

# get resource

require ("../../config/php_conf.cfg");

# prepare parameter

$prefix = $defualt_prefix;
$today = date('Ymd');
$number = null;
$db_number = null;

$get_current_number = "SELECT MAX(`number`) as number FROM `current_code` WHERE `prefix` = '".$prefix."' AND `date` = '".$today."'";

// $_REQUEST['subject']="POE Unstable";
// $_REQUEST['contact_name']="Axis";
// $_REQUEST['contact_number']="0891289182";
// $_REQUEST['contact_email']="";
// $_REQUEST['contact_company']="Bacom";
// $_REQUEST['estimate_id']="BC588888";
// $_SESSION['user']="Rattana";
// $_REQUEST['duty']="Chaithat";
// $_REQUEST['file']="poe.pdf";
// $_REQUEST['picture']="poe.png";
// $_REQUEST['urgent']="High";
// $_REQUEST['description']="Sometime";
// $_REQUEST['request_date']="2017-05-10"; 
$_REQUEST['status'] = $default_case_status;
$return['status']   = "Success";

# connect database

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

    $results = $dbh -> prepare($get_current_number);
    $results -> execute();
    $row = $results -> fetch();  

    if (isset($row['number'])) {
      # if number exists today then case id + 1              
      $db_number =  $row["number"];
      $row["number"] +=1;
      $number = sprintf("%03d", $row["number"]);
      $return['data']['case_id'] = $prefix.$today.$number;

      $update_new_number = "UPDATE `current_code` SET `number`='".$number."' WHERE `number` = '".$db_number."' AND `prefix` = '".$prefix."' AND `date` = '".$today."'";
      $insert_new_case = "INSERT INTO `service_head`(
     
      `service_id`, 
      `service_subject`, 
      `date`, 
      `contact_name`, 
      `contact_number`, 
      `contact_email`, 
      `contact_company`, 
      `estimate_id`, 
      `project_name`, 
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
      '".$_REQUEST['project_name']."',
      '".$_SESSION['user']."',
      '".$_REQUEST['duty']."',
      '".$_REQUEST['file']."',
      '".$_REQUEST['picture']."',
      '".$_REQUEST['urgent']."',
      '".$_REQUEST['description']."',
      '".$_REQUEST['request_date']."',
      '".$_REQUEST['status']."')";    

      try {          

          $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $dbh->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);      
          $dbh->beginTransaction();
          $stmt1 = $dbh->prepare($insert_new_case);                
          $stmt2 = $dbh->prepare($update_new_number);
          $stmt1 -> execute();
          $stmt2 -> execute();
          // $dbh->exec($insert_new_number);
          // $dbh->exec($insert_new_case);                                     
          $dbh->commit();
          $return['message'] = "Commit for more case at : ".date("d-m-y H:i:s")." : No. : ".$number;

        } catch (Exception $e) {
          $dbh->rollBack();
          $return['status']  = "error";
          $return['message'] = "Rollback Transaction";
          $return['data']['error'] = $e->getMessage();
          $number = null;
          $dbh = null;
        }

    } else {
      # code...  
      $number = 1;
      $number = sprintf("%03d", $number);    
      $insert_new_number = "INSERT INTO `current_code`(`prefix`, `date`, `number`) VALUES ('".$prefix."','".$today."','".$number."')";
      $insert_new_case = "INSERT INTO `service_head`(   
      `service_id`, 
      `service_subject`, 
      `date`, 
      `contact_name`, 
      `contact_number`, 
      `contact_email`, 
      `contact_company`, 
      `estimate_id`, 
      `project_name`, 
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
      '".$_REQUEST['project_name']."',
      '".$_SESSION['user']."',
      '".$_REQUEST['duty']."',
      '".$_REQUEST['file']."',
      '".$_REQUEST['picture']."',
      '".$_REQUEST['urgent']."',
      '".$_REQUEST['description']."',
      '".$_REQUEST['request_date']."',
      'Active')";

      try {
       
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);      
        $dbh->beginTransaction();
        $stmt1 = $dbh->prepare($insert_new_case);                
        $stmt2 = $dbh->prepare($insert_new_number);
        $stmt1 -> execute();
        $stmt2 -> execute();
        // $dbh->exec($insert_new_number);
        // $dbh->exec($insert_new_case);  
                                    
        $dbh->commit();
        $return['data']['case_id'] = $prefix.$today.$number;
        $return['message'] = "Commit a new case at : ".date("d-m-y H:i:s")." : No. : ".$number;

      } catch (Exception $e) {
        $dbh->rollBack();                
        $return['status']  = "error";
        $return['message'] = "Rollback Transaction";
        $return['data']['error'] = $e->getMessage();      
      } 

    }

} catch (Exception $e) {

  $return['status'] = "error";
  $return['message'] = "Query statement fail";
  $return['data']['error'] = $e->getMessage();

} finally {

  echo json_encode($return);
  $prefix = null;
  $today = null;
  $number = null;
  $db_number = null;
  $get_current_number = null;
  $number = null;
  $dbh = null;  

}