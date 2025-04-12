<?php
include 'includes/db_connect.php';
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->execute(['username' => $username, 'email' => $email, 'password' => $password]);
        echo '<div class="alert alert-success">Đăng ký thành công! <a href="login.php">Đăng nhập ngay</a></div>';
    } catch (PDOException $e) {
        echo '<div class="alert alert-error">Lỗi: ' . $e->getMessage() . '</div>';
    }
}
?>

<h2>Đăng ký</h2>
<form method="POST">
    <label for="username">Tên người dùng:</label>
    <input type="text" id="username" name="username" required>
    
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    
    <label for="password">Mật khẩu:</label>
    <input type="password" id="password" name="password" required>
    
    <button type="submit">Đăng ký</button>
</form>

<?php include 'includes/footer.php'; ?>