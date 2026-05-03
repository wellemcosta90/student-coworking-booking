<?php
include '../includes/header.php';
include '../config/db.php';

$result = $conn->query("SELECT * FROM rooms");
?>

<h1>Available Rooms</h1>

<?php if (isset($_SESSION['role']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'organiser')) { ?>
    <p><a href="add_room.php">Add New Room</a></p>
<?php } ?>

<?php while ($room = $result->fetch_assoc()) { ?>

<div class="room-card">
    <h3><?php echo htmlspecialchars($room['room_name']); ?></h3>

    <p><strong>Type:</strong> <?php echo ucfirst($room['room_type']); ?></p>
    <p><strong>Capacity:</strong> <?php echo $room['capacity']; ?></p>
    <p><strong>Status:</strong> <?php echo $room['status']; ?></p>

    <?php if ($room['status'] == 'available') { ?>
        <a href="../bookings/book_room.php?type=<?php echo $room['room_type']; ?>">
            Book Now
        </a>
    <?php } ?>

    <?php if (isset($_SESSION['role']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'organiser')) { ?>
        <br><br>
        <a href="edit_room.php?id=<?php echo $room['room_id']; ?>">Edit Room</a>
        |
        <a href="delete_room.php?id=<?php echo $room['room_id']; ?>">Delete Room</a>
    <?php } ?>

</div>

<?php } ?>

<?php include '../includes/footer.php'; ?>
