<?php 
session_start();
include 'includes/db_connect.php';
include 'includes/header.php';

// Kiểm tra bài viết tồn tại
if (!isset($_GET['id'])) {
    header("Location: posts.php");
    exit();
}

$post_id = (int)$_GET['id'];

// Tăng lượt xem
$stmt = $conn->prepare("UPDATE posts SET views = views + 1 WHERE id = ?");
$stmt->bindValue(1, $post_id, PDO::PARAM_INT);
$stmt->execute();

$stmt = $conn->prepare("SELECT p.*, u.username FROM posts p JOIN users u ON p.user_id = u.id WHERE p.id = ?");
$stmt->bindValue(1, $post_id, PDO::PARAM_INT);
$stmt->execute();
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    echo '<div class="alert alert-danger">Bài viết không tồn tại.</div>';
    include 'includes/footer.php';
    exit();
}

// Xử lý thêm bình luận
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['content'])) {
    if (!isset($_SESSION['user_id'])) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Vui lòng <a href="login.php">đăng nhập</a> để thêm bình luận.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    } else {
        $user_id = $_SESSION['user_id'];
        $content = trim($_POST['content']);

        if (empty($content)) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Vui lòng nhập nội dung bình luận.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        } else {
            $sql = "INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $post_id, PDO::PARAM_INT);
            $stmt->bindValue(2, $user_id, PDO::PARAM_INT);
            $stmt->bindValue(3, $content, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Bình luận đã được thêm thành công!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            } else {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Có lỗi xảy ra khi thêm bình luận. Vui lòng thử lại.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            }
        }
    }
}

// Lấy danh sách bình luận
$stmt = $conn->prepare("SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.post_id = ? ORDER BY c.created_at DESC");
$stmt->bindValue(1, $post_id, PDO::PARAM_INT);
$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Post Detail Section -->
<section class="post-detail-section">
    <div class="container">
        <div class="post-detail-wrapper">
            <h2><?php echo htmlspecialchars($post['title']); ?></h2>
            <p class="post-meta">
                Đăng bởi <strong><?php echo htmlspecialchars($post['username']); ?></strong> vào <?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?> | Lượt xem: <?php echo $post['views']; ?>
            </p>
            <?php if ($post['image']): ?>
                <div class="post-image">
                    <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Hình ảnh bài viết">
                </div>
            <?php endif; ?>
            <div class="post-content">
                <?php echo nl2br(htmlspecialchars($post['content'])); ?>
            </div>

            <!-- Post Actions -->
            <div class="post-actions">
                <?php if (isset($_SESSION['user_id']) && ($post['user_id'] == $_SESSION['user_id'] || (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'))): ?>
                    <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-warning btn-edit">Chỉnh sửa bài viết</a>
                    <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="btn btn-danger btn-delete" onclick="return confirm('Bạn có chắc muốn xóa bài viết này?')">Xóa bài viết</a>
                <?php endif; ?>
            </div>

            <!-- Social Share Buttons -->
            <div class="social-share">
                <h4>Chia sẻ bài viết:</h4>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://localhost/doanphp/post_detail.php?id=' . $post_id); ?>" target="_blank" class="btn btn-social btn-facebook">
                    <i class="fab fa-facebook-f"></i> Facebook
                </a>
                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('http://localhost/doanphp/post_detail.php?id=' . $post_id); ?>&text=<?php echo urlencode($post['title']); ?>" target="_blank" class="btn btn-social btn-twitter">
                    <i class="fab fa-twitter"></i> Twitter
                </a>
                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode('http://localhost/doanphp/post_detail.php?id=' . $post_id); ?>&title=<?php echo urlencode($post['title']); ?>" target="_blank" class="btn btn-social btn-linkedin">
                    <i class="fab fa-linkedin-in"></i> LinkedIn
                </a>
            </div>

            <!-- Comments Section -->
            <div class="comments-section" id="comments">
                <h3>Bình luận (<?php echo count($comments); ?>)</h3>

                <!-- Comment Form -->
                <div class="comment-form-wrapper">
                    <form method="POST" class="comment-form">
                        <div class="form-group">
                            <label for="content" class="form-label">Thêm bình luận của bạn:</label>
                            <textarea id="content" name="content" class="form-control" rows="3" placeholder="Viết bình luận..." required></textarea>
                        </div>
                        <div class="form-buttons">
                            <button type="submit" class="btn btn-primary btn-submit">Gửi bình luận</button>
                            <button type="reset" class="btn btn-cancel" onclick="return confirm('Bạn có chắc muốn hủy nội dung đã nhập?')">Hủy</button>
                        </div>
                    </form>
                </div>

                <!-- Comments List -->
                <div class="comments-list">
                    <?php if (empty($comments)): ?>
                        <p>Chưa có bình luận nào. Hãy là người đầu tiên bình luận!</p>
                    <?php else: ?>
                        <?php foreach ($comments as $comment): ?>
                            <div class="comment-item">
                                <div class="comment-content">
                                    <div class="comment-header">
                                        <h4><?php echo htmlspecialchars($comment['username']); ?></h4>
                                        <p class="comment-meta"><?php echo date('d/m/Y H:i', strtotime($comment['created_at'])); ?></p>
                                    </div>
                                    <p><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>