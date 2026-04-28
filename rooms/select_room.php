<?php
// protect page
include '../includes/auth.php';

// connect to database
include '../config/db.php';

// check if type exists in URL
if (!isset($_GET['type'])) {
    die("Room type not selected.");
}

// get room type from URL
$room_type = $_GET['type'];

// allow only valid room types
if ($room_type != "individual" && $room_type != "meeting") {
    die("Invalid room type.");
}

// get only rooms with selected type and available status
$stmt = $conn->prepare("SELECT * FROM rooms WHERE room_type = ? AND status = 'available'");
$stmt->bind_param("s", $room_type);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Room</title>
</head>
<body>

    <h1>Select a <?php echo htmlspecialchars(ucfirst($room_type)); ?> Room</h1>

    <?php if ($room_type == "individual") { ?>
        <p>Individual rooms are for 1 person only.</p>
    <?php } else { ?>
        <p>Meeting rooms are for groups from 2 to 15 people.</p>
    <?php } ?>

    <hr>

    <?php if ($result->num_rows > 0) { ?>

        <table border="1" cellpadding="10">
            <tr>
                <th>Room Name</th>
                <th>Capacity</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php while ($room = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($room['room_name']); ?></td>
                    <td><?php echo htmlspecialchars($room['capacity']); ?></td>
                    <td><?php echo htmlspecialchars($room['status']); ?></td>
                    <td>
                        <a href="../bookings/book_room.php?id=<?php echo $room['room_id']; ?>">
                            Book This Room
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </table>

    <?php } else { ?>

        <p>No available rooms found for this type.</p>

    <?php } ?>

    <br>
    <a href="rooms.php">Back to Room Types</a>
    <br><br>
    <a href="../dashboard.php">Back to Dashboard</a>

</body>
</html>