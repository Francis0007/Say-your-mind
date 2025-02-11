<?php
 $host = "sql203.infinityfree.com"; 
 $username = "if0_37987503"; 
 $password = "LL3uZgKp3a";     
 $dbname = "if0_37987503_mind_say";
$ip_address = $_SERVER['REMOTE_ADDR'];
 

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);
$stmt = $conn->prepare("SELECT id FROM blocked_ips WHERE ip_address = ?");
$stmt->bind_param("s", $ip_address);
$stmt->execute();
$result = $stmt->get_result();

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($result->num_rows > 0) {
    die("Access Denied. Your IP address is blocked.");
}

?>
