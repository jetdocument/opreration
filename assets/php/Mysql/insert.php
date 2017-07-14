

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

$sql = "INSERT INTO `user` (`no`, `id`, `pass`, `email`, `time`, `comment`) VALUES (NULL, 'admin', 'admin', 'admin@admin.com', NOW(), NOW());";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>