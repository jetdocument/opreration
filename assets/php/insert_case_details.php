<?php
// echo $_REQUEST['brand'][0]['value'];
// print_r($_REQUEST['brand']);
// echo sizeof($_REQUEST['brand']);

/* data from js post
type    : type,
brand   : brand,
model   : model,
serial  : serial,
desc    : desc
*/

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



try {          

  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $dbh->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);      
  $dbh->beginTransaction();



  // $stmt1 = $dbh->prepare($insert_new_case);                
  // $stmt2 = $dbh->prepare($update_new_number);
  // $stmt1 -> execute();
  // $stmt2 -> execute();
  // $dbh->exec($insert_new_number);
  // $dbh->exec($insert_new_case);

  // $_REQUEST['case_id'] = "OS20170511002";
	for ($i=0; $i < sizeof($_REQUEST['brand']); $i++) {

    if (
      $_REQUEST['type'][$i]['value'] != "" &&
      $_REQUEST['brand'][$i]['value'] != "" &&
      $_REQUEST['model'][$i]['value'] != "" &&
      $_REQUEST['serial'][$i]['value'] != "" &&
      $_REQUEST['description'][$i]['value'] != ""
      ) {
      # code...
      
      $insert_service_details = "
      INSERT
          INTO
            `service_details`(
              `service_id`,
              `type`,
              `brand`,
              `model`,
              `serial`,
              `description`
            )
          VALUES(
            '".$_REQUEST['case_id']."',
            '".$_REQUEST['type'][$i]['value']."',
            '".$_REQUEST['brand'][$i]['value']."',
            '".$_REQUEST['model'][$i]['value']."',
            '".$_REQUEST['serial'][$i]['value']."',
            '".$_REQUEST['description'][$i]['value']."'
          )"; 
    # code...
    // echo $i.$_REQUEST['brand'][$i]['value'].":";

          $stmt = $dbh->prepare($insert_service_details);
          $stmt -> execute();
    }
		
	}



  $dbh->commit();
  $return['message'] = "Service details insert complete";

} catch (Exception $e) {
  $dbh->rollBack();
  $return['status']  = "error";
  $return['message'] = "Rollback Transaction";
  $return['data']['error'] = $e->getMessage();
  $number = null;
  $dbh = null;
} finally {

	echo json_encode($return);
  	$dbh = null;  

}


// for ($i=0; $i < sizeof($_REQUEST['brand']); $i++) { 
// 	# code...
// 	echo $i.$_REQUEST['brand'][$i]['value'].":";
// }




// echo json_encode($_REQUEST['brand']);