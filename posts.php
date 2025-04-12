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
    echo '<p>' . nl2br(htmlspecialchars($post['content'])) . '</p>';
    echo '<p><small>Bởi ' . htmlspecialchars($post['username']) . ' vào ' . $post['created_at'] . '</small></p>';
    
    if (isset($_SESSION['user_id'])) {
        if ($post['user_id'] === $_SESSION['user_id'] || $_SESSION['role'] === 'admin') {
            echo '<p><a href="edit_post.php?id=' . $post['id'] . '" class="action">Chỉnh sửa</a>';
            echo '<a href="delete_post.php?id=' . $post['id'] . '" class="action">Xóa</a></p>';
        }
        echo '<p><a href="add_comment.php?post_id=' . $post['id'] . '">Thêm bình luận</a></p>';
    }
    
    // Hiển thị bình luận
    $stmt = $conn->prepare("SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE post_id = :post_id ORDER BY created_at DESC");
    $stmt->execute(['post_id' => $post['id']]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($comments) {
        echo '<h4>Bình luận:</h4>';
        foreach ($comments as $comment) {
            echo '<div class="comment">';
            echo '<p>' . nl2br(htmlspecialchars($comment['content'])) . '</p>';
            echo '<p><small>Bởi ' . htmlspecialchars($comment['username']) . ' vào ' . $comment['created_at'] . '</small></p>';
            echo '</div>';
        }
    }
    echo '</div>';
}
?>

<?php include 'includes/footer.php'; ?>