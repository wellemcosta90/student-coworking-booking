<?php
// start session to access user data
session_start();

// check if user is logged in
// if not, send back to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}
?>

<h2>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h2>

<!-- show user role -->
<p>Your role is: <?php echo htmlspecialchars($_SESSION['role']); ?></p>

<!-- logout button -->
<a href="auth/logout.php">Logout</a>