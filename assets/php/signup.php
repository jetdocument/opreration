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

$add_user = "INSERT
                  INTO
                    `user`(
                      `user_id`,
                      `pass`,
                      `fname`,
                      `lname`,
                      `gender`,
                      `email`,
                      `phone`,
                      `company`
                    )
                  VALUES(
                    '".$_REQUEST['user']."',
                    '".$_REQUEST['pass']."',
                    '".$_REQUEST['fname']."',
                    '".$_REQUEST['lname']."',
                    '".$_REQUEST['gender']."',
                    '".$_REQUEST['email']."',
                    '".$_REQUEST['phone']."',
                    '".$_REQUEST['company']."'
                  )";

try {
       
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);      
        $dbh->beginTransaction();
        $stmt = $dbh->prepare($add_user); 
        $stmt -> execute();         
        $dbh->commit();
        $return['data']['user'] = $_REQUEST['user'];        
        $return['message'] = "You are beginning at : ".date("d-m-y H:i:s");

      } catch (Exception $e) {

        $dbh->rollBack();                
        $return['status']  = "error";
        $return['message'] = "Rollback Transaction";
        $return['data']['error'] = $e->getMessage(); 

      } finally {

        echo json_encode($return);
        $dbh = null; 
      }

