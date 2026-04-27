<?php
// connect to database
include '../config/db.php';

// variable to show messages
$message = "";

// get room id from URL
$room_id = $_GET['id'];

// get current room data from database
$stmt = $conn->prepare("SELECT * FROM rooms WHERE room_id = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();
$room = $result->fetch_assoc();

// check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // get updated data from form
    $room_name = trim($_POST['room_name']);
    $room_type = $_POST['room_type'];
    $capacity = $_POST['capacity'];
    $status = $_POST['status'];

    // basic validation
    if (empty($room_name) || empty($room_type) || empty($capacity)) {
        $message = "All fields are required.";
    } else {

        // update room in database using prepared statement
        $stmt = $conn->prepare("UPDATE rooms SET room_name = ?, room_type = ?, capacity = ?, status = ? WHERE room_id = ?");
        $stmt->bind_param("ssisi", $room_name, $room_type, $capacity, $status, $room_id);

        if ($stmt->execute()) {
            $message = "Room updated successfully.";

            // refresh data after update
            $stmt = $conn->prepare("SELECT * FROM rooms WHERE room_id = ?");
            $stmt->bind_param("i", $room_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $room = $result->fetch_assoc();

        } else {
            $message = "Error updating room.";
        }
    }
}
?>

<h2>Edit Room</h2>

<?php if (!empty($message)) { ?>
    <p><?php echo htmlspecialchars($message); ?></p>
<?php } ?>

<form method="POST">

    Room Name:
    <input type="text" name="room_name" value="<?php echo htmlspecialchars($room['room_name']); ?>" required><br><br>

    Room Type:
    <select name="room_type">
        <option value="meeting" <?php if ($room['room_type'] == 'meeting') echo 'selected'; ?>>Meeting</option>
        <option value="individual" <?php if ($room['room_type'] == 'individual') echo 'selected'; ?>>Individual</option>
    </select><br><br>

    Capacity:
    <input type="number" name="capacity" value="<?php echo htmlspecialchars($room['capacity']); ?>" required><br><br>

    Status:
    <select name="status">
        <option value="available" <?php if ($room['status'] == 'available') echo 'selected'; ?>>Available</option>
        <option value="unavailable" <?php if ($room['status'] == 'unavailable') echo 'selected'; ?>>Unavailable</option>
    </select><br><br>

    <button type="submit">Update Room</button>

</form>

<br>
<a href="rooms.php">Back to Rooms</a>