<?php
session_start();
include '../../includes/db.php';

// Chỉ cho admin truy cập
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit;
}

$order_id = intval($_GET['id'] ?? 0);

// Lấy thông tin đơn hàng
$orderStmt = $conn->prepare("
    SELECT o.*, u.username 
    FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE o.id = ?
");
$orderStmt->bind_param("i", $order_id);
$orderStmt->execute();
$order = $orderStmt->get_result()->fetch_assoc();

if (!$order) {
    die("❌ Không tìm thấy đơn hàng!");
}

// Lấy danh sách sản phẩm trong đơn hàng
$itemStmt = $conn->prepare("
    SELECT p.name, oi.quantity, oi.price
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$itemStmt->bind_param("i", $order_id);
$itemStmt->execute();
$items = $itemStmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Chi tiết đơn hàng</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <h3 class="mb-4">🧾 Chi tiết đơn hàng #<?= $order['id'] ?></h3>

  <ul class="list-group mb-4">
    <li class="list-group-item"><strong>Người đặt:</strong> <?= htmlspecialchars($order['username']) ?></li>
    <li class="list-group-item"><strong>Trạng thái:</strong> <?= ucfirst($order['status']) ?></li>
    <li class="list-group-item"><strong>Ngày đặt:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></li>
  </ul>

  <table class="table table-bordered bg-white">
    <thead class="table-dark">
      <tr>
        <th>Sản phẩm</th>
        <th>Đơn giá</th>
        <th>Số lượng</th>
        <th>Thành tiền</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $total = 0;
      while ($item = $items->fetch_assoc()):
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
      ?>
      <tr>
        <td><?= htmlspecialchars($item['name']) ?></td>
        <td><?= number_format($item['price'], 0) ?> đ</td>
        <td><?= $item['quantity'] ?></td>
        <td><?= number_format($subtotal, 0) ?> đ</td>
      </tr>
      <?php endwhile; ?>
      <tr class="fw-bold">
        <td colspan="3" class="text-end">Tổng cộng:</td>
        <td><?= number_format($total, 0) ?> đ</td>
      </tr>
    </tbody>
  </table>

  <a href="list.php" class="btn btn-secondary">← Quay lại danh sách</a>
</div>

</body>
</html>
