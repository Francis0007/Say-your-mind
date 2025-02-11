<?php
require 'config.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // Validation
    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
    } elseif (strlen($password) < 8) {
        echo "Password should be at least 8 characters long.";
    } else {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        
        // Prepare the SQL statement to insert the user
        $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, ip_address) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $password_hash, $ip_address);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Registration successful!";
            header("Location: login.php");
            exit(); // Prevent further script execution after redirect
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<link
      href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
      rel="stylesheet"
    />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Say your mind</title>
  <link rel="stylesheet" href="style.css" />
    <title>Register</title>
</head>
<body>
    <center>
    <h1>Register</h1>

    <div class="login">
    <form method="POST">
        <label>Username:</label><br />
        <input type="text" name="username" required><br>
        <label>Email:</label><br />
        <input type="email" name="email" required><br>
        <label>Password:</label><br />
        <input type="password" name="password" required><br>
        <label>Confirm Password:</label><br />
        <input type="password" name="confirm_password" required><br>
        <button type="submit">Register</button>
    </form>
    <h1>Already have an account <a href="login.php">Login</a></h1>
    <a href="index.php"> <h1>Use anonymously</h1></a>
</div>

</center>
</body>
</html>
