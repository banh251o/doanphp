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
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Kiểm tra lỗi
    $errors = [];

    // Kiểm tra tên đăng nhập đã tồn tại
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(1, $username, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        $errors[] = "Tên đăng nhập đã tồn tại.";
    }

    // Kiểm tra email hợp lệ
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email không hợp lệ.";
    }

    // Kiểm tra email đã tồn tại
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(1, $email, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        $errors[] = "Email đã được sử dụng.";
    }

    // Kiểm tra mật khẩu
    if (strlen($password) < 6) {
        $errors[] = "Mật khẩu phải có ít nhất 6 ký tự.";
    }

    // Kiểm tra xác nhận mật khẩu
    if ($password !== $confirm_password) {
        $errors[] = "Mật khẩu xác nhận không khớp.";
    }

    // Nếu không có lỗi, tiến hành đăng ký
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password, role, created_at) VALUES (?, ?, ?, 'user', NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $username, PDO::PARAM_STR);
        $stmt->bindValue(2, $email, PDO::PARAM_STR);
        $stmt->bindValue(3, $hashed_password, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Đăng ký thành công! <a href="login.php">Đăng nhập ngay</a>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Có lỗi xảy ra khi đăng ký. Vui lòng thử lại.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        }
    } else {
        foreach ($errors as $error) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    ' . htmlspecialchars($error) . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        }
    }
}
?>

<!-- Register Section -->
<section class="register-section">
    <div class="container">
        <div class="register-wrapper">
            <h2>Đăng ký</h2>
            <p class="section-subtitle">Tham gia cộng đồng PHP Việt Nam ngay hôm nay!</p>
            
            <form method="POST" class="register-form">
                <div class="form-group">
                    <label for="username" class="form-label">Tên đăng nhập:</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Nhập tên đăng nhập" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Nhập email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Mật khẩu:</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password" class="form-label">Xác nhận mật khẩu:</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Xác nhận mật khẩu" required>
                </div>
                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary btn-submit">Đăng ký</button>
                    <a href="index.php" class="btn btn-cancel">Hủy</a>
                </div>
                <p class="login-link">Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a></p>
            </form>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>