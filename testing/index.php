<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

function genCode($conn)
{
	$sql = "SELECT `Id`, `Date`, `Number` FROM `running` ORDER By Id DESC LIMIT 1";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	        $running_number = $row["Number"];
	    }

	    $new_number = $running_number + 1; 
	}
	else
	{
		$new_number   = 1;
	}
	
	$running_date = date('Ymd');
	
	$sql = "INSERT INTO `running` (`Date`, `Number`) VALUES('{$running_date}', '{$new_number}');";
	$conn->query($sql);

	return $running_date . '-' . str_pad($new_number, 3, 0, STR_PAD_LEFT);
}

$data = array('Jon', 'Peter', 'James');
foreach($data as $name)
{
	$code= genCode($conn);
	$sql = "INSERT INTO `user` (`Code`, `Name`) VALUES('{$code}','{$name}');";
	$conn->query($sql);
}

// echo genCode($conn);
// print_r($result);
$conn->close(); 
?>