<?php

$servername = "MYSQL_SERVERNAME";
$username = "MYSQL_LOGIN";
$password = "MYSQL_PASSWORD";
$db = "beerleage";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

