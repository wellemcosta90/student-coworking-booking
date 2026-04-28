<?php
// start session
session_start();

// check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>

    <h1>Dashboard</h1>

    <p>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>.</p>

    <p>
        You are currently logged in as:
        <?php echo htmlspecialchars(ucfirst($_SESSION['role'])); ?>
    </p>

    <hr>

    <h2>What would you like to do?</h2>

    <ul>
        <li><a href="rooms/rooms.php">Choose a Room</a></li>
        <li><a href="bookings/my_bookings.php">My Bookings</a></li>

        <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'organiser') { ?>
            <li><a href="rooms/add_room.php">Add New Room</a></li>
        <?php } ?>

        <li><a href="auth/logout.php">Logout</a></li>
    </ul>

</body>
</html>