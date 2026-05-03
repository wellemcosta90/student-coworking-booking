<?php
// protect page
include '../includes/auth.php';
include '../includes/header.php';

// connect database
include '../config/db.php';

// check if booking id exists
if (!isset($_GET['id'])) {
    die("Booking ID not found.");
}

// get booking id
$booking_id = $_GET['id'];

// get user id
$user_id = $_SESSION['user_id'];

// update booking status to cancelled (instead of deleting)
$stmt = $conn->prepare("
    UPDATE bookings 
    SET status = 'cancelled'
    WHERE booking_id = ? AND user_id = ?
");

$stmt->bind_param("ii", $booking_id, $user_id);

if ($stmt->execute()) {
    // redirect back to bookings page
    header("Location: my_bookings.php");
    exit();
} else {
    echo "Error cancelling booking.";
}
?>