<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bacom";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT `no`, `id`, `pass`, `email`, `time` FROM `user`";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo 
        	"no: " . $row["no"]. 
        	" - id: " . $row["id"].
        	" - pass: " . $row["pass"]. 
        	" - email: " . $row["email"].
        	" - time: " . $row["time"]. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>