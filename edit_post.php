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

// Kiểm tra quyền chỉnh sửa
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bindValue(1, $post_id, PDO::PARAM_INT);
$stmt->execute();
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    echo '<div class="alert alert-danger">Bài viết không tồn tại.</div>';
    include 'includes/footer.php';
    exit();
}

if ($post['user_id'] != $_SESSION['user_id'] && (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin')) {
    echo '<div class="alert alert-danger">Bạn không có quyền chỉnh sửa bài viết này.</div>';
    include 'includes/footer.php';
    exit();
}

// Xử lý cập nhật bài viết
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $image = $post['image'];
    $remove_image = isset($_POST['remove_image']);

    // Kiểm tra tiêu đề và nội dung
    if (empty($title) || empty($content)) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Vui lòng nhập tiêu đề và nội dung bài viết.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    } else {
        // Xử lý xóa hình ảnh
        if ($remove_image && $image && file_exists($image)) {
            unlink($image);
            $image = '';
        }

        // Xử lý upload hình ảnh mới
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $max_file_size = 2 * 1024 * 1024; // 2MB
            if ($_FILES['image']['size'] > $max_file_size) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Hình ảnh quá lớn (tối đa 2MB). Vui lòng chọn file nhỏ hơn.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            } else {
                $target_dir = "uploads/posts/";
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                $target_file = $target_dir . basename($_FILES["image"]["name"]);
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    if ($image && file_exists($image)) {
                        unlink($image);
                    }
                    $image = $target_file;
                } else {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            Lỗi khi upload hình ảnh. Vui lòng thử lại.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>';
                }
            }
        }

        // Cập nhật bài viết
        $sql = "UPDATE posts SET title = ?, content = ?, image = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $title, PDO::PARAM_STR);
        $stmt->bindValue(2, $content, PDO::PARAM_STR);
        $stmt->bindValue(3, $image, PDO::PARAM_STR);
        $stmt->bindValue(4, $post_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: posts.php?message=Bài viết đã được cập nhật thành công!");
            exit();
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Có lỗi xảy ra khi cập nhật bài viết. Vui lòng thử lại.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        }
    }
}
?>

<!-- Edit Post Section -->
<section class="edit-post-section">
    <div class="container">
        <div class="edit-post-wrapper">
            <h2>Chỉnh sửa bài viết</h2>
            <p class="section-subtitle">Cập nhật thông tin bài viết của bạn.</p>

            <form method="POST" enctype="multipart/form-data" class="edit-post-form">
                <div class="form-group">
                    <label for="title" class="form-label">Tiêu đề:</label>
                    <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($post['title']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="content" class="form-label">Nội dung:</label>
                    <textarea id="content" name="content" class="form-control" rows="10" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="image" class="form-label">Hình ảnh:</label>
                    <?php if ($post['image']): ?>
                        <div class="current-image">
                            <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Hình ảnh bài viết" class="preview-img" id="image-preview">
                        </div>
                        <div class="form-check">
                            <input type="checkbox" id="remove_image" name="remove_image" class="form-check-input">
                            <label for="remove_image" class="form-check-label">Xóa hình ảnh hiện tại</label>
                        </div>
                    <?php endif; ?>
                    <div class="custom-file-upload">
                        <input type="file" id="image" name="image" class="form-control-file" accept="image/*">
                        <span class="file-name"><?php echo $post['image'] ? 'Thay đổi hình ảnh...' : 'Chưa chọn file...'; ?></span>
                    </div>
                </div>
                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary btn-submit">Cập nhật bài viết</button>
                    <a href="post_detail.php?id=<?php echo $post['id']; ?>" class="btn btn-cancel" onclick="return confirm('Bạn có chắc muốn hủy? Các thay đổi sẽ không được lưu.')">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
// Preview hình ảnh
document.getElementById('image').addEventListener('change', function(e) {
    const fileName = e.target.files.length > 0 ? e.target.files[0].name : 'Chưa chọn file...';
    document.querySelector('.file-name').textContent = fileName;

    const previewImg = document.getElementById('image-preview');
    if (e.target.files.length > 0 && previewImg) {
        const file = e.target.files[0];
        previewImg.src = URL.createObjectURL(file);
    }
});
</script>

<?php include 'includes/footer.php'; ?>