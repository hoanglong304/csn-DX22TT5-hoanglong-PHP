<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// 1. Kiểm tra đơn hàng đang "pending" đã tồn tại chưa
$stmt = $conn->prepare("SELECT id FROM orders WHERE user_id = ? AND status = 'pending' LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($order = $result->fetch_assoc()) {
    $order_id = $order['id'];
} else {
    // 2. Nếu chưa có, tạo đơn hàng mới
    $createOrder = $conn->prepare("INSERT INTO orders (user_id, status, created_at) VALUES (?, 'pending', NOW())");
    $createOrder->bind_param("i", $user_id);
    $createOrder->execute();
    $order_id = $createOrder->insert_id;
}

// 3. Lấy giỏ hàng từ bảng `order_items`
$sql = "SELECT * FROM order_items WHERE order_id = ?";
$items = $conn->prepare($sql);
$items->bind_param("i", $order_id);
$items->execute();
$res = $items->get_result();

if ($res->num_rows === 0) {
    die('❌ Giỏ hàng trống!');
}

// 4. Cập nhật trạng thái đơn hàng → đã thanh toán
$update = $conn->prepare("UPDATE orders SET status = 'completed', created_at = NOW() WHERE id = ?");
$update->bind_param("i", $order_id);
$update->execute();

// 5. Redirect đến trang thành công
header("Location: order_success.php");
exit;
?>
