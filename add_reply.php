<?php
   require 'config.php';
   
   $conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$post_id = $_POST['post_id'];
$user_name = $_POST['user_name'];
$reply = $_POST['reply'];

$stmt = $conn->prepare("INSERT INTO replies (post_id, user_name, reply) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $post_id, $user_name, $reply);

if ($stmt->execute()) {
    header("Location: index.php");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
