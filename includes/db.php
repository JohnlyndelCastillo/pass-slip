<?php
$host = 'pass-slip-mysql'; // Docker MySQL host
$port = 3306;         // or 3307 if you mapped differently
$dbname = 'pass_slip_system';
$username = 'root';
$password = 'group2-123';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Database connected successfully!";
?>
