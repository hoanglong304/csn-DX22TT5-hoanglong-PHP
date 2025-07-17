<?php
session_start();
include 'includes/db.php';

// Kiểm tra quyền admin (giả sử user 'admin' là tài khoản admin)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("⛔ Bạn không có quyền truy cập trang này.");
}

// Lấy danh sách đơn hàng và người đặt
$sql = "SELECT o.id, o.user_id, u.username, o.status, o.created_at
        FROM orders o
        JOIN users u ON o.user_id = u.id
        ORDER BY o.created_at DESC";

$result = $conn->query($sql);
include 'includes/header.php';
?>

<div class="container mt-5">
  <h3 class="mb-4">📦 Quản lý đơn hàng</h3>
  <table class="table table-bordered table-hover">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Người đặt</th>
        <th>Trạng thái</th>
        <th>Ngày tạo</th>
        <th>Chi tiết</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['username']) ?></td>
          <td><?= htmlspecialchars($row['status']) ?></td>
          <td><?= $row['created_at'] ?></td>
          <td><a href="admin_order_detail.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Xem</a></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php include 'includes/footer.php'; ?>
