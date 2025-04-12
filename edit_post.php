<?php
include 'includes/db_connect.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$post_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = :id AND user_id = :user_id");
$stmt->execute(['id' => $post_id, 'user_id' => $_SESSION['user_id']]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    echo '<div class="alert alert-error">Bài viết không tồn tại hoặc bạn không có quyền chỉnh sửa!</div>';
    include 'includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    $stmt = $conn->prepare("UPDATE posts SET title = :title, content = :content WHERE id = :id");
    $stmt->execute(['title' => $title, 'content' => $content, 'id' => $post_id]);
    
    echo '<div class="alert alert-success">Cập nhật bài viết thành công! <a href="posts.php">Xem bài viết</a></div>';
}
?>

<h2>Chỉnh sửa bài viết</h2>
<form method="POST">
    <label for="title">Tiêu đề:</label>
    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
    
    <label for="content">Nội dung:</label>
    <textarea id="content" name="content" rows="5" required><?php echo htmlspecialchars($post['content']); ?></textarea>
    
    <button type="submit">Cập nhật</button>
</form>

<?php include 'includes/footer.php'; ?>