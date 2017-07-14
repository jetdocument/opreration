<select onchange="alert(this.options[this.selectedIndex].value);">

<?php
# Validate Session

// if (!isset($_SESSION['user'])) { header('Location: ../../login.html'); }

# get resource

require ("../../config/php_conf.cfg");

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

    $get_user = "SELECT `fname`, `lname`, `email` FROM `user`";

    $results = $dbh -> prepare($get_user);
    $results -> execute();
    // $result = $results -> fetch();
    $result = $results->fetchAll();  
    // echo $result[0]['email'];
    // echo $result[1]['email'];

    foreach ($result as $key => $value) {
      # code...
      echo "<option value=".$value['email'].">".$value['fname']." ".$value['lname']."</option>";

    }    
    

} catch (Exception $e) {

  echo "<option value=''>".$e->getMessage()."</option>";
  $return['data']['error'] = $e->getMessage();

} finally {
  
  $get_user = null;
  $dbh = null;  

}
?>
</select>