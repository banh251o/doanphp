<?php
include 'includes/db_connect.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$post_id = $_GET['post_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (:post_id, :user_id, :content)");
    $stmt->execute(['post_id' => $post_id, 'user_id' => $user_id, 'content' => $content]);
    
    header("Location: posts.php");
    exit;
}
?>

<h2>Thêm bình luận</h2>
<form method="POST">
    <label for="content">Nội dung bình luận:</label>
    <textarea id="content" name="content" rows="3" required></textarea>
    
    <button type="submit">Gửi</button>
</form>

<?php include 'includes/footer.php'; ?>