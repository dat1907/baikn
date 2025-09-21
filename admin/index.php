<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TechStore</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <nav class="sidebar">
            <div class="sidebar-header">
                <h2>TechStore Admin</h2>
            </div>
            <ul class="sidebar-nav">
                <li class="active"><a href="index.php"><i class="fas fa-tachometer-alt"></i> Tổng quan</a></li>
                <li><a href="products.php"><i class="fas fa-mobile-alt"></i> Sản phẩm</a></li>
                <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> Đơn hàng</a></li>
                <li><a href="users.php"><i class="fas fa-users"></i> Người dùng</a></li>
                <li><a href="settings.php"><i class="fas fa-cog"></i> Cài đặt</a></li>
                <li><a href="../index.php?logout=1"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
            </ul>
        </nav>
        
        <main class="main-content">
            <header class="top-bar">
                <div class="user-info">
                    <span>Xin chào, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <span class="admin-badge">Quản trị viên</span>
                </div>
            </header>
            
            <div class="dashboard-cards">
                <div class="card">
                    <div class="card-icon" style="background-color: #4e73df;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-info">
                        <h3>Người dùng</h3>
                        <p>1,234</p>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-icon" style="background-color: #1cc88a;">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="card-info">
                        <h3>Đơn hàng</h3>
                        <p>567</p>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-icon" style="background-color: #36b9cc;">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div class="card-info">
                        <h3>Sản phẩm</h3>
                        <p>89</p>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-icon" style="background-color: #f6c23e;">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="card-info">
                        <h3>Doanh thu</h3>
                        <p>1,234,000,000₫</p>
                    </div>
                </div>
            </div>
            
            <div class="recent-activity">
                <h2>Hoạt động gần đây</h2>
                <div class="activity-list">
                    <div class="activity-item">
                        <i class="fas fa-user-plus"></i>
                        <div class="activity-details">
                            <p>Người dùng mới đã đăng ký: <strong>nguyenvana</strong></p>
                            <span class="activity-time">5 phút trước</span>
                        </div>
                    </div>
                    <div class="activity-item">
                        <i class="fas fa-shopping-cart"></i>
                        <div class="activity-details">
                            <p>Đơn hàng mới #1234 đã được tạo</p>
                            <span class="activity-time">1 giờ trước</span>
                        </div>
                    </div>
                    <div class="activity-item">
                        <i class="fas fa-comment"></i>
                        <div class="activity-details">
                            <p>Bình luận mới từ <strong>tranthib</strong> về sản phẩm iPhone 13</p>
                            <span class="activity-time">3 giờ trước</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
