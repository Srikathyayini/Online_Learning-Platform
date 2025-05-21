<?php
$servername = "localhost";  // Change if using a remote server
$username = "root";         // Your database username
$password = "";             // Your database password (default is empty for XAMPP)
$dbname = "online_learning";  // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
