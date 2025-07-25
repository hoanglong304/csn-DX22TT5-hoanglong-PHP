<?php
session_start();
include 'includes/db.php';

header('Content-Type: application/json');

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
        echo json_encode(['success' => false, 'redirect' => 'login.php', 'message' => 'Bạn cần đăng nhập để mua hàng']);
        exit;
    } else {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
        exit;
    }
}

// Xử lý riêng từng action
if ($action === 'add') {
    // 1. Tìm hoặc tạo đơn hàng "pending"
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

    // 2. Kiểm tra sản phẩm đã có trong giỏ chưa
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

    // 3. Trả lại số lượng tổng giỏ
    $count = $conn->prepare("SELECT SUM(quantity) as total FROM order_items WHERE order_id = ?");
    $count->bind_param("i", $order_id);
    $count->execute();
    $totalRow = $count->get_result()->fetch_assoc();
    $total = $totalRow['total'] ?? 0;

    echo json_encode(['success' => true, 'total' => $total]);
    exit;

} elseif ($action === 'buy') {
    // 👉 Mua riêng sản phẩm này, không đụng giỏ hàng (pending)

    // 1. Lấy giá sản phẩm
    $getPrice = $conn->prepare("SELECT price FROM products WHERE id = ?");
    $getPrice->bind_param("i", $product_id);
    $getPrice->execute();
    $price = $getPrice->get_result()->fetch_assoc()['price'] ?? 0;

    if ($price <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm']);
        exit;
    }

    // 2. Tạo đơn hàng hoàn chỉnh (completed)
    $stmt = $conn->prepare("INSERT INTO orders (user_id, status, created_at, completed_at) VALUES (?, 'completed', NOW(), NOW())");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // 3. Thêm sản phẩm vào đơn
    $insert = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $insert->bind_param("iiii", $order_id, $product_id, $quantity, $price);
    $insert->execute();

    // 4. Phản hồi để JS redirect
    echo json_encode(['success' => true, 'redirect' => "order_success.php?order_id=$order_id"]);
    exit;

} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ']);
    exit;
}
?>
