<?php
// db.php - Database Connection File

$servername = "127.0.0.1"; // Use IP to force TCP
$username = "root";
$password = "";
$dbname = "ecommerce_db";
$port = 3307; // Discovered from config.inc.php

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname, $port);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
