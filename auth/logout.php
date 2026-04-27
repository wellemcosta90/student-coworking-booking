<?php
// start session
session_start();

// remove session data
session_unset();

// destroy session
session_destroy();

// redirect to login
header("Location: login.php");
exit();
?>