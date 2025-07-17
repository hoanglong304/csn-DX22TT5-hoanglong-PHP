.<?php
    session_start();
    include 'includes/db.php';
    include 'includes/header.php';

    // Kiểm tra nếu chưa đăng nhập thì chuyển hướng về trang đăng nhập
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    ?>

<div class="container mt-5 pt-4">
    <h2 class="mb-4">📦 Đơn hàng của bạn</h2>

    <?php
    // Truy vấn các đơn hàng của người dùng
    $sql = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
    $orders = $conn->query($sql);

    if ($orders->num_rows > 0) {
        while ($order = $orders->fetch_assoc()) {
            $order_id = $order['id'];

            echo "<div class='card mb-4'>";
            echo "<div class='card-header bg-light'>";
            echo "<strong>Đơn hàng #{$order_id}</strong> - Ngày đặt: {$order['created_at']}";
            echo "</div>";
            echo "<div class='card-body'>";

            // Truy vấn chi tiết sản phẩm trong đơn hàng
            $details = $conn->query("
              SELECT p.name, p.image, od.quantity, od.price
              FROM order_items od
              JOIN products p ON od.product_id = p.id
              WHERE od.order_id = $order_id
          ");

            if ($details->num_rows > 0) {
                echo "<table class='table table-bordered'>";
                echo "<thead><tr><th>Ảnh</th><th>Tên SP</th><th>SL</th><th>Giá</th><th>Tổng</th></tr></thead><tbody>";

                $total = 0;
                while ($item = $details->fetch_assoc()) {
                    $subtotal = $item['quantity'] * $item['price'];
                    $total += $subtotal;

                    echo "<tr>";
                    echo "<td><img src='assets/images/product/{$item['image']}' width='50'></td>";
                    echo "<td>{$item['name']}</td>";
                    echo "<td>{$item['quantity']}</td>";
                    echo "<td>" . number_format($item['price'], 0) . " đ</td>";
                    echo "<td>" . number_format($subtotal, 0) . " đ</td>";
                    echo "</tr>";
                }

                echo "</tbody></table>";
                echo "<p class='text-end fw-bold'>Tổng cộng: <span class='text-danger'>" . number_format($total, 0) . " đ</span></p>";
            } else {
                echo "<p>Không có sản phẩm trong đơn hàng.</p>";
            }

            echo "</div></div>";
        }
    } else {
        echo "<div class='alert alert-info'>Bạn chưa có đơn hàng nào.</div>";
    }
    ?>

    <a href="index.php" class="btn btn-secondary mt-3">⬅️ Tiếp tục mua sắm</a>
</div>

<?php include 'includes/footer.php'; ?>