<?php
// protect page
include '../includes/auth.php';
include '../includes/header.php';

// connect to database
include '../config/db.php';

// only admin or organiser can delete rooms
if ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'organiser') {
    die("Access denied. Only admins or organisers can delete rooms.");
}

// check if id exists
if (!isset($_GET['id'])) {
    die("Room ID not found.");
}

// get id
$room_id = $_GET['id'];

// delete room using prepared statement
$stmt = $conn->prepare("DELETE FROM rooms WHERE room_id = ?");
$stmt->bind_param("i", $room_id);

if ($stmt->execute()) {
    // redirect back after delete
    header("Location: rooms.php");
    exit();
} else {
    echo "Error deleting room.";
}
?>
