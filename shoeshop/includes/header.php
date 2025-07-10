<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include 'db.php';

$cartCount = 0;

if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
  $stmt = $conn->prepare("SELECT id FROM orders WHERE user_id = ? AND status = 'pending' ORDER BY id DESC LIMIT 1");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $order = $stmt->get_result()->fetch_assoc();

  if ($order) {
    $order_id = $order['id'];
    $countStmt = $conn->prepare("SELECT SUM(quantity) AS total FROM order_items WHERE order_id = ?");
    $countStmt->bind_param("i", $order_id);
    $countStmt->execute();
    $row = $countStmt->get_result()->fetch_assoc();
    $cartCount = $row['total'] ?? 0;
  }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <title>Shoe Store</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="<?= BASE_URL ?>index.php">Shoe Store</a>
      <div>
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php">Trang chủ</a></li>
          <li class="nav-item">
            <a class="nav-link" href="<?= BASE_URL ?>cart.php">
              🛒 Giỏ hàng <span id="cart-count" class="badge bg-danger"><?= $cartCount ?></span>
            </a>
          </li>
          <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/orders/list.php">📦 Quản lý đơn</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/product/list.php">🛠 Quản lý sản phẩm</a></li>
          <?php endif; ?>
          <?php if (isset($_SESSION['user'])): ?>
            <li class="nav-item"><a class="nav-link" href="#">👤 <?= htmlspecialchars($_SESSION['user']) ?></a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>logout.php">Đăng xuất</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>login.php">Đăng nhập</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container" style="margin-top: 60px;">