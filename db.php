<?php
$host = "localhost";
$username = "fgodifey1";
$password = "fgodifey1";  
$database = "fgodifey1";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
