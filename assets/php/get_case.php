<?php

require( "../../config/php_conf.cfg" );
$dbh = new PDO('mysql:host='.$servername.';dbname='.$dbname, $username, $password);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
$dbh->exec("set names utf8");

// $_REQUEST['service_id'] = "OS20170511005";

$get_data_by_case = "SELECT * FROM `service_head` WHERE `service_id` = '".$_REQUEST['service_id']."'";
$get_action_by_case = "SELECT * FROM `service_action` WHERE `service_id` = '".$_REQUEST['service_id']."' ORDER BY `action_time` DESC";

$data_results = $dbh -> prepare($get_data_by_case);
$data_results -> execute();
$result[0] = $data_results->fetchAll();

$action_results = $dbh -> prepare($get_action_by_case);
$action_results -> execute();
$result[1] = $action_results->fetchAll();

echo json_encode($result);

?>