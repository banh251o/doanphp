<?php 
session_start(); // Bắt đầu session
include 'includes/db_connect.php';
include 'includes/header.php';

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user_id'])) {
    // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit();
}

// Xử lý đăng bài
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = '';
    $user_id = $_SESSION['user_id']; // Lấy user_id từ session

    // Xử lý upload hình ảnh
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        // Kiểm tra và tạo thư mục uploads nếu chưa tồn tại
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $target_file;
        } else {
            echo '<div class="alert alert-danger">Lỗi khi upload hình ảnh. Vui lòng thử lại.</div>';
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
        echo '<div class="alert alert-success">Bài viết đã được đăng thành công! <a href="posts.php">Xem bài viết</a></div>';
    } else {
        echo '<div class="alert alert-danger">Có lỗi xảy ra khi đăng bài. Vui lòng thử lại.</div>';
    }
}
?>

<!-- Add Post Section -->
<section class="add-post-section">
    <div class="container">
        <h2>Đăng bài mới</h2>
        <p>Chia sẻ kiến thức và kinh nghiệm của bạn về PHP với cộng đồng!</p>
        
        <form method="POST" enctype="multipart/form-data" class="add-post-form">
            <div class="form-group">
                <label for="title">Tiêu đề:</label>
                <input type="text" id="title" name="title" class="form-control" placeholder="Nhập tiêu đề bài viết" required>
            </div>
            <div class="form-group">
                <label for="content">Nội dung:</label>
                <textarea id="content" name="content" class="form-control" rows="6" placeholder="Nhập nội dung bài viết" required></textarea>
            </div>
            <div class="form-group">
                <label for="image">Hình ảnh (tùy chọn):</label>
                <input type="file" id="image" name="image" class="form-control-file">
            </div>
            <div class="form-buttons">
                <button type="submit" class="btn btn-primary btn-submit">Đăng bài</button>
                <a href="posts.php" class="btn btn-cancel">Hủy</a>
            </div>
        </form>
    </div>
</section>

<?php include 'includes/footer.php'; ?>