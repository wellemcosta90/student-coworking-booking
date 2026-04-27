<?php
// protect page
include '../includes/auth.php';

// connect database
include '../config/db.php';

// get rooms from database
$result = $conn->query("SELECT * FROM rooms");
?>

<h2>Rooms</h2>

<a href="add_room.php">Add Room</a>
<br><br>

<table border="1" cellpadding="10">
    <tr>
        <th>Name</th>
        <th>Type</th>
        <th>Capacity</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>

    <?php while ($room = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($room['room_name']); ?></td>
            <td><?php echo htmlspecialchars($room['room_type']); ?></td>
            <td><?php echo htmlspecialchars($room['capacity']); ?></td>
            <td><?php echo htmlspecialchars($room['status']); ?></td>
            <td>
                <a href="edit_room.php?id=<?php echo $room['room_id']; ?>">Edit</a> |
                <a href="delete_room.php?id=<?php echo $room['room_id']; ?>">Delete</a> |
                <a href="../bookings/book_room.php?id=<?php echo $room['room_id']; ?>">Book Now</a>
            </td>
        </tr>
    <?php } ?>
</table>

<br>