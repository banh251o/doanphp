<?php
include 'includes/db_connect.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $user['password'];

    try {
        $stmt = $conn->prepare("UPDATE users SET username = :username, password = :password WHERE id = :id");
        $stmt->execute(['username' => $username, 'password' => $password, 'id' => $user_id]);
        $_SESSION['username'] = $username;
        echo '<div class="alert alert-success">Cập nhật hồ sơ thành công!</div>';
    } catch (PDOException $e) {
        echo '<div class="alert alert-error">Lỗi: ' . $e->getMessage() . '</div>';
    }
}
?>

<h2>Chỉnh sửa hồ sơ</h2>
<form method="POST">
    <label for="username">Tên người dùng:</label>
    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
    
    <label for="password">Mật khẩu mới (để trống nếu không đổi):</label>
    <input type="password" id="password" name="password">
    
    <button type="submit">Cập nhật</button>
</form>

<?php include 'includes/footer.php'; ?>