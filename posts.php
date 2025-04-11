<?php 
include 'includes/header.php'; 
include 'includes/db_connect.php';
?>
<h2>Bài viết từ cộng đồng</h2>
<?php
$stmt = $conn->prepare("SELECT * FROM posts ORDER BY created_at DESC");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($posts as $post) {
    echo '<div class="post">';
    echo '<h3>' . htmlspecialchars($post['title']) . '</h3>';
    echo '<p>' . nl2br(htmlspecialchars($post['content'])) . '</p>';
    echo '<p><small>Bởi ' . htmlspecialchars($post['author']) . ' vào ' . $post['created_at'] . '</small></p>';
    echo '</div>';
}
?>
<?php include 'includes/footer.php'; ?>