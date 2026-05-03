<?php
include '../includes/header.php';
include '../config/db.php';

$result = $conn->query("SELECT * FROM rooms");
?>

<h1>Available Rooms</h1>

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

</div>

<?php } ?>

<?php include '../includes/footer.php'; ?>