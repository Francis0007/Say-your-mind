<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to reply.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = intval($_POST['post_id']);
    $reply_text = htmlspecialchars($_POST['reply_text']);
    $user_id = $_SESSION['user_id'];

    // Insert reply into the database
    $stmt = $conn->prepare("INSERT INTO replies (post_id, user_id, reply_text) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $post_id, $user_id, $reply_text);

    if ($stmt->execute()) {
        // Fetch the post owner's user_id
        $stmt2 = $conn->prepare("SELECT user_id FROM posts WHERE id = ?");
        $stmt2->bind_param("i", $post_id);
        $stmt2->execute();
        $result = $stmt2->get_result();

        if ($result->num_rows > 0) {
            $post_owner = $result->fetch_assoc();
            $post_owner_id = $post_owner['user_id'];

            // Add a notification for the post owner
            if ($post_owner_id != $user_id) { // Avoid notifying the user replying to their own post
                $message = "Someone replied to your post.";
                $stmt3 = $conn->prepare("INSERT INTO notifications (user_id, post_id, message) VALUES (?, ?, ?)");
                $stmt3->bind_param("iis", $post_owner_id, $post_id, $message);
                $stmt3->execute();
                $stmt3->close();
            }
        }

        echo "Reply submitted!";
    } else {
        echo "Error submitting reply: " . $stmt->error;
    }

    $stmt->close();
}
?>
