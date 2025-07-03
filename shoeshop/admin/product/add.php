<?php
include '../../includes/db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $price = $_POST['price'];
  $desc = $_POST['description'];
  $image = $_FILES['image']['name'];
  move_uploaded_file($_FILES['image']['tmp_name'], "../../uploads/" . $image);

  $conn->query("INSERT INTO products(name, price, description, image) VALUES('$name', '$price', '$desc', '$image')");
  header("Location: list.php");
}
include '../../includes/header.php';
?>

<h2>Thêm sản phẩm mới</h2>
<form method="post" enctype="multipart/form-data" class="w-50">
  <div class="mb-3">
    <label class="form-label">Tên sản phẩm</label>
    <input name="name" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Giá</label>
    <input name="price" type="number" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Mô tả</label>
    <textarea name="description" class="form-control" rows="3"></textarea>
  </div>
  <div class="mb-3">
    <label class="form-label">Ảnh</label>
    <input type="file" name="image" class="form-control" required>
  </div>
  <button type="submit" class="btn btn-success">Lưu sản phẩm</button>
</form>

<?php include '../../includes/footer.php'; ?>
