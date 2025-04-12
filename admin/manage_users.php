<?php
include '../includes/db_connect.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users ORDER BY created_at DESC");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Quản lý người dùng</h2>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên người dùng</th>
            <th>Email</th>
            <th>Vai trò</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo $user['role']; ?></td>
                <td><?php echo $user['created_at']; ?></td>
                <td>
                    <?php if ($user['role'] !== 'admin'): ?>
                        <a href="manage_users.php?action=delete&id=<?php echo $user['id']; ?>" class="action" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">Xóa</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM comments WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    
    $stmt = $conn->prepare("DELETE FROM posts WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    
    $stmt = $conn->prepare("DELETE FROM users WHERE id = :id AND role != 'admin'");
    $stmt->execute(['id' => $user_id]);
    
    header("Location: manage_users.php");
    exit;
}
?>

<?php include '../includes/footer.php'; ?>