<?php
session_start();
include '../../includes/db.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit;
}

// Truy vấn danh sách đơn hàng
$sql = "SELECT o.id, o.user_id, u.username, o.status, o.created_at
        FROM orders o
        JOIN users u ON o.user_id = u.id
        ORDER BY o.id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <title>Quản lý đơn hàng</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-light">
  <div class="container py-5">
    <h2 class="mb-4 text-center">📦 Quản lý đơn hàng</h2>

    <table class="table table-bordered table-striped bg-white">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Người đặt</th>
          <th>Trạng thái</th>
          <th>Ngày đặt</th>
          <th>Chi tiết</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td>
              <?php
              $badgeClass = match($row['status']) {
                  'pending' => 'warning',
                  'completed' => 'success',
                  'cancelled' => 'danger',
                  default => 'secondary',
              };
              ?>
              <span class="badge bg-<?= $badgeClass ?>"><?= ucfirst($row['status']) ?></span>
            </td>
            <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
            <td>
              <a href="detail.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">👁 Xem</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <a href="../../index.php" class="btn btn-secondary mt-3">← Quay lại trang chủ</a>
  </div>
</body>

</html>
