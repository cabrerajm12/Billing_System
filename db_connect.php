<?php
$servername = "localhost";
$username = "root";   // Default for XAMPP
$password = "";       // Leave blank if none
$dbname = "student_portal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
