<?php 
session_start();
include 'includes/db_connect.php';
include 'includes/header.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Kiểm tra bài viết tồn tại
if (!isset($_GET['id'])) {
    header("Location: posts.php");
    exit();
}

$post_id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bindValue(1, $post_id, PDO::PARAM_INT);
$stmt->execute();
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    echo '<div class="alert alert-danger">Bài viết không tồn tại.</div>';
    include 'includes/footer.php';
    exit();
}

// Kiểm tra quyền chỉnh sửa
if ($post['user_id'] !== $_SESSION['user_id'] && (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')) {
    echo '<div class="alert alert-danger">Bạn không có quyền chỉnh sửa bài viết này.</div>';
    include 'includes/footer.php';
    exit();
}

// Xử lý cập nhật bài viết
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $post['image']; // Giữ hình ảnh cũ nếu không upload hình mới

    // Xử lý upload hình ảnh mới
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Xóa hình ảnh cũ nếu có
            if ($image && file_exists($image)) {
                unlink($image);
            }
            $image = $target_file;
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Hình ảnh đã được upload thành công!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Lỗi khi upload hình ảnh. Vui lòng thử lại.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        }
    }

    // Cập nhật bài viết vào cơ sở dữ liệu
    $sql = "UPDATE posts SET title = ?, content = ?, image = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(1, $title, PDO::PARAM_STR);
    $stmt->bindValue(2, $content, PDO::PARAM_STR);
    $stmt->bindValue(3, $image, PDO::PARAM_STR);
    $stmt->bindValue(4, $post_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Bài viết đã được cập nhật thành công! <a href="post_detail.php?id=' . $post_id . '">Xem bài viết</a>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Có lỗi xảy ra khi cập nhật bài viết. Vui lòng thử lại.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    }
}
?>

<!-- Edit Post Section -->
<section class="edit-post-section">
    <div class="container">
        <div class="edit-post-wrapper">
            <h2>Chỉnh sửa bài viết</h2>
            <p class="section-subtitle">Cập nhật bài viết của bạn để chia sẻ với cộng đồng PHP Việt Nam!</p>
            
            <form method="POST" enctype="multipart/form-data" class="edit-post-form">
                <div class="form-group">
                    <label for="title" class="form-label">Tiêu đề:</label>
                    <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($post['title']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="content" class="form-label">Nội dung:</label>
                    <textarea id="content" name="content" class="form-control" rows="6" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="image" class="form-label">Hình ảnh (tùy chọn):</label>
                    <?php if ($post['image']): ?>
                        <div class="current-image">
                            <p>Hình ảnh hiện tại:</p>
                            <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Hình ảnh hiện tại" class="preview-img">
                        </div>
                    <?php endif; ?>
                    <div class="custom-file-upload">
                        <input type="file" id="image" name="image" class="form-control-file" accept="image/*">
                        <span class="file-name">Chưa chọn file...</span>
                    </div>
                    <div class="image-preview" id="image-preview">
                        <img src="" alt="Image Preview" id="preview-img" style="display: none;">
                    </div>
                </div>
                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary btn-submit">Lưu thay đổi</button>
                    <a href="post_detail.php?id=<?php echo $post_id; ?>" class="btn btn-cancel" onclick="return confirm('Bạn có chắc muốn hủy? Các thay đổi sẽ không được lưu.')">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
// Hiển thị tên file và preview hình ảnh
document.getElementById('image').addEventListener('change', function(e) {
    const fileName = e.target.files.length > 0 ? e.target.files[0].name : 'Chưa chọn file...';
    document.querySelector('.file-name').textContent = fileName;

    const previewImg = document.getElementById('preview-img');
    if (e.target.files.length > 0) {
        const file = e.target.files[0];
        previewImg.src = URL.createObjectURL(file);
        previewImg.style.display = 'block';
    } else {
        previewImg.style.display = 'none';
    }
});
</script>

<?php include 'includes/footer.php'; ?>