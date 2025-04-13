<?php 
session_start();
include 'includes/db_connect.php';
include 'includes/header.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Xử lý đăng bài
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $image = '';

    // Kiểm tra tiêu đề và nội dung
    if (empty($title) || empty($content)) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Vui lòng nhập tiêu đề và nội dung bài viết.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    } else {
        // Xử lý upload hình ảnh
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed_types = ['image/jpeg', 'image/png'];
            if (!in_array($_FILES['image']['type'], $allowed_types)) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Chỉ hỗ trợ định dạng JPG hoặc PNG.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            } else {
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
                    $target_file = $target_dir . time() . '_' . basename($_FILES["image"]["name"]); // Thêm timestamp để tránh trùng tên
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        $image = $target_file;
                    } else {
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                Lỗi khi upload hình ảnh. Vui lòng thử lại.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                              </div>';
                    }
                }
            }
        }

        // Lưu bài viết vào database
        if (!isset($error)) {
            $sql = "INSERT INTO posts (user_id, title, content, image, created_at) VALUES (?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
            $stmt->bindValue(2, $title, PDO::PARAM_STR);
            $stmt->bindValue(3, $content, PDO::PARAM_STR);
            $stmt->bindValue(4, $image, PDO::PARAM_STR);

            if ($stmt->execute()) {
                header("Location: posts.php?message=Bài viết đã được đăng thành công!");
                exit();
            } else {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Có lỗi xảy ra khi đăng bài viết. Vui lòng thử lại.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            }
        }
    }
}
?>

<!-- Add Post Section -->
<section class="add-post-section">
    <div class="container">
        <div class="add-post-wrapper">
            <h2>Đăng bài viết mới</h2>
            <p class="section-subtitle">Chia sẻ kiến thức và kinh nghiệm của bạn với cộng đồng!</p>

            <form method="POST" enctype="multipart/form-data" class="add-post-form">
                <div class="form-group">
                    <label for="title" class="form-label">Tiêu đề:</label>
                    <input type="text" id="title" name="title" class="form-control" value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="content" class="form-label">Nội dung:</label>
                    <textarea id="content" name="content" class="form-control" rows="10" required><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="image" class="form-label">Hình ảnh:</label>
                    <div class="image-preview-container" id="image-preview-container" style="display: none;">
                        <img src="" alt="Hình ảnh xem trước" class="preview-img" id="image-preview">
                    </div>
                    <div class="custom-file-upload">
                        <label for="image" class="btn btn-primary btn-choose-file">Chọn hình ảnh</label>
                        <input type="file" id="image" name="image" accept="image/*" style="display: none;">
                        <span class="file-name">Chưa chọn file...</span>
                    </div>
                </div>
                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary btn-submit">Đăng bài viết</button>
                    <a href="posts.php" class="btn btn-cancel" onclick="return confirm('Bạn có chắc muốn hủy? Nội dung đã nhập sẽ không được lưu.')">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
// Xử lý khi chọn file hình ảnh
document.getElementById('image').addEventListener('change', function(e) {
    const allowedTypes = ['image/jpeg', 'image/png'];
    const file = e.target.files[0];
    const fileName = e.target.files.length > 0 ? file.name : 'Chưa chọn file...';
    document.querySelector('.file-name').textContent = fileName;

    if (file && !allowedTypes.includes(file.type)) {
        alert('Chỉ hỗ trợ định dạng JPG hoặc PNG.');
        e.target.value = ''; // Reset input
        document.querySelector('.file-name').textContent = 'Chưa chọn file...';
        document.getElementById('image-preview-container').style.display = 'none';
        return;
    }

    const previewContainer = document.getElementById('image-preview-container');
    const previewImg = document.getElementById('image-preview');
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            previewImg.src = event.target.result;
            previewContainer.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        previewContainer.style.display = 'none';
    }
});
</script>

<?php include 'includes/footer.php'; ?>