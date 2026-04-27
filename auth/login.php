<?php
// start session to keep user logged in
session_start();

// connect to database
include '../config/db.php';

// variable to show messages to the user
$message = "";

// check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // get user input and remove extra spaces
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // check if fields are empty
    if (empty($email) || empty($password)) {
        $message = "Email and password are required.";

    // check if email format is valid
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";

    } else {
        // prepare SQL to find user by email (more secure)
        $stmt = $conn->prepare("SELECT user_id, name, email, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        // get result from database
        $result = $stmt->get_result();

        // check if user exists
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // verify password with hashed password in database
            if (password_verify($password, $user['password'])) {

                // save user info in session
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];

                // redirect to dashboard after login
                header("Location: ../dashboard.php");
                exit();

            } else {
                // wrong password
                $message = "Incorrect password.";
            }

        } else {
            // email not found
            $message = "No account found with this email.";
        }
    }
}
?>

<h2>Login</h2>

<!-- show error message if exists -->
<?php if (!empty($message)) { ?>
    <p><?php echo htmlspecialchars($message); ?></p>
<?php } ?>

<!-- login form -->
<form method="POST">
    Email:
    <input type="email" name="email" required><br><br>

    Password:
    <input type="password" name="password" required><br><br>

    <button type="submit">Login</button>
</form>