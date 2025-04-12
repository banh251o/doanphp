<?php
include '../includes/db_connect.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$stmt = $conn->prepare("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY created_at DESC");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Quản lý bài viết</h2>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tiêu đề</th>
            <th>Hình ảnh</th>
            <th>Tác giả</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($posts as $post): ?>
            <tr>
                <td><?php echo $post['id']; ?></td>
                <td><?php echo htmlspecialchars($post['title']); ?></td>
                <td>
    <?php if ($post['image']): ?>
        <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Hình ảnh bài viết" class="post-image" style="max-width: 100px;">
    <?php else: ?>
        Không có
    <?php endif; ?>
</td>
                <td><?php echo htmlspecialchars($post['username']); ?></td>
                <td><?php echo $post['created_at']; ?></td>
                <td>
                    <a href="../edit_post.php?id=<?php echo $post['id']; ?>" class="action">Chỉnh sửa</a>
                    <a href="../delete_post.php?id=<?php echo $post['id']; ?>" class="action" onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')">Xóa</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>