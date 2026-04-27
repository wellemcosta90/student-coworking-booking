<?php
$conn = new mysqli("localhost", "root", "", "student_coworking");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>