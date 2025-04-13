<?php 
session_start();
include 'includes/db_connect.php';
include 'includes/header.php';

// Tăng lượt xem khi truy cập bài viết (sẽ dùng trong post_detail.php, nhưng cần cột views)
if (isset($_GET['increase_view'])) {
    $post_id = (int)$_GET['increase_view'];
    $stmt = $conn->prepare("UPDATE posts SET views = views + 1 WHERE id = ?");
    $stmt->bindValue(1, $post_id, PDO::PARAM_INT);
    $stmt->execute();
}

// Lấy bài viết nổi bật (dựa trên lượt xem)
$stmt = $conn->prepare("SELECT p.*, u.username FROM posts p JOIN users u ON p.user_id = u.id ORDER BY p.views DESC LIMIT 3");
$stmt->execute();
$featured_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy bài viết gần đây
$stmt = $conn->prepare("SELECT p.*, u.username FROM posts p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC LIMIT 6");
$stmt->execute();
$recent_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Hero Section (Banner) -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <h1>Chào mừng đến với Cộng đồng PHP Việt Nam</h1>
            <p class="hero-subtitle">Nơi chia sẻ kiến thức, kinh nghiệm và đam mê về lập trình PHP!</p>
            <div class="hero-buttons">
                <a href="posts.php" class="btn btn-primary btn-hero">Khám phá bài viết</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="add_post.php" class="btn btn-secondary btn-hero">Đăng bài viết</a>
                <?php else: ?>
                    <a href="register.php" class="btn btn-secondary btn-hero">Tham gia ngay</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Featured Posts Section -->
<section class="featured-posts-section">
    <div class="container">
        <h2>Bài viết nổi bật</h2>
        <p class="section-subtitle">Những bài viết được yêu thích nhất trong cộng đồng.</p>
        <div class="row">
            <?php if (empty($featured_posts)): ?>
                <div class="col-12">
                    <p class="no-posts">Chưa có bài viết nổi bật nào.</p>
                </div>
            <?php else: ?>
                <?php foreach ($featured_posts as $post): ?>
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
                                    Đăng bởi <strong><?php echo htmlspecialchars($post['username']); ?></strong> | Lượt xem: <?php echo $post['views']; ?>
                                </p>
                                <div class="post-actions">
                                    <a href="post_detail.php?id=<?php echo $post['id']; ?>&increase_view=<?php echo $post['id']; ?>" class="btn btn-primary btn-read-more">Đọc thêm</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Recent Posts Section -->
<section class="recent-posts-section">
    <div class="container">
        <h2>Bài viết gần đây</h2>
        <p class="section-subtitle">Những bài viết mới nhất từ cộng đồng PHP Việt Nam.</p>
        <div class="row">
            <?php if (empty($recent_posts)): ?>
                <div class="col-12">
                    <p class="no-posts">Chưa có bài viết nào.</p>
                </div>
            <?php else: ?>
                <?php foreach ($recent_posts as $post): ?>
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
                                    <a href="post_detail.php?id=<?php echo $post['id']; ?>&increase_view=<?php echo $post['id']; ?>" class="btn btn-primary btn-read-more">Đọc thêm</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="view-all-posts">
            <a href="posts.php" class="btn btn-primary btn-view-all">Xem tất cả bài viết</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>