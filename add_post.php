<?php
include 'includes/db_connect.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("INSERT INTO posts (title, content, user_id) VALUES (:title, :content, :user_id)");
    $stmt->execute(['title' => $title, 'content' => $content, 'user_id' => $user_id]);
    
    echo '<div class="alert alert-success">Bài viết đã được đăng thành công! <a href="posts.php">Xem bài viết</a></div>';
}
?>

<h2>Đăng bài mới</h2>
<form method="POST">
    <label for="title">Tiêu đề:</label>
    <input type="text" id="title" name="title" required>
    
    <label for="content">Nội dung:</label>
    <textarea id="content" name="content" rows="5" required></textarea>
    
    <button type="submit">Đăng bài</button>
</form>

<?php include 'includes/footer.php'; ?>