<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $total = 0;
    foreach ($_SESSION['cart'] as $id => $qty) {
        $product = $conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();
        $total += $product['price'] * $qty;
    }

    $conn->query("INSERT INTO orders (name, phone, address, total) VALUES ('$name', '$phone', '$address', $total)");
    $order_id = $conn->insert_id;

    foreach ($_SESSION['cart'] as $id => $qty) {
        $product = $conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();
        $conn->query("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($order_id, $id, $qty, {$product['price']})");
    }

    $_SESSION['cart'] = [];
    echo "<div class='alert alert-success'>🎉 Đặt hàng thành công!</div>";
    include 'includes/footer.php';
    exit;
}
?>

<h2>Thông tin thanh toán</h2>
<form method="post" class="w-50">
  <div class="mb-3">
    <label class="form-label">Họ tên</label>
    <input name="name" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Số điện thoại</label>
    <input name="phone" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Địa chỉ giao hàng</label>
    <textarea name="address" class="form-control" rows="3" required></textarea>
  </div>
  <button class="btn btn-primary">Xác nhận đặt hàng</button>
</form>

<?php include 'includes/footer.php'; ?>
