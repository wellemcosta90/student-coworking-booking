<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}
?>

<h2>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h2>

<p>Your role is: <?php echo htmlspecialchars($_SESSION['role']); ?></p>

<a href="auth/logout.php">Logout</a>