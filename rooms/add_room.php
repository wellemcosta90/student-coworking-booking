<?php
// protect page
include '../includes/auth.php';

// only admin or organiser can add rooms
if ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'organiser') {
    die("Access denied. Only admins or organisers can add rooms.");
}

// connect to database
include '../config/db.php';

// message variable
$message = "";

// check if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // get form data
    $room_name = trim($_POST['room_name']);
    $room_type = $_POST['room_type'];

    // status is set automatically when a room is created
    $status = "available";

    // if meeting, get capacity from form
    if ($room_type == "meeting") {
        $capacity = intval($_POST['capacity']);
    } else {
        // if individual, capacity is always 1
        $capacity = 1;
    }

    // validation
    if (empty($room_name) || empty($room_type)) {
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Room</title>
</head>
<body>

    <h1>Add New Room</h1>

    <p>This page is for admins or organisers to create rooms available for booking.</p>

    <?php if (!empty($message)) { ?>
        <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
    <?php } ?>

    <form method="POST">

        Room Identifier:
        <input 
            type="text" 
            name="room_name" 
            placeholder="e.g. Meeting Room A or Individual Room 1" 
            required
        ><br><br>

        Room Type:
        <select name="room_type" id="room_type" onchange="toggleCapacity()" required>
            <option value="meeting">Meeting Room</option>
            <option value="individual">Individual Room</option>
        </select><br><br>

        <div id="capacity_field">
            Meeting Room Capacity:
            <select name="capacity">
                <?php for ($i = 2; $i <= 15; $i++) { ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php } ?>
            </select>
            <p>Meeting rooms can have between 2 and 15 people.</p>
        </div>

        <button type="submit">Add Room</button>

    </form>

    <br>
    <a href="rooms.php">Back to Room Types</a>
    <br><br>
    <a href="../dashboard.php">Back to Dashboard</a>

    <script>
    // show capacity only for meeting rooms
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

</body>
</html>