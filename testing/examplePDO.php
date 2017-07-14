<?php

require ("../config/php_conf.cfg");

# connect database

try { 

	$dbh = new PDO('mysql:host='.$servername.';dbname='.$dbname, $username, $password, array(PDO::ATTR_PERSISTENT => true));
	$return['message'] = "Connected";
} catch (Exception $e) {
	$return['data']['error'] = $e->getMessage();
}


# transaction

try { 
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  	$dbh->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);      
  	$dbh->beginTransaction();
  	$return['message'] = "Transaction Begin";

  	$result1 = $dbh->exec("INSERT INTO `current_code` (`id`, `prefix`, `date`, `number`, `created_at`, `updated_at`) VALUES (NULL, 'OS', '2017-05-10', '007', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
  
  	$result2 = $dbh->exec("INSERT INTO `current_code` (`id`, `prefix`, `date`, `number`, `created_at`, `updated_at`) VALUES (NULL, 'OS', '2017-05-10', '008', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");

  	$return['message'] = "Query Success";
  	$dbh->commit();
  	$return['message'] = "Transaction Commit";
	
} catch (Exception $e) {
	$dbh->rollBack();
	$return['status']  = "error";
  	$return['message'] = $e->getMessage();
}

$dbh = null;

echo json_encode($return);