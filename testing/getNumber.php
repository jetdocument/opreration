<?php

require ("../config/php_conf.cfg");

$link = mysqli_connect($servername, $username, $password, $dbname);

if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL . "</br>";
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL . "</br>";
    exit;
}

echo "Success: A proper connection to MySQL was made! The my_db database is great." . PHP_EOL . "</br>";
echo "Host information: " . mysqli_get_host_info($link) . PHP_EOL . "</br>";

if ($result = $link->query("SELECT * FROM `service_head` WHERE 1")) {
    printf("Select returned %d rows.\n </br>", $result->num_rows);
    echo  json_encode($result). "</br>" . 
    	"current_field : " . $result->current_field. "</br>" .
    	"field_count : " . $result->field_count. "</br>" .
    	"lengths : " . $result->lengths. "</br>" .
    	"num_rows : " . $result->num_rows. "</br>" .
    	"type : " . $result->type. "</br>";

    if ($result->num_rows == 1) {
    	$row = $result->fetch_object();
    	echo "Running Number : " . $row->date . "</br>";
    } else {
    	while ($row = $result->fetch_object()){

	    	echo $row->date . "</br>";
	    	// print_r($row);
	        // $data[] = $row; 
	        
	    }   
    	
    }
      
    /* free result set */
    $result->close();
}

mysqli_close($link);
