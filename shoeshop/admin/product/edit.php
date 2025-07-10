<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit;
}
include '../../includes/db.php';

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = intval($_POST['price']);

    if ($_FILES['image']['name']) {
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../../assets/images/Product/$image");
    } else {
        $image = $product['image'];
    }

    $update = $conn->prepare("UPDATE products SET name = ?, price = ?, image = ? WHERE id = ?");
    $update->bind_param("sisi", $name, $price, $image, $id);
    $update->execute();
    header("Location: list.php");
    exit;
}
?>

<?php include '../../includes/header.php'; ?>
<div class="container mt-5">
    <h2>✏️ Sửa sản phẩm</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Giá</label>
            <input type="number" name="price" class="form-control" value="<?= $product['price'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Ảnh</label><br>
            <img src="../../assets/images/Product/<?= $product['image'] ?>" width="100" class="mb-2">
            <input type="file" name="image" class="form-control">
        </div>
        <button class="btn btn-primary">Cập nhật</button>
        <a href="list.php" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
<?php include '../../includes/footer.php'; ?>
