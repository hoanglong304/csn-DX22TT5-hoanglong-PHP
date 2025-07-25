<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit;
}
include '../../includes/db.php';

// Lấy danh mục và thương hiệu
$categories = $conn->query("SELECT * FROM category");
$brands = $conn->query("SELECT * FROM brand");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = intval($_POST['price']);
    $category_id = intval($_POST['category_id']);
    $brand_id = intval($_POST['brand_id']);
    $description = $_POST['description'] ?? '';
    $image = $_FILES['image']['name'];

    // Upload ảnh
    $targetDir = "../../assets/images/product/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
    move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $image);

    // Thêm vào DB
    $stmt = $conn->prepare("INSERT INTO products (name, price, image, category_id, brand_id, description, created_at)
                            VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sisdds", $name, $price, $image, $category_id, $brand_id, $description);
    $stmt->execute();

    header("Location: list.php");
    exit;
}
?>
<?php include '../../includes/header.php'; ?>
<div class="container mt-5">
    <h2>➕ Thêm sản phẩm mới</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Giá (VNĐ)</label>
            <input type="number" name="price" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Danh mục</label>
            <select name="category_id" class="form-control" required>
                <option value="">-- Chọn danh mục --</option>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Thương hiệu</label>
            <select name="brand_id" class="form-control" required>
                <option value="">-- Chọn thương hiệu --</option>
                <?php while ($brand = $brands->fetch_assoc()): ?>
                    <option value="<?= $brand['id'] ?>"><?= htmlspecialchars($brand['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="description" class="form-control" rows="4"></textarea>
        </div>

        <div class="mb-3">
            <label>Ảnh sản phẩm</label>
            <input type="file" name="image" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Lưu</button>
        <a href="list.php" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
<?php include '../../includes/footer.php'; ?>