<?php 
session_start();
include 'includes/db_connect.php';
include 'includes/header.php';

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Xử lý đăng bài
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = '';
    $user_id = $_SESSION['user_id'];

    // Xử lý upload hình ảnh
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
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

    // Lưu bài viết vào cơ sở dữ liệu
    $sql = "INSERT INTO posts (title, content, image, user_id, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(1, $title, PDO::PARAM_STR);
    $stmt->bindValue(2, $content, PDO::PARAM_STR);
    $stmt->bindValue(3, $image, PDO::PARAM_STR);
    $stmt->bindValue(4, $user_id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Bài viết đã được đăng thành công! <a href="posts.php">Xem bài viết</a>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Có lỗi xảy ra khi đăng bài. Vui lòng thử lại.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    }
}
?>

<!-- Add Post Section -->
<section class="add-post-section">
    <div class="container">
        <div class="add-post-wrapper">
            <h2>Đăng bài mới</h2>
            <p class="section-subtitle">Chia sẻ kiến thức và kinh nghiệm của bạn về PHP với cộng đồng!</p>
            
            <form method="POST" enctype="multipart/form-data" class="add-post-form">
                <div class="form-group">
                    <label for="title" class="form-label">Tiêu đề:</label>
                    <input type="text" id="title" name="title" class="form-control" placeholder="Nhập tiêu đề bài viết" required>
                </div>
                <div class="form-group">
                    <label for="content" class="form-label">Nội dung:</label>
                    <textarea id="content" name="content" class="form-control" rows="6" placeholder="Nhập nội dung bài viết" required></textarea>
                </div>
                <div class="form-group">
                    <label for="image" class="form-label">Hình ảnh (tùy chọn):</label>
                    <div class="custom-file-upload">
                        <input type="file" id="image" name="image" class="form-control-file" accept="image/*">
                        <span class="file-name">Chưa chọn file...</span>
                    </div>
                </div>
                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary btn-submit">Đăng bài</button>
                    <a href="posts.php" class="btn btn-cancel" onclick="return confirm('Bạn có chắc muốn hủy? Các thay đổi sẽ không được lưu.')">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
// Hiển thị tên file khi chọn hình ảnh
document.getElementById('image').addEventListener('change', function(e) {
    const fileName = e.target.files.length > 0 ? e.target.files[0].name : 'Chưa chọn file...';
    document.querySelector('.file-name').textContent = fileName;
});
</script>

<?php include 'includes/footer.php'; ?>