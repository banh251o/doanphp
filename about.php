<?php 
include 'includes/db_connect.php';
include 'includes/header.php';
?>

<!-- Spacer để tách biệt header và banner -->
<div class="banner-spacer"></div>

<!-- Banner Section -->
<section class="about-banner-section">
    <div class="container">
        <div class="banner-content">
            <h1>Giới thiệu về PHP</h1>
            <p>Tìm hiểu về ngôn ngữ lập trình PHP – nền tảng của hàng triệu website trên toàn thế giới.</p>
        </div>
    </div>
</section>

<!-- About PHP Section -->
<section class="about-section">
    <div class="container">
        <h2>PHP là ngôn ngữ gì?</h2>
        <p>PHP là một ngôn ngữ lập trình thông dịch được sử dụng chủ yếu để phát triển các trang web tĩnh, động và ứng dụng web. PHP viết tắt của <strong>"PHP: Hypertext Preprocessor"</strong>. Đây là một ngôn ngữ mã nguồn mở và được tích hợp trực tiếp vào mã HTML để tạo ra nội dung web động.</p>

        <!-- Banner cho mục "Ai là người tạo ra PHP?" -->
        <section class="section-banner creator-banner">
            <div class="banner-overlay">
                <h3>Ai là người tạo ra PHP?</h3>
            </div>
        </section>
        <p>PHP được tạo ra bởi <strong>Rasmus Lerdorf</strong> vào năm 1994. Ban đầu, PHP không phải là một ngôn ngữ lập trình đầy đủ. Rasmus Lerdorf phát triển PHP như một tập hợp các mã để quản lý thông tin cá nhân và thông tin liên hệ trên trang cá nhân của mình. Trong quá trình phát triển, ông đã mở rộng PHP để bao gồm khả năng tương tác với hệ thống file và tạo mã HTML động.</p>

        <!-- Banner cho mục "Lịch sử phát triển của PHP" -->
        <section class="section-banner history-banner">
            <div class="banner-overlay">
                <h3>Lịch sử phát triển của PHP</h3>
            </div>
        </section>
        <p>Sau khi Rasmus Lerdorf công bố PHP, ngôn ngữ này đã thu hút sự chú ý của cộng đồng lập trình viên. Dưới đây là các mốc quan trọng trong lịch sử phát triển của PHP:</p>

        <div class="timeline">
            <div class="timeline-item">
                <h4>1994</h4>
                <p>Rasmus Lerdorf tạo ra PHP.</p>
            </div>
            <div class="timeline-item">
                <h4>1997</h4>
                <p>PHP 3 ra đời, mã nguồn mở hoàn toàn.</p>
            </div>
            <div class="timeline-item">
                <h4>2000</h4>
                <p>PHP 4 được phát hành với Zend Engine 1.</p>
            </div>
            <div class="timeline-item">
                <h4>2004</h4>
                <p>PHP 5 ra mắt với Zend Engine 2.</p>
            </div>
            <div class="timeline-item">
                <h4>2015</h4>
                <p>PHP 7 mang lại hiệu suất vượt trội.</p>
            </div>
        </div>

        <p>Năm 1997, hai nhà phát triển <strong>Andi Gutmans</strong> và <strong>Zeev Suraski</strong> đã tham gia và viết lại lõi của PHP, tạo ra phiên bản <strong>PHP 3</strong>. Phiên bản này được coi là mã nguồn mở hoàn toàn và đã đánh dấu bước ngoặt lớn, thu hút sự chú ý từ cộng đồng phát triển web.</p>
        <p>Năm 2000, <strong>PHP 4</strong> ra đời với những cải tiến đáng kể về tốc độ và hiệu suất, nhờ vào sự giới thiệu của Zend Engine 1. Tiếp theo, <strong>PHP 5</strong> (phát hành năm 2004) mang đến kiến trúc mạnh mẽ hơn với Zend Engine 2, hỗ trợ lập trình hướng đối tượng tốt hơn và cải thiện hiệu suất.</p>
        <p>Đến năm 2015, <strong>PHP 7</strong> được phát hành, mang lại hiệu suất tăng đáng kể nhờ cải thiện quản lý bộ nhớ và cú pháp hiện đại hơn. PHP 7 nhanh hơn gấp đôi so với PHP 5, giúp nó trở thành lựa chọn hàng đầu cho các ứng dụng web lớn.</p>

        <!-- Banner cho mục "PHP ngày nay" -->
        <section class="section-banner today-banner">
            <div class="banner-overlay">
                <h3>PHP ngày nay</h3>
            </div>
        </section>
        <p>Hiện nay, PHP là một trong những ngôn ngữ lập trình web phổ biến nhất trên thế giới. Sức mạnh của PHP được chứng minh qua sự hỗ trợ của một cộng đồng lập trình viên lớn mạnh, cùng với nhiều framework nổi tiếng như <strong>Laravel</strong>, <strong>Symfony</strong>, và <strong>CodeIgniter</strong>. Các hệ thống quản trị nội dung (CMS) phổ biến như <strong>WordPress</strong>, <strong>Drupal</strong>, và <strong>Joomla</strong> cũng được xây dựng trên nền tảng PHP.</p>
        <p>Với sự phát triển không ngừng, PHP tiếp tục giữ vững vị trí của mình trong lĩnh vực phát triển web, đặc biệt trong việc xây dựng các ứng dụng web động, nhanh chóng và hiệu quả.</p>

        <h3>Tham gia cộng đồng PHP Việt Nam</h3>
        <p>Cộng đồng PHP Việt Nam là nơi để bạn học hỏi, chia sẻ kiến thức, và kết nối với những người đam mê PHP. Hãy tham gia ngay để khám phá thêm về ngôn ngữ lập trình mạnh mẽ này!</p>
        <a href="posts.php" class="btn btn-primary">Xem bài viết</a>
        <a href="add_post.php" class="btn btn-outline-primary">Đăng bài ngay</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>