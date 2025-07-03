<?php include '../../includes/db.php'; ?>
<?php include '../../includes/auth.php'; ?>
<?php include '../../includes/header.php'; ?>

<h2>Quản lý đơn hàng</h2>
<table class="table table-bordered">
  <thead class="table-light">
    <tr>
      <th>ID</th>
      <th>Khách hàng</th>
      <th>Tổng tiền</th>
      <th>Thời gian</th>
      <th>Chi tiết</th>
    </tr>
  </thead>
  <tbody>
<?php
$result = $conn->query("SELECT * FROM orders ORDER BY id DESC");
while($row = $result->fetch_assoc()):
?>
  <tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['name'] ?><br><?= $row['phone'] ?><br><?= $row['address'] ?></td>
    <td><?= number_format($row['total'], 0) ?> đ</td>
    <td><?= $row['created_at'] ?></td>
    <td><a href="detail.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">Xem</a></td>
  </tr>
<?php endwhile; ?>
  </tbody>
</table>

<?php include '../../includes/footer.php'; ?>
