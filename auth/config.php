<?php
$servername = '85.209.92.162';
$username = 'remoteuser';
$password = 'admin@db1';
$dbname = 'epd1';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
