<?php
include '../config/db.php';

$message = "";

// REGISTER LOGIC
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = "attendee";

    if (empty($name) || empty($email) || empty($password)) {
        $message = "All fields are required.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";

    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters.";

    } else {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

        if ($stmt->execute()) {
            $message = "Registration successful!";
        } else {
            if ($conn->errno == 1062) {
                $message = "Email already registered.";
            } else {
                $message = "Error creating account.";
            }
        }
    }
}

// HEADER
include '../includes/header.php';
?>

<div class="auth-container">

    <h2>Create Account</h2>

    <?php if (!empty($message)) { ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php } ?>

    <form method="POST">

        <div class="input-group">
            <i class="fa fa-user"></i>
            <input type="text" name="name" placeholder="Full Name" required>
        </div>

        <div class="input-group">
            <i class="fa fa-envelope"></i>
            <input type="email" name="email" placeholder="Email" required>
        </div>

        <div class="input-group">
            <i class="fa fa-lock"></i>
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <button type="submit">Register</button>

    </form>

    <a href="login.php">Already have an account?</a>

</div>