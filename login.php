<?php
session_start();

// Check if the user is already logged in. If yes, redirect to the dashboard or any other authenticated page.
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php"); // Replace 'dashboard.php' with your desired authenticated page.
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $db_host = "your_db_host"; // Replace with your database host
    $db_username = "your_db_username"; // Replace with your database username
    $db_password = "your_db_password"; // Replace with your database password
    $db_name = "your_db_name"; // Replace with your database name

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $db_username, $db_password_hash);
        $stmt->fetch();

        if (password_verify($password, $db_password_hash)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $db_username;
            header("Location: dashboard.php"); // Replace 'dashboard.php' with your desired authenticated page.
            exit();
        } else {
            $login_error = "Invalid username or password.";
        }
    } else {
        $login_error = "Invalid username or password.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <?php if (isset($login_error)): ?>
        <p style="color: red;"><?php echo $login_error; ?></p>
    <?php endif; ?>
    <form method="post" action="login.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <input type="submit" value="Login">
    </form>
</body>
</html>
