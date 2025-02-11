
<?php
session_start();
require 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch all users
$stmt = $conn->prepare("SELECT id, username, email, ip_address FROM users");
$stmt->execute();
$users = $stmt->get_result();

// Fetch all blocked IPs
$stmt2 = $conn->prepare("SELECT ip_address FROM blocked_ips");
$stmt2->execute();
$blocked_ips = $stmt2->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Admin Panel</h1>

    <h2>All Users</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>IP Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $users->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['ip_address']); ?></td>
                    <td>
                        <form method="POST" action="block_user.php">
                            <input type="hidden" name="ip_address" value="<?php echo $user['ip_address']; ?>">
                            <button type="submit">Block</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h2>Blocked IPs</h2>
    <table>
        <thead>
            <tr>
                <th>IP Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($blocked_ip = $blocked_ips->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($blocked_ip['ip_address']); ?></td>
                    <td>
                        <form method="POST" action="unblock_ip.php">
                            <input type="hidden" name="ip_address" value="<?php echo $blocked_ip['ip_address']; ?>">
                            <button type="submit">Unblock</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
