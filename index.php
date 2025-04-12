<?php 
include 'includes/db_connect.php';
include 'includes/header.php';
?>

<h2>Chào mừng đến với Cộng đồng PHP!</h2>
<p>Đây là nơi chia sẻ kiến thức, kinh nghiệm và những điều thú vị về PHP - ngôn ngữ lập trình mạnh mẽ cho web.</p>

<h3>Bài viết mới nhất</h3>
<?php
// Lấy 5 bài viết mới nhất
$stmt = $conn->prepare("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY created_at DESC LIMIT 5");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($posts) {
    foreach ($posts as $post) {
        echo '<div class="post">';
        echo '<h4>' . htmlspecialchars($post['title']) . '</h4>';
        echo '<p>' . nl2br(htmlspecialchars(substr($post['content'], 0, 200))) . '...</p>';
        echo '<p><small>Bởi ' . htmlspecialchars($post['username']) . ' vào ' . $post['created_at'] . '</small></p>';
        echo '<p><a href="posts.php">Đọc thêm</a></p>';
        echo '</div>';
    }
} else {
    echo '<p>Chưa có bài viết nào. <a href="add_post.php">Đăng bài ngay!</a></p>';
}
?>

<?php include 'includes/footer.php'; ?>