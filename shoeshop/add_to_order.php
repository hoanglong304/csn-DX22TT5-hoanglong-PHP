<?php
session_start();
include 'includes/db.php';

$product_id = intval($_POST['product_id'] ?? 0);
$quantity = intval($_POST['quantity'] ?? 1);
$action = $_POST['action'] ?? 'add';

$user_id = $_SESSION['user_id'] ?? 0;

if ($product_id <= 0 || $quantity <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
    exit;
}

// Nếu chưa đăng nhập
if (!$user_id) {
    if ($action === 'buy') {
        // Mua ngay → cần chuyển hướng
        echo "<script>alert('Bạn cần đăng nhập để mua hàng!'); window.location='login.php';</script>";
        exit;
    } else {
        // Thêm giỏ → trả JSON
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
        exit;
    }
}

// Với cả "add" và "buy", tìm hoặc tạo đơn hàng trạng thái pending
$stmt = $conn->prepare("SELECT id FROM orders WHERE user_id = ? AND status = 'pending' LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $order_id = $row['id'];
} else {
    $stmt = $conn->prepare("INSERT INTO orders (user_id, status, created_at) VALUES (?, 'pending', NOW())");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $order_id = $stmt->insert_id;
}

// Kiểm tra sản phẩm đã có chưa
$check = $conn->prepare("SELECT id, quantity FROM order_items WHERE order_id = ? AND product_id = ?");
$check->bind_param("ii", $order_id, $product_id);
$check->execute();
$item = $check->get_result()->fetch_assoc();

if ($item) {
    $newQty = $item['quantity'] + $quantity;
    $update = $conn->prepare("UPDATE order_items SET quantity = ? WHERE id = ?");
    $update->bind_param("ii", $newQty, $item['id']);
    $update->execute();
} else {
    $getPrice = $conn->prepare("SELECT price FROM products WHERE id = ?");
    $getPrice->bind_param("i", $product_id);
    $getPrice->execute();
    $price = $getPrice->get_result()->fetch_assoc()['price'] ?? 0;

    $insert = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $insert->bind_param("iiii", $order_id, $product_id, $quantity, $price);
    $insert->execute();
}

// Nếu action là "buy", cập nhật trạng thái và chuyển hướng sang trang thành công
if ($action === 'buy') {
    // Chuyển order thành completed
    $updateOrder = $conn->prepare("UPDATE orders SET status = 'completed', completed_at = NOW() WHERE id = ?");
    $updateOrder->bind_param("i", $order_id);
    $updateOrder->execute();

    echo "<script>window.location='order_success.php?order_id=$order_id';</script>";
    exit;
}

// Nếu action là "add", trả JSON để AJAX xử lý
// Trả về tổng số lượng trong giỏ hiện tại
$count = $conn->prepare("SELECT SUM(quantity) as total FROM order_items WHERE order_id = ?");
$count->bind_param("i", $order_id);
$count->execute();
$totalRow = $count->get_result()->fetch_assoc();
$total = $totalRow['total'] ?? 0;

echo json_encode(['success' => true, 'total' => $total]);
exit;
