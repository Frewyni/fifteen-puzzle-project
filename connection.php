<?php

$conn = new mysqli('localhost', 'fgodifey1', 'fgodifey1', 'fgodifey1');

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
echo 'Connected successfully to MySQL database. Server version: ' . $conn->server_info;

$conn->close();
?>
