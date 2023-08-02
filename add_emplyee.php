<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Define your database connection details here
    $db_host = "your_db_host"; // Replace with your database host
    $db_username = "your_db_username"; // Replace with your database username
    $db_password = "your_db_password"; // Replace with your database password
    $db_name = "your_db_name"; // Replace with your database name

    // Connect to the database
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Collect user data from the form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];

    // Check if the username is already taken
    $check_username_query = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($check_username_query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $registration_error = "Username already taken. Please choose a different username.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Handle image upload
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
            $image_name = $_FILES['profile_image']['name'];
            $image_tmp = $_FILES['profile_image']['tmp_name'];
            $image_path = "uploads/" . $image_name;

            if (move_uploaded_file($image_tmp, $image_path)) {
                // Image uploaded successfully, proceed with user registration
                $insert_user_query = "INSERT INTO users (username, password, email, full_name, profile_image) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_user_query);
                $stmt->bind_param("sssss", $username, $hashed_password, $email, $full_name, $image_path);
                if ($stmt->execute()) {
                    // Registration successful, redirect to login page or any other page you prefer
                    header("Location: login.php"); // Replace 'login.php' with your desired login page.
                    exit();
                } else {
                    $registration_error = "Error occurred during registration. Please try again.";
                }
            } else {
                $registration_error = "Error uploading the image. Please try again.";
            }
        } else {
            $registration_error = "Please choose a profile image.";
        }
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
</head>
<body>
    <h1>Registration</h1>
    <?php if (isset($registration_error)): ?>
        <p style="color: red;"><?php echo $registration_error; ?></p>
    <?php endif; ?>
    <form method="post" action="register.php" enctype="multipart/form-data">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name" required><br>

        <label for="profile_image">Profile Image:</label>
        <input type="file" id="profile_image" name="profile_image" accept="image/*" required><br>

        <input type="submit" value="Register">
    </form>
</body>
</html>
