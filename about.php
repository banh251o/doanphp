<?php 
include 'includes/db_connect.php';
include 'includes/header.php';
?>

<!-- About Section -->
<section class="about-section">
    <div class="container">
        <h1 class="section-title">Giới thiệu về PHP</h1>

        <!-- PHP là gì? -->
        <div class="about-content">
            <h2>PHP là ngôn ngữ gì?</h2>
            <p>PHP là một ngôn ngữ lập trình thông dịch được sử dụng chủ yếu để phát triển các trang web tĩnh, động và ứng dụng web. PHP viết tắt của <strong>"PHP: Hypertext Preprocessor"</strong>. Đây là một ngôn ngữ mã nguồn mở và được tích hợp trực tiếp vào mã HTML để tạo ra nội dung web động.</p>
        </div>

        <!-- Ai tạo ra PHP? -->
        <div class="about-content">
            <h2>Ai là người tạo ra PHP?</h2>
            <p>PHP được tích hợp với mã HTML để tạo nội dung động trên web. Nhưng ban đầu, nó không phải là ngôn ngữ lập trình đầy đủ. <strong>Rasmus Lerdorf</strong> tạo ra PHP như một tập hợp các mã để quản lý thông tin cá nhân và thông tin liên hệ trên trang cá nhân của mình. Trong quá trình phát triển, Rasmus đã mở rộng PHP để bao gồm khả năng tương tác với hệ thống file và tạo mã HTML động.</p>
            <p>Sau này, hai nhà phát triển <strong>Andi Gutmans</strong> và <strong>Zeev Suraski</strong> đã mở rộng PHP và tạo ra phiên bản PHP 3 vào năm 1997. PHP 3 được coi là mã nguồn mở hoàn toàn đã thu hút sự chú ý lớn từ cộng đồng phát triển web.</p>
        </div>

        <!-- Lịch sử phát triển -->
        <div class="about-content">
            <h2>Lịch sử phát triển của PHP</h2>
            <p>Sự ra đời của PHP 4 vào năm 2000 đã có những cải tiến đáng kể về tốc độ và hiệu suất. PHP 5 với sự giới thiệu của Zend Engine 2 đã mang đến kiến trúc mạnh mẽ hơn và đạt hiệu suất tốt hơn.</p>
            <p>PHP tiếp tục phát triển với phiên bản PHP 7 mang lại hiệu suất tăng đáng kể trong việc quản lý bộ nhớ tốt hơn và cải thiện về cú pháp. Hiện nay, PHP là một trong những ngôn ngữ lập trình web phổ biến nhất trên thế giới. Sức mạnh của nó đã được chứng minh thông qua sự hỗ trợ của cộng đồng lập trình viên lớn mạnh, nhiều framework và ứng dụng web nổi tiếng được xây dựng bằng PHP.</p>
        </div>

        <!-- Call to Action -->
        <div class="cta-section">
            <h3>Tham gia Cộng đồng PHP Việt Nam ngay hôm nay!</h3>
            <p>Học hỏi, chia sẻ và phát triển kỹ năng lập trình PHP cùng chúng tôi.</p>
            <a href="posts.php" class="btn btn-primary">Khám phá bài viết</a>
            <a href="add_post.php" class="btn btn-outline-primary">Đăng bài ngay</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>