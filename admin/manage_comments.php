<?php
include '../includes/db_connect.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$stmt = $conn->prepare("SELECT comments.*, users.username, posts.title FROM comments JOIN users ON comments.user_id = users.id JOIN posts ON comments.post_id = posts.id ORDER BY created_at DESC");
$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Quản lý bình luận</h2>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Bài viết</th>
            <th>Tác giả</th>
            <th>Nội dung</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($comments as $comment): ?>
            <tr>
                <td><?php echo $comment['id']; ?></td>
                <td><?php echo htmlspecialchars($comment['title']); ?></td>
                <td><?php echo htmlspecialchars($comment['username']); ?></td>
                <td><?php echo htmlspecialchars($comment['content']); ?></td>
                <td><?php echo $comment['created_at']; ?></td>
                <td>
                    <a href="manage_comments.php?action=delete&id=<?php echo $comment['id']; ?>" class="action" onclick="return confirm('Bạn có chắc chắn muốn xóa bình luận này?')">Xóa</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $comment_id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM comments WHERE id = :id");
    $stmt->execute(['id' => $comment_id]);
    
    header("Location: manage_comments.php");
    exit;
}
?>

<?php include '../includes/footer.php'; ?>