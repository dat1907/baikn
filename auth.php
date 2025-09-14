<?php
session_start();
require_once 'config/database.php';

// Xử lý đăng ký
if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $fullname = trim($_POST['fullname']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    
    // Validate input
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Mật khẩu xác nhận không khớp!";
        header("Location: index.php?register=1");
        exit();
    }
    
    try {
        // Check if username or email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "Tên đăng nhập hoặc email đã tồn tại!";
            header("Location: index.php?register=1");
            exit();
        }
        
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new user
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, fullname, phone, address) 
                              VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$username, $email, $hashed_password, $fullname, $phone, $address]);
        
        $_SESSION['success'] = "Đăng ký thành công! Vui lòng đăng nhập.";
        header("Location: index.php?login=1");
        exit();
        
    } catch (PDOException $e) {
        $_SESSION['error'] = "Đã xảy ra lỗi: " . $e->getMessage();
        header("Location: index.php?register=1");
        exit();
    }
}

// Xử lý đăng nhập
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Đăng nhập thành công
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin'];
            $_SESSION['success'] = "Đăng nhập thành công!";
            
            // Chuyển hướng đến trang admin nếu là admin
            if ($user['is_admin']) {
                header("Location: admin/");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Tên đăng nhập hoặc mật khẩu không đúng!";
            header("Location: index.php?login=1");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Đã xảy ra lỗi: " . $e->getMessage();
        header("Location: index.php?login=1");
        exit();
    }
}

// Xử lý đăng xuất
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
