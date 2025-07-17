<?php
session_start();
include 'includes/header.php';
?>

<div class="container text-center mt-5 pt-5">
  <h1 class="text-success display-4">🎉 Đặt hàng thành công!</h1>
  <p class="lead mt-3">Cảm ơn bạn đã mua sắm tại <strong>Shoe Store</strong>.</p>
  <p>Chúng tôi sẽ xử lý đơn hàng của bạn trong thời gian sớm nhất.</p>
  <a href="index.php" class="btn btn-primary mt-4">⬅️ Quay về Trang chủ</a>
  <a href="orders.php" class="btn btn-outline-secondary mt-4">📦 Xem đơn hàng</a>
</div>

<?php include 'includes/footer.php'; ?>
