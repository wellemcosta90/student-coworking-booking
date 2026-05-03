<?php
// protect page
include '../includes/auth.php';
include '../includes/header.php';

// only admin can access this page
if ($_SESSION['role'] != 'admin') {
    die("Access denied. Only admins can manage bookings.");
}

// connect database
include '../config/db.php';

// get all bookings with user and room information
$result = $conn->query("
    SELECT 
        bookings.booking_id,
        bookings.booking_date,
        bookings.start_time,
        bookings.end_time,
        bookings.booking_purpose,
        bookings.status,
        users.name AS user_name,
        users.email AS user_email,
        rooms.room_type
    FROM bookings
    JOIN users ON bookings.user_id = users.user_id
    JOIN rooms ON bookings.room_id = rooms.room_id
    ORDER BY bookings.booking_date, bookings.start_time
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Bookings</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="container">

<h1>Manage Bookings</h1>

<?php if ($result->num_rows > 0) { ?>

<table border="1" cellpadding="10">
    <tr>
        <th>User</th>
        <th>Email</th>
        <th>Room Type</th>
        <th>Date</th>
        <th>Time</th>
        <th>Purpose</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    <?php while ($booking = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($booking['user_name']); ?></td>
            <td><?php echo htmlspecialchars($booking['user_email']); ?></td>
            <td><?php echo htmlspecialchars(ucfirst($booking['room_type'])); ?></td>
            <td><?php echo htmlspecialchars($booking['booking_date']); ?></td>
            <td>
                <?php echo substr($booking['start_time'], 0, 5); ?> -
                <?php echo substr($booking['end_time'], 0, 5); ?>
            </td>
            <td><?php echo htmlspecialchars($booking['booking_purpose']); ?></td>
            <td><?php echo htmlspecialchars($booking['status']); ?></td>
            <td>
    <?php if ($booking['status'] == 'booked') { ?>
        <a href="edit_booking.php?id=<?php echo $booking['booking_id']; ?>">Edit</a>
        |
        <a href="cancel_booking.php?id=<?php echo $booking['booking_id']; ?>">Cancel</a>
    <?php } ?>
</td>
        </tr>
    <?php } ?>

</table>

<?php } else { ?>

<p>No bookings found.</p>

<?php } ?>

<br>
<a href="../dashboard.php">Back to Dashboard</a>

</div>
</body>
</html>