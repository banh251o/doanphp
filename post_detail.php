<?php 
include 'includes/db_connect.php';
include 'includes/header.php';

if (!isset($_GET['id'])) {
    echo '<div class="alert alert-error">Không tìm thấy bài viết!</div>';
    include 'includes/footer.php';
    exit;
}

$post_id = $_GET['id'];
$stmt = $conn->prepare("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id WHERE posts.id = :id");
$stmt->execute(['id' => $post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    echo '<div class="alert alert-error">Bài viết không tồn tại!</div>';
    include 'includes/footer.php';
    exit;
}
?>

<div class="post">
    <h2><?php echo htmlspecialchars($post['title']); ?></h2>
    <?php if ($post['image']): ?>
        <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Hình ảnh bài viết" class="post-image">
    <?php endif; ?>
    <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
    <p><small>Bởi <?php echo htmlspecialchars($post['username']); ?> vào <?php echo $post['created_at']; ?></small></p>
    
    <?php if (isset($_SESSION['user_id'])): ?>
        <?php if ($post['user_id'] === $_SESSION['user_id'] || $_SESSION['role'] === 'admin'): ?>
            <p>
                <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="action">Chỉnh sửa</a>
                <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="action">Xóa</a>
            </p>
        <?php endif; ?>
        <p><a href="add_comment.php?post_id=<?php echo $post['id']; ?>">Thêm bình luận</a></p>
    <?php endif; ?>
    
    <!-- Hiển thị bình luận -->
    <?php
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
    } else {
        echo '<p>Chưa có bình luận nào.</p>';
    }
    ?>
</div>

<p><a href="posts.php">Quay lại danh sách bài viết</a></p>

<?php include 'includes/footer.php'; ?>