<?php
// Kiểm tra xem session đã được khởi động chưa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cộng đồng PHP</title>
    <link rel="stylesheet" href="<?php echo str_contains($_SERVER['REQUEST_URI'], 'admin') ? '../css/style.css' : 'css/style.css'; ?>">
    <!-- Thêm Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="container header-container">
            <div class="left-section">
                <div class="logo-container">
                    <img src="<?php echo str_contains($_SERVER['REQUEST_URI'], 'admin') ? '../images/php_logo.png' : 'images/php_logo.png'; ?>" alt="PHP Logo" class="logo">
                </div>
                <h1>Cộng đồng PHP Việt Nam</h1>
            </div>
            <div class="header-content">
                <nav>
                    <ul>
                        <?php if (str_contains($_SERVER['REQUEST_URI'], 'admin')): ?>
                            <!-- Menu cho trang quản lý Admin -->
                            <li><a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>">Tổng quan</a></li>
                            <li><a href="manage_users.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'manage_users.php' ? 'active' : ''; ?>">Quản lý người dùng</a></li>
                            <li><a href="manage_posts.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'manage_posts.php' ? 'active' : ''; ?>">Quản lý bài viết</a></li>
                            <li><a href="manage_comments.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'manage_comments.php' ? 'active' : ''; ?>">Quản lý bình luận</a></li>
                            <li><a href="../index.php">Quay lại trang chủ</a></li>
                        <?php else: ?>
                            <!-- Menu cho các trang thông thường -->
                            <li><a href="index.php">Trang chủ</a></li>
                            <li><a href="about.php">Giới thiệu</a></li>
                            <li><a href="posts.php">Bài viết</a></li>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <li><a href="add_post.php">Đăng bài</a></li>
                                <li><a href="edit_profile.php">Hồ sơ</a></li>
                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <li><a href="admin/dashboard.php">Quản lý</a></li>
                                <?php endif; ?>
                                <li><a href="logout.php">Đăng xuất</a></li>
                            <?php else: ?>
                                <li><a href="login.php">Đăng nhập</a></li>
                                <li><a href="register.php">Đăng ký</a></li>
                            <?php endif; ?>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <main class="container">