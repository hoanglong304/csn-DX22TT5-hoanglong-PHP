<?php include '../../includes/db.php'; ?>
<?php include '../../includes/header.php'; ?>
<div class="d-flex justify-content-between align-items-center">
  <h2>Quản lý sản phẩm</h2>
  <a href="add.php" class="btn btn-success">+ Thêm sản phẩm</a>
</div>
<table class="table table-bordered mt-3">
  <thead class="table-light">
    <tr>
      <th>ID</th>
      <th>Tên</th>
      <th>Giá</th>
      <th>Ảnh</th>
      <th>Thao tác</th>
    </tr>
  </thead>
  <tbody>
<?php
$result = $conn->query("SELECT * FROM products");
while($row = $result->fetch_assoc()):
?>
  <tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['name'] ?></td>
    <td><?= number_format($row['price'], 0) ?> đ</td>
    <td><img src="../../uploads/<?= $row['image'] ?>" width="60"></td>
    <td>
      <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Sửa</a>
      <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xoá sản phẩm này?')">Xoá</a>
    </td>
  </tr>
<?php endwhile; ?>
  </tbody>
</table>
<?php include '../../includes/footer.php'; ?>
