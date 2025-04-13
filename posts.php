<?php 
session_start();
include 'includes/db_connect.php';
include 'includes/header.php';

// Phân trang
$per_page = 6; // Số bài viết mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $per_page;

// Tìm kiếm
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_condition = $search ? "WHERE title LIKE ? OR content LIKE ?" : "";
$search_param = $search ? "%$search%" : "";

// Lấy tổng số bài viết
$stmt = $conn->prepare("SELECT COUNT(*) FROM posts $search_condition");
if ($search) {
    $stmt->bindValue(1, $search_param, PDO::PARAM_STR);
    $stmt->bindValue(2, $search_param, PDO::PARAM_STR);
}
$stmt->execute();
$total_posts = $stmt->fetchColumn();
$total_pages = ceil($total_posts / $per_page);

// Lấy danh sách bài viết
$sql = "SELECT p.*, u.username FROM posts p JOIN users u ON p.user_id = u.id $search_condition ORDER BY p.created_at DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
if ($search) {
    $stmt->bindValue(1, $search_param, PDO::PARAM_STR);
    $stmt->bindValue(2, $search_param, PDO::PARAM_STR);
    $stmt->bindValue(3, $per_page, PDO::PARAM_INT);
    $stmt->bindValue(4, $offset, PDO::PARAM_INT);
} else {
    $stmt->bindValue(1, $per_page, PDO::PARAM_INT);
    $stmt->bindValue(2, $offset, PDO::PARAM_INT);
}
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Posts Section -->
<section class="posts-section">
    <div class="container">
        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_GET['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <h2>Danh sách bài viết</h2>
        <p class="section-subtitle">Khám phá các bài viết từ cộng đồng PHP Việt Nam!</p>

        <!-- Search Form -->
        <form method="GET" class="search-form">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm bài viết..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary btn-search">Tìm kiếm</button>
            </div>
        </form>

        <!-- Posts List -->
        <div class="row">
            <?php if (empty($posts)): ?>
                <div class="col-12">
                    <p class="no-posts">Không tìm thấy bài viết nào.</p>
                </div>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="post-card">
                            <?php if ($post['image']): ?>
                                <div class="post-image">
                                    <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Hình ảnh bài viết">
                                </div>
                            <?php endif; ?>
                            <div class="post-content">
                                <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                                <p class="post-excerpt">
                                    <?php 
                                    $content = htmlspecialchars($post['content']);
                                    echo strlen($content) > 100 ? substr($content, 0, 100) . '...' : $content;
                                    ?>
                                </p>
                                <p class="post-meta">
                                    Đăng bởi <strong><?php echo htmlspecialchars($post['username']); ?></strong> vào <?php echo date('d/m/Y', strtotime($post['created_at'])); ?>
                                </p>
                                <div class="post-actions">
                                    <a href="post_detail.php?id=<?php echo $post['id']; ?>" class="btn btn-primary btn-read-more">Đọc thêm</a>
                                    <a href="post_detail.php?id=<?php echo $post['id']; ?>#comments" class="btn btn-secondary btn-comment">Bình luận</a>
                                    <?php if (isset($_SESSION['user_id']) && ($post['user_id'] == $_SESSION['user_id'] || (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'))): ?>
                                        <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-warning btn-edit">Chỉnh sửa</a>
                                        <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="btn btn-danger btn-delete" onclick="return confirm('Bạn có chắc muốn xóa bài viết này?')">Xóa</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="posts.php?page=<?php echo $page - 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" class="btn btn-primary">Trang trước</a>
            <?php endif; ?>
            <span>Trang <?php echo $page; ?> / <?php echo $total_pages; ?></span>
            <?php if ($page < $total_pages): ?>
                <a href="posts.php?page=<?php echo $page + 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" class="btn btn-primary">Trang sau</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>