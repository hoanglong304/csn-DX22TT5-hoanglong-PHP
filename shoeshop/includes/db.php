<?php
$host = "localhost";
$user = "root";
$pass = "long304";
$dbname = "shoeshop";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
require_once 'config.php'; // Include cấu hình đường dẫn
