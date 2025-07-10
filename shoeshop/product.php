<?php include 'includes/db.php'; ?>
<?php include 'includes/header.php'; ?>

<?php
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM products WHERE id = $id");
    if ($row = $result->fetch_assoc()) {
?>
<div class="row">
  <div class="col-md-5">
    <img src="assets/images/product/<?= $row['image'] ?>" class="img-fluid rounded" alt="<?= $row['name'] ?>">
  </div>
  <div class="col-md-7">
    <h2><?= $row['name'] ?></h2>
    <h4 class="text-danger"><?= number_format($row['price'], 0) ?> đ</h4>
    <p><?= nl2br($row['description']) ?></p>
    <a href="cart.php?action=add&id=<?= $row['id'] ?>" class="btn btn-success">🛒 Thêm vào giỏ hàng</a>
  </div>
</div>
<?php
    } else {
        echo "<div class='alert alert-warning'>Sản phẩm không tồn tại.</div>";
    }
}
?>

<?php include 'includes/footer.php'; ?>
