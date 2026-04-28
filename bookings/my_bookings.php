<?php
// protect page
include '../includes/auth.php';

// connect database
include '../config/db.php';

// get current user id
$user_id = $_SESSION['user_id'];

// get bookings for logged user only
$stmt = $conn->prepare("
    SELECT bookings.*, rooms.room_type
    FROM bookings
    JOIN rooms ON bookings.room_id = rooms.room_id
    WHERE bookings.user_id = ?
    ORDER BY booking_date, start_time
");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookings</title>
</head>
<body>

<h1>My Bookings</h1>

<?php if ($result->num_rows > 0) { ?>

<table border="1" cellpadding="10">
    <tr>
        <th>Room Type</th>
        <th>Date</th>
        <th>Time</th>
        <th>Purpose</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

<?php while ($booking = $result->fetch_assoc()) { ?>
<tr>
    <td><?php echo htmlspecialchars(ucfirst($booking['room_type'])); ?></td>

    <td><?php echo htmlspecialchars($booking['booking_date']); ?></td>

    <td>
        <?php echo substr($booking['start_time'],0,5); ?> -
        <?php echo substr($booking['end_time'],0,5); ?>
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

</body>
</html>