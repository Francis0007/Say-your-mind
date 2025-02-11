<?php
session_start();
require 'config.php'; // Database connection


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, is_admin FROM users WHERE email = ? AND password_hash = (?)");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        if ($user['is_admin']) {
            $_SESSION['admin_id'] = $user['id'];
            header("Location: admin.php"); // Redirect to the admin panel
        } else {
            header("Location: index.php"); // Redirect to the user dashboard
        }
    } else {
        echo "Invalid email or password.";
    }
}




if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password_hash FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
        } else {
            echo "Invalid credentials!";
        }
    } else {
        echo "User not found!";
    }
    $stmt->close();
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
    <title>Login</title>
</head>
<body>
    <center>
<h1>Login</h1>
<div class="login">
    <form method="POST">
        <label>Email:</label><br />
        <input type="email" name="email" required><br>
        <label>Password:</label><br />
        <input type="password" name="password" required><br>
        <button type="submit">Login</button>
    </form><h1>Don't have an account <a href="register.php">Register</a></h1>
   <a href="index.php"> <h1>Use anonymously</h1></a>
</div>
</center>
</body>


</html>
