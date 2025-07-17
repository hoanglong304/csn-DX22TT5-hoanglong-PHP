<?php
session_start();
ob_clean(); // Xóa mọi output không mong muốn

include 'includes/db.php';

// Trả JSON mặc định
header('Content-Type: application/json');

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = intval($_POST['product_id'] ?? 0);
$quantity = intval($_POST['quantity'] ?? 1);
$action = $_POST['action'] ?? 'add';

if ($product_id <= 0 || $quantity <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
    exit;
}

try {
    // 1. Tìm đơn hàng "pending" cho user
    $stmt = $conn->prepare("SELECT id FROM orders WHERE user_id = ? AND status = 'pending' LIMIT 1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $order_id = $row['id'];
    } else {
        // 2. Chưa có đơn thì tạo mới
        $createOrder = $conn->prepare("INSERT INTO orders (user_id, status) VALUES (?, 'pending')");
        $createOrder->bind_param("i", $user_id);
        $createOrder->execute();
        $order_id = $createOrder->insert_id;
    }

    // 3. Kiểm tra sản phẩm đã có chưa
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
        // Lấy giá sản phẩm
        $priceStmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
        $priceStmt->bind_param("i", $product_id);
        $priceStmt->execute();
        $priceRow = $priceStmt->get_result()->fetch_assoc();
        $price = $priceRow['price'] ?? 0;

        // Thêm sản phẩm mới
        $insert = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $insert->bind_param("iiii", $order_id, $product_id, $quantity, $price);
        $insert->execute();
    }

    // 4. Kết quả trả về
    if ($action === 'buy') {
        echo json_encode(['success' => true, 'redirect' => 'cart.php']);
    } else {
        // Tính tổng số lượng sản phẩm
        $countStmt = $conn->prepare("SELECT SUM(quantity) AS total FROM order_items WHERE order_id = ?");
        $countStmt->bind_param("i", $order_id);
        $countStmt->execute();
        $totalRow = $countStmt->get_result()->fetch_assoc();
        $totalItems = $totalRow['total'] ?? 0;

        echo json_encode(['success' => true, 'total' => $totalItems]);
    }
} catch (Exception $e) {
    // Nếu có lỗi
    echo json_encode(['success' => false, 'message' => 'Lỗi máy chủ']);
}
exit;
