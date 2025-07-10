<?php
session_start();
include 'includes/db.php'; // Đảm bảo $conn được kết nối

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Truy vấn user từ database
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Kiểm tra tồn tại và đúng mật khẩu
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = $user['username'];
        $_SESSION['role'] = $user['role'];  // 🔹 Gán quyền
        
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

<p class="mt-3">Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>

<?php include 'includes/footer.php'; ?>