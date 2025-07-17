<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("⛔ Không có quyền truy cập");
}

$order_id = intval($_GET['id']);
$orderSql = $conn->prepare("
  SELECT o.*, u.username
  FROM orders o
  JOIN users u ON o.user_id = u.id
  WHERE o.id = ?
");
$orderSql->bind_param("i", $order_id);
$orderSql->execute();
$order = $orderSql->get_result()->fetch_assoc();

if (!$order) {
    die("🚫 Đơn hàng không tồn tại.");
}

$itemSql = $conn->prepare("
  SELECT oi.quantity, oi.price, p.name
  FROM order_items oi
  JOIN products p ON oi.product_id = p.id
  WHERE oi.order_id = ?
");
$itemSql->bind_param("i", $order_id);
$itemSql->execute();
$items = $itemSql->get_result();

include 'includes/header.php';
?>

<div class="container mt-5">
  <h3>📋 Chi tiết đơn hàng #<?= $order['id'] ?></h3>
  <p><strong>Người đặt:</strong> <?= htmlspecialchars($order['username']) ?></p>
  <p><strong>Trạng thái:</strong> <?= htmlspecialchars($order['status']) ?></p>
  <p><strong>Ngày tạo:</strong> <?= $order['created_at'] ?></p>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Sản phẩm</th>
        <th>Số lượng</th>
        <th>Đơn giá</th>
        <th>Tổng</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $total = 0;
      while ($item = $items->fetch_assoc()):
        $subtotal = $item['quantity'] * $item['price'];
        $total += $subtotal;
      ?>
        <tr>
          <td><?= htmlspecialchars($item['name']) ?></td>
          <td><?= $item['quantity'] ?></td>
          <td><?= number_format($item['price'], 0) ?> đ</td>
          <td><?= number_format($subtotal, 0) ?> đ</td>
        </tr>
      <?php endwhile; ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
        <td><strong class="text-danger"><?= number_format($total, 0) ?> đ</strong></td>
      </tr>
    </tfoot>
  </table>
</div>

<?php include 'includes/footer.php'; ?>
