<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit;
}
include '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = intval($_POST['price']);
    $image = $_FILES['image']['name'];

    move_uploaded_file($_FILES['image']['tmp_name'], "../../assets/images/Product/$image");

    $stmt = $conn->prepare("INSERT INTO products (name, price, image) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $name, $price, $image);
    $stmt->execute();
    header("Location: list.php");
    exit;
}
?>

<?php include '../../includes/header.php'; ?>
<div class="container mt-5">
    <h2>➕ Thêm sản phẩm</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Giá</label>
            <input type="number" name="price" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Ảnh</label>
            <input type="file" name="image" class="form-control" required>
        </div>
        <button class="btn btn-primary">Lưu</button>
        <a href="list.php" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
<?php include '../../includes/footer.php'; ?>
