<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Lấy đơn hàng "pending" hiện tại
$stmt = $conn->prepare("SELECT id FROM orders WHERE user_id = ? AND status = 'pending' LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

$total = 0;

if ($order):
    $order_id = $order['id'];

    // Lấy danh sách sản phẩm trong đơn hàng
    $itemStmt = $conn->prepare("
        SELECT p.name, p.image, oi.quantity, oi.price, p.id AS product_id
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id = ?
    ");
    $itemStmt->bind_param("i", $order_id);
    $itemStmt->execute();
    $items = $itemStmt->get_result();
?>

<div class="container mt-5">
    <h2>🛒 Giỏ hàng của bạn</h2>

    <?php if ($items->num_rows > 0): ?>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Hình ảnh</th>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $items->fetch_assoc()): 
                    $subtotal = $row['price'] * $row['quantity'];
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><img src="assets/images/Product/<?= $row['image'] ?>" width="60"></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= $row['quantity'] ?></td>
                        <td><?= number_format($row['price'], 0) ?> đ</td>
                        <td><?= number_format($subtotal, 0) ?> đ</td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-end">Tổng cộng:</th>
                    <th><?= number_format($total, 0) ?> đ</th>
                </tr>
            </tfoot>
        </table>

        <div class="text-end">
            <a href="checkout.php" class="btn btn-success">✅ Tiến hành đặt hàng</a>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Giỏ hàng của bạn đang trống.</div>
    <?php endif; ?>
</div>

<?php
else:
    echo "<div class='container mt-5 alert alert-info'>Bạn chưa có sản phẩm nào trong giỏ hàng.</div>";
endif;

include 'includes/footer.php';
?>
