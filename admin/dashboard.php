<?php
include '../includes/db_connect.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Lấy số liệu thống kê
$stmt = $conn->query("SELECT COUNT(*) FROM users");
$total_users = $stmt->fetchColumn();

$stmt = $conn->query("SELECT COUNT(*) FROM posts");
$total_posts = $stmt->fetchColumn();

$stmt = $conn->query("SELECT COUNT(*) FROM comments");
$total_comments = $stmt->fetchColumn();
?>

<h2>Tổng quan</h2>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Tổng số người dùng</h5>
                <p class="card-text"><?php echo $total_users; ?></p>
                <a href="manage_users.php" class="btn btn-primary">Xem chi tiết</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Tổng số bài viết</h5>
                <p class="card-text"><?php echo $total_posts; ?></p>
                <a href="manage_posts.php" class="btn btn-primary">Xem chi tiết</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Tổng số bình luận</h5>
                <p class="card-text"><?php echo $total_comments; ?></p>
                <a href="manage_comments.php" class="btn btn-primary">Xem chi tiết</a>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>