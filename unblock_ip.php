<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ip_address = $_POST['ip_address'];

    $stmt = $conn->prepare("DELETE FROM blocked_ips WHERE ip_address = ?");
    $stmt->bind_param("s", $ip_address);
    if ($stmt->execute()) {
        echo "IP Address unblocked successfully.";
    } else {
        echo "Failed to unblock IP Address.";
    }

    header("Location: admin.php");
    exit;
}
?>
