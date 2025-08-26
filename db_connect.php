<?php
$servername = "localhost";
$username = "root";
$password = ""; // Your MySQL password, which is usually empty for XAMPP
$dbname = "employee_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>