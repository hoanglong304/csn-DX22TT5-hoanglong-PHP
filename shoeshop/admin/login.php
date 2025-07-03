<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['username'] == 'admin' && $_POST['password'] == '123') {
        $_SESSION['admin'] = 'admin';
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Tài khoản hoặc mật khẩu sai.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Đăng nhập Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
<h2>Đăng nhập Admin</h2>
<form method="post" class="w-50">
  <div class="mb-3">
    <label class="form-label">Tài khoản</label>
    <input type="text" name="username" class="form-control">
  </div>
  <div class="mb-3">
    <label class="form-label">Mật khẩu</label>
    <input type="password" name="password" class="form-control">
  </div>
  <button class="btn btn-primary">Đăng nhập</button>
</form>
<?php if (isset($error)) echo "<div class='alert alert-danger mt-3'>$error</div>"; ?>
</body>
</html>
