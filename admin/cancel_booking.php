<?php
// protect page
include '../includes/auth.php';

// only admin can access
if ($_SESSION['role'] != 'admin') {
    die("Access denied.");
}

// connect database
include '../config/db.php';

// check booking id
if (!isset($_GET['id'])) {
    die("Booking ID not found.");
}

$booking_id = $_GET['id'];

// update status to cancelled
$stmt = $conn->prepare("
    UPDATE bookings
    SET status = 'cancelled'
    WHERE booking_id = ?
");

$stmt->bind_param("i", $booking_id);

if ($stmt->execute()) {
    header("Location: manage_bookings.php");
    exit();
} else {
    echo "Error cancelling booking.";
}
?>