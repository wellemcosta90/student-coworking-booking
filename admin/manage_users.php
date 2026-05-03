<?php
// protect page
include '../includes/auth.php';
include '../includes/header.php';

// only admin can access this page
if ($_SESSION['role'] != 'admin') {
    die("Access denied. Only admins can manage users.");
}

// connect database
include '../config/db.php';

// get all users
$result = $conn->query("SELECT user_id, name, email, role, created_at FROM users ORDER BY user_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="container">

<h1>Manage Users</h1>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Created At</th>
        <th>Action</th>
    </tr>

    <?php while ($user = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($user['user_id']); ?></td>
            <td><?php echo htmlspecialchars($user['name']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><?php echo htmlspecialchars(ucfirst($user['role'])); ?></td>
            <td><?php echo htmlspecialchars($user['created_at']); ?></td>
            <td>
                <a href="edit_user.php?id=<?php echo $user['user_id']; ?>">Edit Role</a>
            </td>
        </tr>
    <?php } ?>

</table>

<br>
<a href="../dashboard.php">Back to Dashboard</a>

</div>
</body>
</html>