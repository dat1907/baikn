<?php
require_once 'config/database.php';

try {
    // Create users table
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        fullname VARCHAR(100) NOT NULL,
        phone VARCHAR(20),
        address TEXT,
        is_admin BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);
    
    // Create default admin user
    $username = 'admin';
    $email = 'admin@techstore.vn';
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $fullname = 'Quản trị viên';
    $is_admin = 1;
    
    $stmt = $pdo->prepare("INSERT IGNORE INTO users (username, email, password, fullname, is_admin) 
                          VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$username, $email, $password, $fullname, $is_admin]);
    
    echo "Cài đặt cơ sở dữ liệu thành công!<br>";
    echo "Thông tin đăng nhập mặc định:<br>";
    echo "Tài khoản: admin<br>";
    echo "Mật khẩu: admin123<br>";
    echo "<a href='index.php'>Về trang chủ</a>";
    
} catch(PDOException $e) {
    die("Lỗi khi tạo bảng: " . $e->getMessage());
}
?>
