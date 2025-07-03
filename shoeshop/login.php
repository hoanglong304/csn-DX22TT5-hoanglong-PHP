<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['username'] == 'admin' && $_POST['password'] == '123') {
        $_SESSION['user'] = 'admin';
        header('Location: index.php');
        exit;
    } else {
        $error = "Sai tài khoản hoặc mật khẩu!";
    }
}
include 'includes/header.php';
?>

<h2>Đăng nhập</h2>
<form method="post" class="w-50">
  <div class="mb-3">
    <label for="username" class="form-label">Tài khoản</label>
    <input type="text" name="username" class="form-control" required>
  </div>
  <div class="mb-3">
    <label for="password" class="form-label">Mật khẩu</label>
    <input type="password" name="password" class="form-control" required>
  </div>
  <button type="submit" class="btn btn-primary">Đăng nhập</button>
</form>
<?php if (isset($error)) echo "<div class='alert alert-danger mt-3'>$error</div>"; ?>

<?php include 'includes/footer.php'; ?>
