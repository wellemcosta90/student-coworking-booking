<?php
// start session
session_start();

// check login
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

// include header (THIS LOADS CSS)
include 'includes/header.php';
?>

<h1>Dashboard</h1>

<p>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>.</p>
<p>You are logged in as: <?php echo ucfirst($_SESSION['role']); ?></p>

<hr>

<div class="card-grid">

    <div class="card">
        <h3>Browse Rooms</h3>
        <p>Find available rooms</p>
        <a href="rooms/rooms.php">View Rooms</a>
    </div>

    <div class="card">
        <h3>My Bookings</h3>
        <p>View your bookings</p>
        <a href="bookings/my_bookings.php">View Bookings</a>
    </div>

    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'organiser') { ?>
    <div class="card">
        <h3>Add Room</h3>
        <a href="rooms/add_rooms.php">Add Room</a>
    </div>
    <?php } ?>

    <?php if ($_SESSION['role'] == 'admin') { ?>
    <div class="card">
        <h3>Manage Users</h3>
        <a href="admin/manage_users.php">Users</a>
    </div>

    <div class="card">
        <h3>Manage Bookings</h3>
        <a href="admin/manage_bookings.php">Bookings</a>
    </div>
    <?php } ?>

</div>

<?php include 'includes/footer.php'; ?>