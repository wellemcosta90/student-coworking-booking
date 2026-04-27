<?php
// connect to database
include '../config/db.php';

// variable to show messages
$message = "";

// check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // get form data
    $room_name = trim($_POST['room_name']);
    $room_type = $_POST['room_type'];
    $capacity = $_POST['capacity'];
    $status = $_POST['status'];

    // basic validation
    if (empty($room_name) || empty($room_type) || empty($capacity)) {
        $message = "All fields are required.";

    } else {

        // insert room into database using prepared statement (more secure)
        $stmt = $conn->prepare("INSERT INTO rooms (room_name, room_type, capacity, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $room_name, $room_type, $capacity, $status);

        if ($stmt->execute()) {
            $message = "Room added successfully!";
        } else {
            $message = "Error adding room.";
        }
    }
}
?>

<h2>Add Room</h2>

<?php if (!empty($message)) { ?>
    <p><?php echo htmlspecialchars($message); ?></p>
<?php } ?>

<form method="POST">

    Room Name:
    <input type="text" name="room_name" required><br><br>

    Room Type:
    <select name="room_type">
        <option value="meeting">Meeting</option>
        <option value="individual">Individual</option>
    </select><br><br>

    Capacity:
    <input type="number" name="capacity" required><br><br>

    Status:
    <select name="status">
        <option value="available">Available</option>
        <option value="unavailable">Unavailable</option>
    </select><br><br>

    <button type="submit">Add Room</button>

</form>