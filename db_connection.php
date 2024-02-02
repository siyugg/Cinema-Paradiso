<?php

$servername = "localhost";
$username = "f32ee";
$password = "f32ee";
$database = "project_ss";

// create connection to the MySQL database
$conn = new mysqli($servername, $username, $password, $database);
if($conn -> connect_error){
    die("Connection failed: " . $conn->connect_error);
}

?>