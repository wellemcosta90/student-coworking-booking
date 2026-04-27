<?php
// start session
session_start();

// check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}
?>

<h2>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h2>

<p>Your role is: <?php echo htmlspecialchars($_SESSION['role']); ?></p>

<hr>

<h3>Menu</h3>

<a href="rooms/rooms.php">View Rooms</a><br>
<a href="rooms/add_room.php">Add Room</a><br>
<a href="bookings/my_bookings.php">My Bookings</a><br>
<a href="auth/logout.php">Logout</a>