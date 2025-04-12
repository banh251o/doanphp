<?php
include 'includes/db_connect.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];
    $image_path = NULL;

    // Xử lý tải lên hình ảnh
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB

        $file_type = $_FILES['image']['type'];
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];

        // Kiểm tra định dạng và kích thước
        if (in_array($file_type, $allowed_types) && $file_size <= $max_size) {
            $file_name = 'post_' . time() . '_' . $_FILES['image']['name'];
            $image_path = 'images/uploads/' . $file_name;

            // Di chuyển file vào thư mục uploads
            if (move_uploaded_file($file_tmp, $image_path)) {
                // Tải lên thành công
            } else {
                echo '<div class="alert alert-error">Lỗi khi tải lên hình ảnh!</div>';
                $image_path = NULL;
            }
        } else {
            echo '<div class="alert alert-error">Hình ảnh không đúng định dạng hoặc quá lớn (tối đa 5MB)!</div>';
        }
    }

    // Lưu bài viết vào database
    try {
        $stmt = $conn->prepare("INSERT INTO posts (title, content, user_id, image) VALUES (:title, :content, :user_id, :image)");
        $stmt->execute([
            'title' => $title,
            'content' => $content,
            'user_id' => $user_id,
            'image' => $image_path
        ]);
        // Lấy ID của bài viết vừa thêm
$post_id = $conn->lastInsertId();
echo '<div class="alert alert-success">Bài viết đã được đăng thành công! <a href="post_detail.php?id=' . $post_id . '">Xem bài viết</a></div>';
    } catch (PDOException $e) {
        echo '<div class="alert alert-error">Lỗi: ' . $e->getMessage() . '</div>';
    }
}
?>

<h2>Đăng bài mới</h2>
<form method="POST" enctype="multipart/form-data">
    <label for="title">Tiêu đề:</label>
    <input type="text" id="title" name="title" required>
    
    <label for="content">Nội dung:</label>
    <textarea id="content" name="content" rows="5" required></textarea>
    
    <label for="image">Hình ảnh (tùy chọn):</label>
    <input type="file" id="image" name="image" accept="image/*">
    
    <button type="submit">Đăng bài</button>
</form>

<?php include 'includes/footer.php'; ?>