<?php 
include 'includes/db_connect.php';
include 'includes/header.php';
?>

<h2>Bài viết từ cộng đồng</h2>
<?php
$stmt = $conn->prepare("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY created_at DESC");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($posts as $post) {
    echo '<div class="post">';
    echo '<h3>' . htmlspecialchars($post['title']) . '</h3>';
    if ($post['image']) {
        echo '<img src="' . htmlspecialchars($post['image']) . '" alt="Hình ảnh bài viết" class="post-image">';
    }
    echo '<p>' . nl2br(htmlspecialchars(substr($post['content'], 0, 200))) . '...</p>';
    echo '<p><small>Bởi ' . htmlspecialchars($post['username']) . ' vào ' . $post['created_at'] . '</small></p>';
    echo '<p><a href="post_detail.php?id=' . $post['id'] . '">Đọc thêm</a></p>';
    
    if (isset($_SESSION['user_id'])) {
        if ($post['user_id'] === $_SESSION['user_id'] || $_SESSION['role'] === 'admin') {
            echo '<p><a href="edit_post.php?id=' . $post['id'] . '" class="action">Chỉnh sửa</a>';
            echo '<a href="delete_post.php?id=' . $post['id'] . '" class="action">Xóa</a></p>';
        }
    }
    echo '</div>';
}
?>

<?php include 'includes/footer.php'; ?>