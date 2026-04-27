<?php
// start session
session_start();

// remove all session data
session_unset();

// destroy session completely
session_destroy();

// redirect back to login page
header("Location: login.php");
exit();
?>