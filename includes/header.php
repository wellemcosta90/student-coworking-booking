<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Coworking</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- ✅ CORRECT PATH -->
    <link rel="stylesheet" href="/student-coworking-booking/assets/css/style.css">
    <script src="/student-coworking-booking/assets/js/intro.js" defer></script>
</head>
<body>

<div class="container">

<?php if (isset($_SESSION['user_id'])) { ?>
<div class="nav">
    <a href="/student-coworking-booking/dashboard.php">Dashboard</a>
    <a href="/student-coworking-booking/rooms/rooms.php">Rooms</a>
    <a href="/student-coworking-booking/bookings/my_bookings.php">My Bookings</a>

    <?php if ($_SESSION['role'] == 'admin') { ?>
        <a href="/student-coworking-booking/admin/manage_users.php">Users</a>
        <a href="/student-coworking-booking/admin/manage_bookings.php">Bookings</a>
    <?php } ?>

    <a href="/student-coworking-booking/auth/logout.php">Logout</a>
</div>
<?php } ?>
