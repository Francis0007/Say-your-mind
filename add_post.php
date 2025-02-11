<?php
$conn = new mysqli("localhost", "root", "", "mind_say");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$user_name = $_POST['user_name'];
$content = $_POST['content'];

$stmt = $conn->prepare("INSERT INTO posts (user_name, content) VALUES (?, ?)");
$stmt->bind_param("ss", $user_name, $content);

if ($stmt->execute()) {
    header("Location: index.php");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
