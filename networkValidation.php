<?php

$servername = "localhost";
$username = "CST8257";
$password = "cakeall";
$dbname = "mydbsqliobj";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$servername = "localhost";
$username = "CST8257";
$password = "cakeall";
$dbname = "mydbsqliobj";

$conn2 = new PDO("mysql:host=$servername;dbname=$dbname", $username,
        $password);