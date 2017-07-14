<?php
// $servername = "localhost";
// $dbname = "bacom";
// $username = "root";
// $password = "";
require ("../../../config/php_conf.cfg");
try {
    $dbh = new PDO('mysql:host='.$servername.';dbname='.$dbname, $username, $password, array(PDO::ATTR_PERSISTENT => true));
    echo "Connected\n";

    $prefix = "OS";
    // $today	= date('Y-m-d');
    $today	= '2017-05-10';

    $str = $dbh -> prepare( "SELECT MAX(`number`) as number FROM `current_code` WHERE `prefix` = '".$prefix."' AND `date` = '".$today."'" );

	$str -> execute();
	$result = $str -> fetch();
	echo $result ["number"];    

    // foreach($dbh->query("SELECT MAX(`number`) as number FROM `current_code` WHERE `prefix` = '".$prefix."' AND `date` = '".$today."'") as $row) {
    //     print_r($row);
    // }
    // $dbh = null;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

try {  
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $dbh->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);      
  $dbh->beginTransaction();
  echo "Transaction begin\n";

  // $result1 = $dbh->exec("INSERT INTO `current_code` (`id`, `prefix`, `date`, `number`, `created_at`, `updated_at`) VALUES (NULL, 'OS', '2017-05-10', '007', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
  // echo "Success query\n";
  // $result2 = $dbh->exec("INSERT INTO `current_code` (`id`, `prefix`, `date`, `number`, `created_at`, `updated_at`) VALUES (NULL, 'OS', '2017-05-10', '008', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
  // echo "Success query2\n", json_encode($result1)."</br>".json_encode($result2)."</br>";
  
  
  $dbh->commit();
  echo "Success Commit";
  
} catch (Exception $e) {
  $dbh->rollBack();  
  echo "Failed 00: " . $e->getMessage();
}

$dbh = null;