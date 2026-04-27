<?php
// start session
session_start();

// check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // redirect if not logged in
    header("Location: ../auth/login.php");
    exit();
}
?>