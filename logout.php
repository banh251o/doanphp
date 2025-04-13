<?php
session_start();
include 'includes/db_connect.php';
include 'includes/header.php';

// Đăng xuất người dùng
if (isset($_SESSION['user_id'])) {
    session_unset();
    session_destroy();
}
?>

<!-- Logout Section -->
<section class="logout-section">
    <div class="container">
        <div class="logout-wrapper">
            <h2>Đăng xuất thành công</h2>
            <p class="section-subtitle">Bạn đã đăng xuất khỏi hệ thống. Cảm ơn bạn đã tham gia cộng đồng PHP Việt Nam!</p>
            <div class="form-buttons">
                <a href="index.php" class="btn btn-primary btn-submit">Quay về trang chủ</a>
                <a href="login.php" class="btn btn-cancel">Đăng nhập lại</a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>