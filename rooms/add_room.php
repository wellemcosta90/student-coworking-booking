<?php
// protect page
include '../includes/auth.php';

// connect to database
include '../config/db.php';

// message variable
$message = "";

// check if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // get form data
    $room_name = trim($_POST['room_name']);
    $room_type = $_POST['room_type'];
    $status = $_POST['status'];

    // if meeting, get capacity from form
    if ($room_type == "meeting") {
        $capacity = intval($_POST['capacity']);
    } else {
        // if individual, capacity is always 1
        $capacity = 1;
    }

    // validation
    if (empty($room_name) || empty($room_type) || empty($status)) {
        $message = "All fields are required.";

    } elseif ($room_type == "meeting" && ($capacity < 2 || $capacity > 15)) {
        $message = "Meeting room capacity must be between 2 and 15.";

    } else {

        // insert room into database
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
    <select name="room_type" id="room_type" onchange="toggleCapacity()" required>
        <option value="meeting">Meeting</option>
        <option value="individual">Individual</option>
    </select><br><br>

    <div id="capacity_field">
        Capacity:
        <select name="capacity">
            <?php for ($i = 2; $i <= 15; $i++) { ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php } ?>
        </select><br><br>
    </div>

    Status:
    <select name="status" required>
        <option value="available">Available</option>
        <option value="unavailable">Unavailable</option>
    </select><br><br>

    <button type="submit">Add Room</button>

</form>

<br>
<a href="rooms.php">Back to Rooms</a>

<script>
// show or hide capacity field
function toggleCapacity() {
    const type = document.getElementById("room_type").value;
    const field = document.getElementById("capacity_field");

    if (type === "individual") {
        field.style.display = "none";
    } else {
        field.style.display = "block";
    }
}

// run when page loads
toggleCapacity();
</script>