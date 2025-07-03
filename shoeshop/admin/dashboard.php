<?php
include '../includes/db.php';
include '../includes/header.php';

$totalProducts = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];
?>

<h2>📊 Bảng điều khiển (Dashboard)</h2>
<div class="row mt-4">
  <div class="col-md-4">
    <div class="card text-white bg-primary mb-3">
      <div class="card-body">
        <h5 class="card-title">Tổng số sản phẩm</h5>
        <p class="card-text fs-3"><?= $totalProducts ?></p>
      </div>
    </div>
  </div>
  <!-- Có thể thêm thống kê doanh thu, đơn hàng ở đây -->
</div>

<?php include '../includes/footer.php'; ?>
