<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ip_address = $_POST['ip_address'];

    $stmt = $conn->prepare("INSERT IGNORE INTO blocked_ips (ip_address) VALUES (?)");
    $stmt->bind_param("s", $ip_address);
    if ($stmt->execute()) {
        echo "IP Address blocked successfully.";
    } else {
        echo "Failed to block IP Address.";
    }

    header("Location: admin.php");
    exit;
}
?>
