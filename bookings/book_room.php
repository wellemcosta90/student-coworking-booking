<?php
// protect page
include '../includes/auth.php';
include '../includes/header.php';

// connect to database
include '../config/db.php';

// message variable
$message = "";

// check if room type exists in URL
if (!isset($_GET['type'])) {
    die("Room type not selected.");
}

// get room type from URL
$room_type = $_GET['type'];

// allow only valid room types
if ($room_type != "individual" && $room_type != "meeting") {
    die("Invalid room type.");
}

// get one available room based on selected type
$stmt = $conn->prepare("
    SELECT * FROM rooms
    WHERE room_type = ?
    AND status = 'available'
    LIMIT 1
");

$stmt->bind_param("s", $room_type);
$stmt->execute();
$result = $stmt->get_result();
$room = $result->fetch_assoc();

// check if available room exists
if (!$room) {
    die("No available rooms found for this type.");
}

// get selected room id
$room_id = $room['room_id'];

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

    // check if date is in the past
    } elseif ($booking_date < date("Y-m-d")) {
        $message = "Booking date cannot be in the past.";

    // check opening hours
    } elseif ($start_time < "08:00:00" || $end_time > "20:00:00") {
        $message = "Bookings are only allowed between 08:00 and 20:00.";

    // check if end time is after start time
    } elseif ($end_time <= $start_time) {
        $message = "End time must be after start time.";

    } else {

        // check if selected room already has booking at this time
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
                (user_id, room_id, booking_date, start_time, end_time, booking_purpose, status)
                VALUES (?, ?, ?, ?, ?, ?, 'booked')
            ");

            $stmt->bind_param("iissss", $user_id, $room_id, $booking_date, $start_time, $end_time, $booking_purpose);

            if ($stmt->execute()) {

                // show confirmation message
                $message = "Booking confirmed successfully!";

                // save confirmation details to show on screen
                $confirmation = [
                    "room_type" => $room_type,
                    "date" => $booking_date,
                    "start_time" => substr($start_time, 0, 5),
                    "end_time" => substr($end_time, 0, 5),
                    "purpose" => $booking_purpose
                ];

            } else {
                $message = "Error creating booking.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Room</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container">

    <h1>Book <?php echo htmlspecialchars(ucfirst($room_type)); ?> Room</h1>

    <?php if ($room_type == "individual") { ?>
        <p>This room type is for 1 person only.</p>
    <?php } else { ?>
        <p>This room type is for meetings from 2 to 15 people.</p>
    <?php } ?>

    <hr>

    <?php if (!empty($message)) { ?>
        <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
    <?php } ?>

    <?php if (isset($confirmation)) { ?>

        <h3>Booking Details</h3>

        <p><strong>Room Type:</strong> <?php echo htmlspecialchars(ucfirst($confirmation['room_type'])); ?></p>
        <p><strong>Date:</strong> <?php echo htmlspecialchars($confirmation['date']); ?></p>
        <p>
            <strong>Time:</strong>
            <?php echo htmlspecialchars($confirmation['start_time']); ?>
            -
            <?php echo htmlspecialchars($confirmation['end_time']); ?>
        </p>
        <p><strong>Purpose:</strong> <?php echo htmlspecialchars($confirmation['purpose']); ?></p>

        <br>
        <a href="my_bookings.php">View My Bookings</a>
        <br><br>
        <a href="../rooms/rooms.php">Book Another Room</a>

    <?php } else { ?>

        <form method="POST">

            Booking Date:
            <input type="date" name="booking_date" required><br><br>

            Start Time:
            <select name="start_time" required>
                <option value="">Select start time</option>
                <?php for ($hour = 8; $hour <= 19; $hour++) { 
                    $time = sprintf("%02d:00:00", $hour);
                    $label = sprintf("%02d:00", $hour);
                ?>
                    <option value="<?php echo $time; ?>"><?php echo $label; ?></option>
                <?php } ?>
            </select><br><br>

            End Time:
            <select name="end_time" required>
                <option value="">Select end time</option>
                <?php for ($hour = 9; $hour <= 20; $hour++) { 
                    $time = sprintf("%02d:00:00", $hour);
                    $label = sprintf("%02d:00", $hour);
                ?>
                    <option value="<?php echo $time; ?>"><?php echo $label; ?></option>
                <?php } ?>
            </select><br><br>

            Purpose:
            <select name="booking_purpose" required>
                <option value="">Select purpose</option>

                <?php if ($room_type == 'individual') { ?>
                    <option value="Individual Study">Individual Study</option>
                    <option value="Individual Work">Individual Work</option>
                <?php } else { ?>
                    <option value="Group Study">Group Study</option>
                    <option value="Meeting">Meeting</option>
                <?php } ?>
            </select><br><br>

            <button type="submit">Confirm Booking</button>

        </form>

        <br>
        <a href="../rooms/rooms.php">Back to Room Types</a>

    <?php } ?>

    <br><br>
    <a href="../dashboard.php">Back to Dashboard</a>

</div>
</body>
</html>
