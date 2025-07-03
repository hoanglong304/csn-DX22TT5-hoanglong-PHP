<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['username'] && $_POST['password']) {
        $_SESSION['user'] = $_POST['username'];
        header('Location: index.php');
        exit;
    } else {
        $error = "Vui lòng nhập đầy đủ thông tin.";
    }
}
include 'includes/header.php';
?>

<h2>Đăng ký tài khoản</h2>
<form method="post" class="w-50">
  <div class="mb-3">
    <label class="form-label">Tài khoản</label>
    <input type="text" name="username" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Mật khẩu</label>
    <input type="password" name="password" class="form-control" required>
  </div>
  <button type="submit" class="btn btn-success">Đăng ký</button>
</form>
<?php if (isset($error)) echo "<div class='alert alert-danger mt-3'>$error</div>"; ?>

<?php include 'includes/footer.php'; ?>
