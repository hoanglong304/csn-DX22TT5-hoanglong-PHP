<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
  header("Location: ../../index.php");
  exit;
}

include '../../includes/db.php';
$result = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<?php include '../../includes/header.php'; ?>

<div class="container mt-5">
  <h2 class="mb-4">📦 Danh sách sản phẩm</h2>
  <a href="add.php" class="btn btn-success mb-3">➕ Thêm sản phẩm</a>
  <table class="table table-bordered">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Tên</th>
        <th>Ảnh</th>
        <th>Giá</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><img src="../../assets/images/Product/<?= $row['image'] ?>" width="60"></td>
          <td><?= number_format($row['price']) ?> đ</td>
          <td>
            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">✏️ Sửa</a>
            <button onclick="confirmDelete(<?= $row['id'] ?>)" class="btn btn-danger btn-sm">🗑 Xoá</button>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function confirmDelete(id) {
    Swal.fire({
      title: 'Bạn có chắc chắn muốn xoá?',
      text: "Dữ liệu sẽ không thể khôi phục!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Xoá',
      cancelButtonText: 'Huỷ'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location = 'delete.php?id=' + id;
      }
    });
  }
</script>

<?php include '../../includes/footer.php'; ?>