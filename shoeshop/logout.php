<?php
session_start();

// Xoá toàn bộ biến session (xóa thông tin đăng nhập)
session_unset();

// Hủy hoàn toàn session
session_destroy();

// Chuyển hướng người dùng về trang đăng nhập
header("Location: login.php");
exit;
