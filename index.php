<?php include 'includes/header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Coworking Booking System</title>
    <title>Choose Room Type</title>
    <link rel="stylesheet" href="assets/css/style.css">

</head>
<body>
<div class="container">

<div class="nav">
    <a href="/student-coworking-booking/rooms/rooms.php">Rooms</a>

    <?php if (isset($_SESSION['user_id'])) { ?>
        <a href="/student-coworking-booking/dashboard.php">Dashboard</a>
        <a href="/student-coworking-booking/bookings/my_bookings.php">My Bookings</a>
        <a href="/student-coworking-booking/auth/logout.php">Logout</a>
    <?php } else { ?>
        <a href="/student-coworking-booking/auth/login.php">Login</a>
        <a href="/student-coworking-booking/auth/register.php">Register</a>
    <?php } ?>
</div>

    <h1>Student Coworking Booking System</h1>

    <p>
        Welcome to the student coworking booking system.
        You can book individual rooms for study or meeting rooms for group work.
    </p>

    <br>

    <a href="auth/login.php">Login</a>
    <br><br>
    <a href="auth/register.php">Register</a>

</div>
</body>
</html>
