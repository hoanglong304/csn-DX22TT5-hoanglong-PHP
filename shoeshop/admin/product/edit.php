<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit;
}
include '../../includes/db.php';

$id = intval($_GET['id'] ?? 0);

// Lấy dữ liệu sản phẩm
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    echo "Không tìm thấy sản phẩm!";
    exit;
}

// Lấy danh mục và thương hiệu
$categories = $conn->query("SELECT * FROM category");
$brands = $conn->query("SELECT * FROM brand");

// Xử lý khi submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = intval($_POST['price']);
    $category_id = intval($_POST['category_id']);
    $brand_id = intval($_POST['brand_id']);
    $description = $_POST['description'];

    if ($_FILES['image']['name']) {
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../../assets/images/Product/$image");
    } else {
        $image = $product['image'];
    }

    $update = $conn->prepare("UPDATE products SET name = ?, price = ?, image = ?, category_id = ?, brand_id = ?, description = ? WHERE id = ?");
    $update->bind_param("sisddsi", $name, $price, $image, $category_id, $brand_id, $description, $id);
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
            <label>Danh mục</label>
            <select name="category_id" class="form-control" required>
                <option value="">-- Chọn danh mục --</option>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $product['category_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Thương hiệu</label>
            <select name="brand_id" class="form-control" required>
                <option value="">-- Chọn thương hiệu --</option>
                <?php while ($brand = $brands->fetch_assoc()): ?>
                    <option value="<?= $brand['id'] ?>" <?= $brand['id'] == $product['brand_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($brand['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($product['description']) ?></textarea>
        </div>

        <div class="mb-3">
            <label>Ảnh hiện tại</label><br>
            <img src="../../assets/images/Product/<?= $product['image'] ?>" width="100" class="mb-2"><br>
            <input type="file" name="image" class="form-control">
        </div>

        <button class="btn btn-primary">Cập nhật</button>
        <a href="list.php" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
<?php include '../../includes/footer.php'; ?>