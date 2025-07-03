<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

if ($_GET['action'] ?? '' == 'add') {
    $id = intval($_GET['id']);
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    header("Location: cart.php");
    exit;
}

if ($_GET['action'] ?? '' == 'remove') {
    unset($_SESSION['cart'][intval($_GET['id'])]);
    header("Location: cart.php");
    exit;
}
?>

<h2>Giỏ hàng</h2>

<?php if (empty($_SESSION['cart'])): ?>
<div class="alert alert-info">Giỏ hàng của bạn đang trống.</div>
<?php else: ?>
<table class="table table-bordered">
  <thead class="table-secondary">
    <tr>
      <th>Tên sản phẩm</th>
      <th>Giá</th>
      <th>Số lượng</th>
      <th>Thành tiền</th>
      <th>Xoá</th>
    </tr>
  </thead>
  <tbody>
<?php
$total = 0;
foreach ($_SESSION['cart'] as $id => $qty):
  $result = $conn->query("SELECT * FROM products WHERE id = $id");
  $row = $result->fetch_assoc();
  $subtotal = $row['price'] * $qty;
  $total += $subtotal;
?>
  <tr>
    <td><?= $row['name'] ?></td>
    <td><?= number_format($row['price'], 0) ?> đ</td>
    <td><?= $qty ?></td>
    <td><?= number_format($subtotal, 0) ?> đ</td>
    <td><a href="cart.php?action=remove&id=<?= $id ?>" class="btn btn-sm btn-danger">X</a></td>
  </tr>
<?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
      <td colspan="2"><strong class="text-danger"><?= number_format($total, 0) ?> đ</strong></td>
    </tr>
  </tfoot>
</table>
<a href="checkout.php" class="btn btn-primary">✅ Tiến hành thanh toán</a>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
