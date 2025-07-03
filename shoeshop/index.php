<?php include 'includes/db.php'; ?>
<?php include 'includes/header.php'; ?>

<?php
$search = $_GET['search'] ?? '';
$sql = "SELECT * FROM products";
if ($search != '') {
    $sql .= " WHERE name LIKE '%" . $conn->real_escape_string($search) . "%'";
}
$sql .= " ORDER BY id DESC";
$result = $conn->query($sql);
?>

<form class="d-flex mb-4" method="get">
  <input type="text" name="search" class="form-control me-2" placeholder="Tìm giày..." value="<?= htmlspecialchars($search) ?>">
  <button class="btn btn-outline-primary">Tìm kiếm</button>
</form>

<h2 class="mb-4">Sản phẩm mới nhất</h2>
<div class="row row-cols-1 row-cols-md-4 g-4">
<?php
$sql = "SELECT * FROM products ORDER BY id DESC LIMIT 12";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
?>
  <div class="col">
    <div class="card h-100">
      <img src="uploads/<?= $row['image'] ?>" class="card-img-top" alt="<?= $row['name'] ?>">
      <div class="card-body">
        <h5 class="card-title"><?= $row['name'] ?></h5>
        <p class="card-text text-danger"><?= number_format($row['price'], 0) ?> đ</p>
        <a href="product.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Xem chi tiết</a>
      </div>
    </div>
  </div>
<?php } ?>
</div>

<?php include 'includes/footer.php'; ?>
