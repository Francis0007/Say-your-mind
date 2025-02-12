<?php
session_start();
require 'config.php';

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    // Fetch the user's details
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
      rel="stylesheet"
    />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Say your mind</title>

  <link rel="stylesheet" href="style.css" />
</head>
<body>

<nav>
    <a href="index.php">Home</a>
    <a href="notifications.php">Notifications</a>
    <?php if (isset($user)): ?>
        <span>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</span>
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    <?php endif; ?>
</nav>
<center>
<div class="container">
    <h1>Just say your mind and wait for others opinions or advice</h1>
    
    <form class="post-form" action="add_post.php" method="POST">
        <?php if (isset($user)): ?>
            <!-- Hide the name field for logged-in users -->
            <input type="hidden" name="user_name" value="<?php echo htmlspecialchars($user['username']); ?>">
        <?php else: ?>
            <!-- Show the name field for non-logged-in users -->
            <input type="text" name="user_name" placeholder="Your name" required><br><br>
        <?php endif; ?>
        
        <textarea name="content" placeholder="What's on your mind?" required></textarea><br><br>
        <button type="submit">Post</button>
    </form>
<h1>Comment on what others have said</h1>
    <?php
    // Fetch and display posts
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
    $result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");

    while ($row = $result->fetch_assoc()) {

        echo 
     "<div class='post'>

                <p><strong>{$row['user_name']}</strong>: {$row['content']}</p>
                <form class='reply-form' action='add_reply.php' method='POST'>
                    <input type='hidden' name='post_id' value='{$row['id']}'>";
        
        // If the user is logged in, hide the name field for the reply form
        if (isset($user)) {
            echo "<input type='hidden' name='user_name' value='{$user['username']}'>";
        } else {
            // If the user is not logged in, show the name field for the reply form
            echo "<input type='text' name='user_name' placeholder='Your name' required><br><br>";
        }

        echo "
                    <textarea name='reply' placeholder='Your reply' required></textarea><br><br>
                    <button type='submit'>Reply</button>
                </form>";

        // Fetch and display replies
        $replies = $conn->query("SELECT * FROM replies WHERE post_id={$row['id']}");
        if ($replies->num_rows > 0) {
            echo "<div class='replies'>";
            while ($reply = $replies->fetch_assoc()) {
                echo "<p><strong>{$reply['user_name']}</strong>: {$reply['reply']}</p>";
            }
            echo "</div>";
        }
        
        echo "</div>";
    }
    
    $conn->close();
    ?>
</div>
</center>
</body>
</html>
