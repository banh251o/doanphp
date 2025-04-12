<?php
include 'includes/db_connect.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$post_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = :id AND user_id = :user_id");
$stmt->execute(['id' => $post_id, 'user_id' => $_SESSION['user_id']]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    echo '<div class="alert alert-error">Bài viết không tồn tại hoặc bạn không có quyền chỉnh sửa!</div>';
    include 'includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image_path = $post['image'];

    // Xử lý tải lên hình ảnh mới (nếu có)
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB

        $file_type = $_FILES['image']['type'];
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];

        if (in_array($file_type, $allowed_types) && $file_size <= $max_size) {
            $file_name = 'post_' . time() . '_' . $_FILES['image']['name'];
            $image_path = 'images/uploads/' . $file_name;

            // Xóa hình ảnh cũ (nếu có)
            if ($post['image'] && file_exists($post['image'])) {
                unlink($post['image']);
            }

            // Di chuyển file mới vào thư mục uploads
            if (!move_uploaded_file($file_tmp, $image_path)) {
                echo '<div class="alert alert-error">Lỗi khi tải lên hình ảnh!</div>';
                $image_path = $post['image'];
            }
        } else {
            echo '<div class="alert alert-error">Hình ảnh không đúng định dạng hoặc quá lớn (tối đa 5MB)!</div>';
        }
    }

    // Cập nhật bài viết
    try {
        $stmt = $conn->prepare("UPDATE posts SET title = :title, content = :content, image = :image WHERE id = :id");
        $stmt->execute([
            'title' => $title,
            'content' => $content,
            'image' => $image_path,
            'id' => $post_id
        ]);
        echo '<div class="alert alert-success">Cập nhật bài viết thành công! <a href="post_detail.php?id=' . $post_id . '">Xem bài viết</a></div>';
    } catch (PDOException $e) {
        echo '<div class="alert alert-error">Lỗi: ' . $e->getMessage() . '</div>';
    }
}
?>

<h2>Chỉnh sửa bài viết</h2>
<form method="POST" enctype="multipart/form-data">
    <label for="title">Tiêu đề:</label>
    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
    
    <label for="content">Nội dung:</label>
    <textarea id="content" name="content" rows="5" required><?php echo htmlspecialchars($post['content']); ?></textarea>
    
    <label for="image">Hình ảnh hiện tại:</label>
    <?php if ($post['image']): ?>
    <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Hình ảnh bài viết" class="post-image">
<?php else: ?>
    <p>Chưa có hình ảnh.</p>
<?php endif; ?>
    
    <label for="image">Thay đổi hình ảnh (tùy chọn):</label>
    <input type="file" id="image" name="image" accept="image/*">
    
    <button type="submit">Cập nhật</button>
</form>

<?php include 'includes/footer.php'; ?>