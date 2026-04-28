<?php
// protect page
include '../includes/auth.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Choose Room Type</title>
</head>
<body>

    <h1>Choose a Room Type</h1>

    <p>Select the type of room you want to book.</p>

    <hr>

    <h2>Individual Room</h2>
    <p>Capacity: 1 person</p>
    <a href="select_room.php?type=individual">Choose Individual Room</a>

    <br><br>

    <h2>Meeting Room</h2>
    <p>Capacity: 2 to 15 people</p>
    <a href="select_room.php?type=meeting">Choose Meeting Room</a>

    <br><br><br>

    <a href="../dashboard.php">Back to Dashboard</a>

</body>
</html>