<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?login=1");
    exit();
}

// Get user data
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    
    try {
        // Check if email is already taken by another user
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $user_id]);
        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "Email đã được sử dụng bởi tài khoản khác!";
        } else {
            // Update user data
            $stmt = $pdo->prepare("UPDATE users SET fullname = ?, email = ?, phone = ?, address = ? WHERE id = ?");
            $stmt->execute([$fullname, $email, $phone, $address, $user_id]);
            $_SESSION['success'] = "Cập nhật thông tin thành công!";
            
            // Refresh user data
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Đã xảy ra lỗi: " . $e->getMessage();
    }
    
    header("Location: profile.php");
    exit();
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (!password_verify($current_password, $user['password'])) {
        $_SESSION['error'] = "Mật khẩu hiện tại không đúng!";
    } elseif ($new_password !== $confirm_password) {
        $_SESSION['error'] = "Mật khẩu mới không khớp!";
    } else {
        try {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashed_password, $user_id]);
            $_SESSION['success'] = "Đổi mật khẩu thành công!";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Đã xảy ra lỗi: " . $e->getMessage();
        }
    }
    
    header("Location: profile.php#password");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ cá nhân - TechStore</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="logo">
                <h1>TechStore</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Trang chủ</a></li>
                    <li><a href="index.php#products">Sản phẩm</a></li>
                    <li><a href="index.php#about">Giới thiệu</a></li>
                    <li><a href="index.php#contact">Liên hệ</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="user-menu">
                            <a href="#" class="user-dropdown">
                                <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                                <?php if ($_SESSION['is_admin']): ?>
                                    <span class="admin-badge">Admin</span>
                                <?php endif; ?>
                            </a>
                            <div class="dropdown-menu">
                                <?php if ($_SESSION['is_admin']): ?>
                                    <a href="admin/"><i class="fas fa-tachometer-alt"></i> Trang quản trị</a>
                                <?php endif; ?>
                                <a href="profile.php"><i class="fas fa-user"></i> Hồ sơ</a>
                                <a href="?logout=1"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
                            </div>
                        </li>
                    <?php else: ?>
                        <li><a href="index.php?login=1" class="login-btn">Đăng nhập</a></li>
                        <li><a href="index.php?register=1" class="register-btn">Đăng ký</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            <div class="cart">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count">0</span>
            </div>
        </div>
    </header>

    <!-- Profile Section -->
    <section class="profile-section">
        <div class="container">
            <div class="profile-container">
                <div class="profile-sidebar">
                    <div class="profile-avatar">
                        <i class="fas fa-user-circle"></i>
                        <h3><?php echo htmlspecialchars($user['fullname']); ?></h3>
                        <p><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                    <ul class="profile-menu">
                        <li><a href="#profile" class="active"><i class="fas fa-user"></i> Thông tin cá nhân</a></li>
                        <li><a href="#password"><i class="fas fa-key"></i> Đổi mật khẩu</a></li>
                        <li><a href="#orders"><i class="fas fa-shopping-bag"></i> Đơn hàng của tôi</a></li>
                        <li><a href="?logout=1"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
                    </ul>
                </div>
                
                <div class="profile-content">
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>
                    
                    <!-- Profile Information -->
                    <div id="profile" class="profile-section-content">
                        <h2>Thông tin cá nhân</h2>
                        <form action="profile.php" method="POST" class="profile-form">
                            <div class="form-group">
                                <label for="fullname">Họ và tên</label>
                                <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="username">Tên đăng nhập</label>
                                <input type="text" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                                <small class="form-text">Không thể thay đổi tên đăng nhập</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Số điện thoại</label>
                                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="address">Địa chỉ</label>
                                <textarea id="address" name="address" rows="3"><?php echo htmlspecialchars($user['address']); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" name="update_profile" class="btn btn-primary">Cập nhật thông tin</button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Change Password -->
                    <div id="password" class="profile-section-content" style="display: none;">
                        <h2>Đổi mật khẩu</h2>
                        <form action="profile.php#password" method="POST" class="profile-form">
                            <div class="form-group">
                                <label for="current_password">Mật khẩu hiện tại</label>
                                <input type="password" id="current_password" name="current_password" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="new_password">Mật khẩu mới</label>
                                <input type="password" id="new_password" name="new_password" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="confirm_password">Xác nhận mật khẩu mới</label>
                                <input type="password" id="confirm_password" name="confirm_password" required>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" name="change_password" class="btn btn-primary">Đổi mật khẩu</button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Orders -->
                    <div id="orders" class="profile-section-content" style="display: none;">
                        <h2>Đơn hàng của tôi</h2>
                        <div class="no-orders">
                            <i class="fas fa-shopping-bag"></i>
                            <p>Bạn chưa có đơn hàng nào</p>
                            <a href="index.php#products" class="btn btn-primary">Mua sắm ngay</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Về chúng tôi</h3>
                    <p>TechStore tự hào là địa chỉ uy tín cung cấp các sản phẩm điện thoại chính hãng với giá cả cạnh tranh nhất thị trường.</p>
                </div>
                <div class="footer-section">
                    <h3>Liên hệ</h3>
                    <p><i class="fas fa-map-marker-alt"></i> 123 Đường Minh Khai, Hai Bà Trưng, TP. Hà Nội</p>
                    <p><i class="fas fa-phone"></i> 0909 123 456</p>
                    <p><i class="fas fa-envelope"></i> info@techstore.vn</p>
                </div>
                <div class="footer-section">
                    <h3>Kết nối với chúng tôi</h3>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2023 TechStore. Tất cả các quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>

    <script>
        // Tab navigation
        document.addEventListener('DOMContentLoaded', function() {
            // Show section based on URL hash
            const hash = window.location.hash || '#profile';
            showSection(hash);
            
            // Update active menu item
            document.querySelectorAll('.profile-menu a').forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === hash) {
                    link.classList.add('active');
                }
                
                // Add click event to menu items
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = this.getAttribute('href');
                    window.location.hash = target;
                    showSection(target);
                    
                    // Update active menu item
                    document.querySelectorAll('.profile-menu a').forEach(item => {
                        item.classList.remove('active');
                    });
                    this.classList.add('active');
                });
            });
            
            // Show section function
            function showSection(sectionId) {
                // Hide all sections
                document.querySelectorAll('.profile-section-content').forEach(section => {
                    section.style.display = 'none';
                });
                
                // Show target section
                const targetSection = document.querySelector(sectionId);
                if (targetSection) {
                    targetSection.style.display = 'block';
                }
            }
            
            // Handle hash change
            window.addEventListener('hashchange', function() {
                const hash = window.location.hash || '#profile';
                showSection(hash);
                
                // Update active menu item
                document.querySelectorAll('.profile-menu a').forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === hash) {
                        link.classList.add('active');
                    }
                });
            });
        });
    </script>
</body>
</html>
