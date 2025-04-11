<?php 
include 'includes/header.php'; 
include 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author = $_POST['author'];
    
    $stmt = $conn->prepare("INSERT INTO posts (title, content, author) VALUES (:title, :content, :author)");
    $stmt->execute(['title' => $title, 'content' => $content, 'author' => $author]);
    
    echo '<p>Bài viết đã được đăng thành công! <a href="posts.php">Xem bài viết</a></p>';
}
?>
<h2>Đăng bài mới</h2>
<form method="POST">
    <label for="title">Tiêu đề:</label>
    <input type="text" id="title" name="title" required>
    
    <label for="content">Nội dung:</label>
    <textarea id="content" name="content" rows="5" required></textarea>
    
    <label for="author">Tác giả:</label>
    <input type="text" id="author" name="author" required>
    
    <button type="submit">Đăng bài</button>
</form>
<?php include 'includes/footer.php'; ?>