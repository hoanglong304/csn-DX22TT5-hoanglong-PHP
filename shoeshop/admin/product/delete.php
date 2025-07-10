<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit;
}
include '../../includes/db.php';

$id = intval($_GET['id']);
$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: list.php");
exit;
?>
