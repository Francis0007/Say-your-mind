<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mind Say</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f9f9f9; }
        .container { max-width: 800px; margin: auto; padding: 20px; }
        .post-form, .reply-form { margin-bottom: 20px; }
        .post { border: 1px solid #ddd; padding: 10px; margin-bottom: 15px; background: #fff; }
        .replies { margin-left: 20px; background: #f4f4f4; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Mind Say</h1>
        <form class="post-form" action="add_post.php" method="POST">
            <input type="text" name="user_name" placeholder="Your name" required><br><br>
            <textarea name="content" placeholder="What's on your mind?" required></textarea><br><br>
            <button type="submit">Post</button>
        </form>
        
        <?php
        $conn = new mysqli("localhost", "root", "", "mind_say");
        if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
        $result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
        while ($row = $result->fetch_assoc()) {
            echo "<div class='post'>
                    <p><strong>{$row['user_name']}</strong>: {$row['content']}</p>
                    <form class='reply-form' action='add_reply.php' method='POST'>
                        <input type='hidden' name='post_id' value='{$row['id']}'>
                        <input type='text' name='user_name' placeholder='Your name' required>
                        <textarea name='reply' placeholder='Your reply' required></textarea>
                        <button type='submit'>Reply</button>
                    </form>";

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
</body>
</html>
