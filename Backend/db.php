<?php
$servername = "localhost"; // Replace with your database server name
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "login_system"; // Replace with your database name

// Create a connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>