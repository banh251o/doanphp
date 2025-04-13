<?php 
session_start(); // Bắt đầu session để sử dụng $_SESSION
include 'includes/db_connect.php';
include 'includes/header.php';
?>

<!-- Posts Section -->
<section class="posts-section">
    <div class="container">
        <h2>Bài viết từ cộng đồng</h2>
        <?php
        $stmt = $conn->prepare("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY created_at DESC");
        $stmt->execute();
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($posts)) {
            echo '<p>Chưa có bài viết nào. Hãy <a href="add_post.php">đăng bài ngay</a>!</p>';
        } else {
            echo '<div class="posts-grid">';
            foreach ($posts as $post) {
                echo '<article class="post-item">';
                if ($post['image']) {
                    echo '<div class="post-image">';
                    echo '<img src="' . htmlspecialchars($post['image']) . '" alt="Hình ảnh bài viết">';
                    echo '</div>';
                }
                echo '<div class="post-content">';
                echo '<h3>' . htmlspecialchars($post['title']) . '</h3>';
                echo '<p>' . nl2br(htmlspecialchars(substr($post['content'], 0, 200))) . '...</p>';
                echo '<p class="post-meta"><small>Bởi ' . htmlspecialchars($post['username']) . ' vào ' . $post['created_at'] . '</small></p>';
                echo '<div class="post-actions">';
                echo '<a href="post_detail.php?id=' . $post['id'] . '" class="btn btn-primary btn-read-more">Đọc thêm</a>';
                
                if (isset($_SESSION['user_id'])) {
                    if ($post['user_id'] === $_SESSION['user_id'] || (isset($_SESSION['role']) && $_SESSION['role'] === 'admin')) {
                        echo '<a href="edit_post.php?id=' . $post['id'] . '" class="btn btn-action btn-edit">Chỉnh sửa</a>';
                        echo '<a href="delete_post.php?id=' . $post['id'] . '" class="btn btn-action btn-delete" onclick="return confirm(\'Bạn có chắc muốn xóa bài viết này?\')">Xóa</a>';
                    }
                }
                echo '</div>'; // Đóng post-actions
                echo '</div>'; // Đóng post-content
                echo '</article>'; // Đóng post-item
            }
            echo '</div>'; // Đóng posts-grid
        }
        ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>