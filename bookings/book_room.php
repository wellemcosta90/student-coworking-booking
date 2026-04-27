<?php
// protect page
include '../includes/auth.php';

// connect to database
include '../config/db.php';

// message variable
$message = "";

// check if room id exists in URL
if (!isset($_GET['id'])) {
    die("Room ID not found.");
}

// get room id
$room_id = $_GET['id'];

// get room information
$stmt = $conn->prepare("SELECT * FROM rooms WHERE room_id = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();
$room = $result->fetch_assoc();

// check if room exists
if (!$room) {
    die("Room not found.");
}

// check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // get booking data
    $user_id = $_SESSION['user_id'];
    $booking_date = $_POST['booking_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $booking_purpose = trim($_POST['booking_purpose']);

    // check required fields
    if (empty($booking_date) || empty($start_time) || empty($end_time) || empty($booking_purpose)) {
        $message = "All fields are required.";

    // check if room is unavailable
    } elseif ($room['status'] == 'unavailable') {
        $message = "This room is currently unavailable.";

    // check if date is in the past
    } elseif ($booking_date < date("Y-m-d")) {
        $message = "Booking date cannot be in the past.";

    // check if end time is after start time
    } elseif ($end_time <= $start_time) {
        $message = "End time must be after start time.";

    } else {

        // check if there is already a booking at the selected time
        $stmt = $conn->prepare("
            SELECT * FROM bookings
            WHERE room_id = ?
            AND booking_date = ?
            AND status = 'booked'
            AND start_time < ?
            AND end_time > ?
        ");

        $stmt->bind_param("isss", $room_id, $booking_date, $end_time, $start_time);
        $stmt->execute();
        $conflict_result = $stmt->get_result();

        // if conflict exists, do not create booking
        if ($conflict_result->num_rows > 0) {
            $message = "This room is already booked at this time.";
        } else {

            // insert booking into database
            $stmt = $conn->prepare("
                INSERT INTO bookings 
                (user_id, room_id, booking_date, start_time, end_time, booking_purpose)
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            $stmt->bind_param("iissss", $user_id, $room_id, $booking_date, $start_time, $end_time, $booking_purpose);

            if ($stmt->execute()) {
                $message = "Booking created successfully!";
            } else {
                $message = "Error creating booking.";
            }
        }
    }
}
?>

<h2>Book Room</h2>

<p><strong>Room:</strong> <?php echo htmlspecialchars($room['room_name']); ?></p>
<p><strong>Type:</strong> <?php echo htmlspecialchars($room['room_type']); ?></p>
<p><strong>Capacity:</strong> <?php echo htmlspecialchars($room['capacity']); ?></p>
<p><strong>Status:</strong> <?php echo htmlspecialchars($room['status']); ?></p>

<?php if (!empty($message)) { ?>
    <p><?php echo htmlspecialchars($message); ?></p>
<?php } ?>

<form method="POST">

    Booking Date:
    <input type="date" name="booking_date" required><br><br>

    Start Time:
    <input type="time" name="start_time" required><br><br>

    End Time:
    <input type="time" name="end_time" required><br><br>

    Purpose:
    <select name="booking_purpose" required>
        <option value="">Select purpose</option>
        <option value="Individual Study">Individual Study</option>
        <option value="Individual Work">Individual Work</option>
        <option value="Group Study">Group Study</option>
        <option value="Meeting">Meeting</option>
    </select><br><br>

    <button type="submit">Confirm Booking</button>

</form>

<br>
<a href="../rooms/rooms.php">Back to Rooms</a>