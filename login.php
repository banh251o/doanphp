<?php
session_start();
include 'includes/db_connect.php';
include 'includes/header.php';

// Kiểm tra nếu người dùng đã đăng nhập, chuyển hướng về trang chủ
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(1, $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'] ?? 'user'; // Gán role nếu có
        header("Location: index.php");
        exit();
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Tên đăng nhập hoặc mật khẩu không đúng.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    }
}
?>

<!-- Login Section -->
<section class="login-section">
    <div class="container">
        <div class="login-wrapper">
            <h2>Đăng nhập</h2>
            <p class="section-subtitle">Đăng nhập để chia sẻ và kết nối với cộng đồng PHP Việt Nam!</p>
            
            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="username" class="form-label">Tên đăng nhập:</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Nhập tên đăng nhập" required>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Mật khẩu:</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
                </div>
                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary btn-submit">Đăng nhập</button>
                    <a href="index.php" class="btn btn-cancel">Hủy</a>
                </div>
                <p class="register-link">Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
            </form>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>