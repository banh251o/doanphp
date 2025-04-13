<?php 
session_start();
include 'includes/db_connect.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Kiểm tra bài viết tồn tại
if (!isset($_GET['id'])) {
    header("Location: posts.php");
    exit();
}

$post_id = (int)$_GET['id'];

// Kiểm tra quyền xóa
$stmt = $conn->prepare("SELECT user_id FROM posts WHERE id = ?");
$stmt->bindValue(1, $post_id, PDO::PARAM_INT);
$stmt->execute();
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    header("Location: posts.php");
    exit();
}

if ($post['user_id'] != $_SESSION['user_id'] && (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin')) {
    echo '<div class="alert alert-danger">Bạn không có quyền xóa bài viết này.</div>';
    exit();
}

// Xóa bài viết
$stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
$stmt->bindValue(1, $post_id, PDO::PARAM_INT);

if ($stmt->execute()) {
    // Xóa bình luận liên quan (nếu có)
    $stmt = $conn->prepare("DELETE FROM comments WHERE post_id = ?");
    $stmt->bindValue(1, $post_id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: posts.php?message=Bài viết đã được xóa thành công!");
    exit();
} else {
    echo '<div class="alert alert-danger">Có lỗi xảy ra khi xóa bài viết. Vui lòng thử lại.</div>';
}
?>