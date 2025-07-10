<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    if ($stmt->get_result()->num_rows > 0) {
        $error = "Tài khoản đã tồn tại!";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $insert = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $insert->bind_param("ss", $username, $hashed);
        $insert->execute();

        $_SESSION['user_id'] = $insert->insert_id;
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit;
    }
}
include 'includes/header.php';
?>

<h2>Đăng ký</h2>
<form method="post" class="w-50 mx-auto">
  <div class="mb-3">
    <label>Tài khoản</label>
    <input type="text" name="username" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Mật khẩu</label>
    <input type="password" name="password" class="form-control" required>
  </div>
  <button type="submit" class="btn btn-success">Đăng ký</button>
</form>
<?php if (isset($error)) echo "<div class='alert alert-danger mt-3'>$error</div>"; ?>
<?php include 'includes/footer.php'; ?>
