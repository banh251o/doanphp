<?php
include 'includes/db_connect.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$post_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = :id");
$stmt->execute(['id' => $post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    echo '<div class="alert alert-error">Bài viết không tồn tại!</div>';
    include 'includes/footer.php';
    exit;
}

// Kiểm tra quyền: User chỉ xóa bài của mình, Admin xóa được tất cả
if ($_SESSION['role'] !== 'admin' && $post['user_id'] !== $_SESSION['user_id']) {
    echo '<div class="alert alert-error">Bạn không có quyền xóa bài viết này!</div>';
    include 'includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("DELETE FROM comments WHERE post_id = :post_id");
    $stmt->execute(['post_id' => $post_id]);
    
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = :id");
    $stmt->execute(['id' => $post_id]);
    
    echo '<div class="alert alert-success">Xóa bài viết thành công! <a href="posts.php">Quay lại</a></div>';
    include 'includes/footer.php';
    exit;
}
?>

<h2>Xóa bài viết</h2>
<p>Bạn có chắc chắn muốn xóa bài viết "<strong><?php echo htmlspecialchars($post['title']); ?></strong>" không? Hành động này không thể hoàn tác.</p>
<form method="POST">
    <button type="submit">Xóa</button>
    <a href="posts.php">Hủy</a>
</form>

<?php include 'includes/footer.php'; ?>