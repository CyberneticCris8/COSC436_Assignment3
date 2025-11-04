<?php 
// server information for connection
$server = "localhost";
$db_username = "436_mysql_user";
$db_password = "123pwd456"; 
$db = "436db";

// creates connection
$conn = new mysqli($server, $db_username, $db_password, $db);

// kills connection if an error occurs
if ($conn->connect_error) {
  die("Connection Failed: ". $conn->connect_error); 
}
?>