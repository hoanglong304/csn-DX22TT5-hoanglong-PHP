<?php
include '../../includes/db.php';
$id = $_GET['id'] ?? 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $desc = $_POST['description'];
    $image = $_FILES['image']['name'];

    if ($image) {
        move_uploaded_file($_FILES['image']['tmp_name'], "../../uploads/" . $image);
        $sql = "UPDATE products SET name='$name', price='$price', description='$desc', image='$image' WHERE id=$id";
    } else {
        $sql = "UPDATE products SET name='$name', price='$price', description='$desc' WHERE id=$id";
    }

    $conn->query($sql);
    header("Location: list.php");
    exit;
}

$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
include '../../includes/header.php';
?>

<h2>Sửa sản phẩm</h2>
<form method="post" enctype="multipart/form-data" class="w-50">
  <div class="mb-3">
    <label class="form-label">Tên sản phẩm</label>
    <input name="name" value="<?= $product['name'] ?>" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Giá</label>
    <input name="price" type="number" value="<?= $product['price'] ?>" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Mô tả</label>
    <textarea name="description" class="form-control"><?= $product['description'] ?></textarea>
  </div>
  <div class="mb-3">
    <label class="form-label">Ảnh (nếu muốn thay)</label>
    <input type="file" name="image" class="form-control">
  </div>
  <button type="submit" class="btn btn-primary">Cập nhật</button>
</form>

<?php include '../../includes/footer.php'; ?>
