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

    <hr>

    <h2>What would you like to do?</h2>

    <ul>
        <li>
            <a href="rooms/rooms.php">Choose a Room</a>
        </li>

        <li>
            <a href="bookings/my_bookings.php">My Bookings</a>
        </li>

        <li>
            <a href="auth/logout.php">Logout</a>
        </li>
    </ul>

</body>
</html>