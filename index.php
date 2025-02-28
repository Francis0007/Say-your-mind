<?php
session_start();
require 'config.php';

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Say Your Mind</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css" />
</head>
<body>

<nav>
    <h3>Say Your Mind.</h3>
    <i class="fa fa-bars" id="menu-bar"></i>
    <div class="menu">
        <a href="index.php">Home</a>
        <a href="notifications.php">Notifications</a>
        <?php if (isset($user)): ?>
            <span>Welcome, <?php echo htmlspecialchars($user['username']); ?></span>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </div>
</nav>

<div class="center">
    <div class="container">
        <h2>FEEL LIKE VENTING? &#128561;</h2>
        <p>Say your mind and wait for others' opinions or advice</p>
        
        <form class="post-form" action="add_post.php" method="POST">
            <?php if (isset($user)): ?>
                <input type="hidden" name="user_name" value="<?php echo htmlspecialchars($user['username']); ?>">
            <?php else: ?>
                <input type="text" name="user_name" placeholder="Your name" required><br><br>
            <?php endif; ?>
            
            <textarea name="content" placeholder="What's on your mind?" required></textarea><br><br>
            <button type="submit">Post</button>
        </form>

        <h1>Comment on what others have said</h1>
       
        <?php
        $conn = new mysqli($host, $username, $password, $dbname);
        if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

        $result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");

        while ($row = $result->fetch_assoc()) {
            $formatted_time = date("M d, Y - h:i A", strtotime($row['created_at']));

            echo "<div class='post'>
                    <p><strong>{$row['user_name']}</strong>: {$row['content']}</p>
                    <small>Posted on: {$formatted_time}</small>
                    <form class='reply-form' action='add_reply.php' method='POST'>
                        <input type='hidden' name='post_id' value='{$row['id']}'>";

            if (isset($user)) {
                echo "<input type='hidden' name='user_name' value='{$user['username']}'>";
            } else {
                echo "<input type='text' name='user_name' placeholder='Your name' required><br><br>";
            }

            echo "<textarea name='reply' placeholder='Your reply' required></textarea><br><br>
                  <button type='submit'>Reply</button>
                  </form>";

            $replies = $conn->query("SELECT * FROM replies WHERE post_id={$row['id']} ORDER BY created_at ASC");
            if ($replies->num_rows > 0) {
                echo "<div class='replies'>";
                while ($reply = $replies->fetch_assoc()) {
                    $reply_time = date("M d, Y - h:i A", strtotime($reply['created_at']));
                    echo "<p><strong>{$reply['user_name']}</strong>: {$reply['reply']}</p>
                          <small>Replied on: {$reply_time}</small>";
                }
                echo "</div>";
            }
            
            echo "</div>";
        }
        
        $conn->close();
        ?> 
    </div>
</div>

</body>
</html>
