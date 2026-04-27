<?php
session_start();
include '../config/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $message = "Email and password are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } else {
        $stmt = $conn->prepare("SELECT user_id, name, email, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];

                header("Location: ../dashboard.php");
                exit();
            } else {
                $message = "Incorrect password.";
            }
        } else {
            $message = "No account found with this email.";
        }
    }
}
?>

<h2>Login</h2>

<?php if (!empty($message)) { ?>
    <p><?php echo htmlspecialchars($message); ?></p>
<?php } ?>

<form method="POST">
    Email:
    <input type="email" name="email" required><br><br>

    Password:
    <input type="password" name="password" required><br><br>

    <button type="submit">Login</button>
</form>