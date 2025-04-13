<?php 
session_start();
include 'includes/db_connect.php';
include 'includes/header.php';

// Lấy danh sách bài viết nổi bật (hoặc mới nhất)
$stmt = $conn->prepare("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY created_at DESC LIMIT 5");
$stmt->execute();
$featured_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách bài viết gần đây cho sidebar
$stmt = $conn->prepare("SELECT posts.id, posts.title FROM posts ORDER BY created_at DESC LIMIT 5");
$stmt->execute();
$recent_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Main Section -->
<section class="main-section">
    <div class="container">
        <div class="row">
            <!-- Main Content (70%) -->
            <div class="col-lg-8 col-md-7 main-content">
                <h2>Bài viết nổi bật</h2>
                <?php if (empty($featured_posts)): ?>
                    <p>Chưa có bài viết nào. Hãy <a href="add_post.php">đăng bài ngay</a>!</p>
                <?php else: ?>
                    <div class="posts-list">
                        <?php foreach ($featured_posts as $post): ?>
                            <article class="post-item">
                                <?php if ($post['image']): ?>
                                    <div class="post-image">
                                        <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Hình ảnh bài viết">
                                    </div>
                                <?php endif; ?>
                                <div class="post-content">
                                    <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                                    <p><?php echo nl2br(htmlspecialchars(substr($post['content'], 0, 200))) . '...'; ?></p>
                                    <p class="post-meta">
                                        <span>Bởi <?php echo htmlspecialchars($post['username']); ?></span> vào 
                                        <?php echo $post['created_at']; ?>
                                    </p>
                                    <div class="post-actions">
                                        <a href="post_detail.php?id=<?php echo $post['id']; ?>" class="btn btn-primary btn-read-more">Đọc thêm</a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar (30%) -->
            <div class="col-lg-4 col-md-5 sidebar">
                <!-- Recent Posts -->
                <div class="sidebar-widget">
                    <h3>Bài viết gần đây</h3>
                    <ul class="recent-posts">
                        <?php foreach ($recent_posts as $post): ?>
                            <li>
                                <a href="post_detail.php?id=<?php echo $post['id']; ?>">
                                    <?php echo htmlspecialchars($post['title']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Community Info -->
                <div class="sidebar-widget">
                    <h3>Về cộng đồng</h3>
                    <p>Cộng đồng PHP Việt Nam là nơi kết nối các lập trình viên PHP, chia sẻ kiến thức, kinh nghiệm và hỗ trợ lẫn nhau.</p>
                    <a href="about.php" class="btn btn-primary btn-read-more">Tìm hiểu thêm</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>