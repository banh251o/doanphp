<?php
include 'includes/db_connect.php';
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: index.php");
        exit;
    } else {
        echo '<div class="alert alert-error">Tên người dùng hoặc mật khẩu không đúng!</div>';
    }
}
?>

<h2>Đăng nhập</h2>
<form method="POST">
    <label for="username">Tên người dùng:</label>
    <input type="text" id="username" name="username" required>
    
    <label for="password">Mật khẩu:</label>
    <input type="password" id="password" name="password" required>
    
    <button type="submit">Đăng nhập</button>
</form>

<?php include 'includes/footer.php'; ?>