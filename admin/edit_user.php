<?php
// protect page
include '../includes/auth.php';

// only admin can access this page
if ($_SESSION['role'] != 'admin') {
    die("Access denied. Only admins can edit users.");
}

// connect database
include '../config/db.php';

$message = "";

// check if user id exists
if (!isset($_GET['id'])) {
    die("User ID not found.");
}

$user_id = $_GET['id'];

// get user data
$stmt = $conn->prepare("SELECT user_id, name, email, role FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

// update role
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $role = $_POST['role'];

    if ($role != "admin" && $role != "organiser" && $role != "attendee") {
        $message = "Invalid role.";
    } else {
        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE user_id = ?");
        $stmt->bind_param("si", $role, $user_id);

        if ($stmt->execute()) {
            $message = "User role updated successfully.";

            // refresh user data
            $stmt = $conn->prepare("SELECT user_id, name, email, role FROM users WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

        } else {
            $message = "Error updating role.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User Role</title>
</head>
<body>

<h1>Edit User Role</h1>

<p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
<p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>

<?php if (!empty($message)) { ?>
    <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
<?php } ?>

<form method="POST">

    Role:
    <select name="role" required>
        <option value="attendee" <?php if ($user['role'] == 'attendee') echo 'selected'; ?>>Attendee</option>
        <option value="organiser" <?php if ($user['role'] == 'organiser') echo 'selected'; ?>>Organiser</option>
        <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
    </select><br><br>

    <button type="submit">Update Role</button>

</form>

<br>
<a href="manage_users.php">Back to Manage Users</a>

</body>
</html>