<?php
// connect to database
$conn = new mysqli("localhost", "root", "", "student_coworking_db");

// check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>