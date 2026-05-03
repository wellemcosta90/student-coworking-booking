<?php
// protect page
include '../includes/auth.php';
include '../includes/header.php';

// connect to database
include '../config/db.php';

// only admin or organiser can edit rooms
if ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'organiser') {
    die("Access denied. Only admins or organisers can edit rooms.");
}

// variable to show messages
$message = "";

// check if room id exists
if (!isset($_GET['id'])) {
    die("Room ID not found.");
}

// get room id from URL
$room_id = $_GET['id'];

// get current room data from database
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

    // get form data
    $room_name = trim($_POST['room_name']);
    $room_type = $_POST['room_type'];
    $status = $_POST['status'];

    // if meeting, get capacity from user
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
        $message = "Meeting room capacity must be between 2 and 15 people.";

    } else {

        // update room in database
        $stmt = $conn->prepare("UPDATE rooms SET room_name = ?, room_type = ?, capacity = ?, status = ? WHERE room_id = ?");
        $stmt->bind_param("ssisi", $room_name, $room_type, $capacity, $status, $room_id);

        if ($stmt->execute()) {
            $message = "Room updated successfully.";

            // reload updated data
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
    <input 
        type="text" 
        name="room_name" 
        value="<?php echo htmlspecialchars($room['room_name']); ?>" 
        required
    ><br><br>

    Room Type:
    <select name="room_type" id="room_type" onchange="toggleCapacity()" required>
        <option value="meeting" <?php if ($room['room_type'] == 'meeting') echo 'selected'; ?>>
            Meeting
        </option>
        <option value="individual" <?php if ($room['room_type'] == 'individual') echo 'selected'; ?>>
            Individual
        </option>
    </select><br><br>

    <div id="capacity_field">
        Capacity:
        <select name="capacity" id="capacity">
            <?php for ($i = 2; $i <= 15; $i++) { ?>
                <option value="<?php echo $i; ?>" <?php if ($room['capacity'] == $i) echo 'selected'; ?>>
                    <?php echo $i; ?>
                </option>
            <?php } ?>
        </select><br><br>
    </div>

    Status:
    <select name="status" required>
        <option value="available" <?php if ($room['status'] == 'available') echo 'selected'; ?>>
            Available
        </option>
        <option value="unavailable" <?php if ($room['status'] == 'unavailable') echo 'selected'; ?>>
            Unavailable
        </option>
    </select><br><br>

    <button type="submit">Update Room</button>

</form>

<br>
<a href="rooms.php">Back to Rooms</a>

<script>
// show or hide capacity field
function toggleCapacity() {
    const roomType = document.getElementById("room_type").value;
    const capacityField = document.getElementById("capacity_field");

    if (roomType === "individual") {
        capacityField.style.display = "none";
    } else {
        capacityField.style.display = "block";
    }
}

// run when page loads
toggleCapacity();
</script>
