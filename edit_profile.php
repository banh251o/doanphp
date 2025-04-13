<?php 
session_start();
include 'includes/db_connect.php';
include 'includes/header.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Lấy thông tin người dùng
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bindValue(1, $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo '<div class="alert alert-danger">Không tìm thấy thông tin người dùng.</div>';
    include 'includes/footer.php';
    exit();
}

// Gán giá trị mặc định cho full_name nếu không có cột
$user['full_name'] = $user['full_name'] ?? '';
$stmt->bindValue(1, $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo '<div class="alert alert-danger">Không tìm thấy thông tin người dùng.</div>';
    include 'includes/footer.php';
    exit();
}

// Xử lý cập nhật hồ sơ
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Kiểm tra email hợp lệ
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Email không hợp lệ.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    } else {
        // Kiểm tra email đã tồn tại (trừ email của chính người dùng)
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND id != ?");
        $stmt->bindValue(1, $email, PDO::PARAM_STR);
        $stmt->bindValue(2, $user_id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Email đã được sử dụng bởi người dùng khác.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        } else {
            // Cập nhật thông tin hồ sơ
            if (!empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET full_name = ?, email = ?, password = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(1, $full_name, PDO::PARAM_STR);
                $stmt->bindValue(2, $email, PDO::PARAM_STR);
                $stmt->bindValue(3, $hashed_password, PDO::PARAM_STR);
                $stmt->bindValue(4, $user_id, PDO::PARAM_INT);
            } else {
                $sql = "UPDATE users SET full_name = ?, email = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(1, $full_name, PDO::PARAM_STR);
                $stmt->bindValue(2, $email, PDO::PARAM_STR);
                $stmt->bindValue(3, $user_id, PDO::PARAM_INT);
            }

            if ($stmt->execute()) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Hồ sơ đã được cập nhật thành công! <a href="profile.php">Xem hồ sơ</a>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            } else {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Có lỗi xảy ra. Vui lòng thử lại.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            }
        }
    }
}
?>

<!-- Edit Profile Section -->
<section class="edit-profile-section">
    <div class="container">
        <div class="edit-profile-wrapper">
            <h2>Chỉnh sửa hồ sơ</h2>
            <p class="section-subtitle">Cập nhật thông tin cá nhân của bạn để chia sẻ với cộng đồng PHP Việt Nam!</p>
            
            <form method="POST" class="edit-profile-form">
                <div class="form-group">
                    <label for="username" class="form-label">Tên người dùng (không thể thay đổi):</label>
                    <input type="text" id="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="full_name" class="form-label">Họ và tên:</label>
                    <input type="text" id="full_name" name="full_name" class="form-control" value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Mật khẩu mới (để trống nếu không đổi):</label>
                    <input type="password" id="password" name="password" class="form-control">
                </div>
                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary btn-submit">Cập nhật</button>
                    <a href="profile.php" class="btn btn-cancel" onclick="return confirm('Bạn có chắc muốn hủy? Các thay đổi sẽ không được lưu.')">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>