<?php
// protect page
include '../includes/auth.php';

// connect database
include '../config/db.php';

// message variable
$message = "";

// check if booking id exists
if (!isset($_GET['id'])) {
    die("Booking ID not found.");
}

// get booking id
$booking_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// get booking details for logged user only
$stmt = $conn->prepare("
    SELECT bookings.*, rooms.room_type
    FROM bookings
    JOIN rooms ON bookings.room_id = rooms.room_id
    WHERE bookings.booking_id = ? AND bookings.user_id = ?
");

$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

// check if booking exists
if (!$booking) {
    die("Booking not found or you do not have permission to edit it.");
}

// check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // get updated data
    $booking_date = $_POST['booking_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // validation
    if (empty($booking_date) || empty($start_time) || empty($end_time)) {
        $message = "All fields are required.";

    } elseif ($booking_date < date("Y-m-d")) {
        $message = "Booking date cannot be in the past.";

    } elseif ($start_time < "08:00:00" || $end_time > "20:00:00") {
        $message = "Bookings are only allowed between 08:00 and 20:00.";

    } elseif ($end_time <= $start_time) {
        $message = "End time must be after start time.";

    } else {

        // check if another booking already exists at the selected time
        $stmt = $conn->prepare("
            SELECT * FROM bookings
            WHERE room_id = ?
            AND booking_date = ?
            AND status = 'booked'
            AND booking_id != ?
            AND start_time < ?
            AND end_time > ?
        ");

        $stmt->bind_param(
            "isiss",
            $booking['room_id'],
            $booking_date,
            $booking_id,
            $end_time,
            $start_time
        );

        $stmt->execute();
        $conflict_result = $stmt->get_result();

        if ($conflict_result->num_rows > 0) {
            $message = "This room is already booked at this time.";
        } else {

            // update booking date and time
            $stmt = $conn->prepare("
                UPDATE bookings
                SET booking_date = ?, start_time = ?, end_time = ?
                WHERE booking_id = ? AND user_id = ?
            ");

            $stmt->bind_param("sssii", $booking_date, $start_time, $end_time, $booking_id, $user_id);

            if ($stmt->execute()) {
                header("Location: my_bookings.php");
                exit();
            } else {
                $message = "Error updating booking.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Booking</title>
</head>
<body>

<h1>Edit Booking</h1>

<p><strong>Room Type:</strong> <?php echo htmlspecialchars(ucfirst($booking['room_type'])); ?></p>
<p><strong>Current Date:</strong> <?php echo htmlspecialchars($booking['booking_date']); ?></p>
<p>
    <strong>Current Time:</strong>
    <?php echo substr($booking['start_time'], 0, 5); ?> -
    <?php echo substr($booking['end_time'], 0, 5); ?>
</p>

<?php if (!empty($message)) { ?>
    <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
<?php } ?>

<form method="POST">

    New Date:
    <input type="date" name="booking_date" value="<?php echo htmlspecialchars($booking['booking_date']); ?>" required><br><br>

    New Start Time:
    <select name="start_time" required>
        <?php for ($hour = 8; $hour <= 19; $hour++) { 
            $time = sprintf("%02d:00:00", $hour);
            $label = sprintf("%02d:00", $hour);
        ?>
            <option value="<?php echo $time; ?>" <?php if ($booking['start_time'] == $time) echo 'selected'; ?>>
                <?php echo $label; ?>
            </option>
        <?php } ?>
    </select><br><br>

    New End Time:
    <select name="end_time" required>
        <?php for ($hour = 9; $hour <= 20; $hour++) { 
            $time = sprintf("%02d:00:00", $hour);
            $label = sprintf("%02d:00", $hour);
        ?>
            <option value="<?php echo $time; ?>" <?php if ($booking['end_time'] == $time) echo 'selected'; ?>>
                <?php echo $label; ?>
            </option>
        <?php } ?>
    </select><br><br>

    <button type="submit">Update Booking</button>

</form>

<br>
<a href="my_bookings.php">Back to My Bookings</a>

</body>
</html>