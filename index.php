<?php
require_once 'config/database.php';

try {
    // Получаем 3 последних поста для слайдера
    $slider_stmt = $pdo->query("SELECT * FROM posts ORDER BY id DESC LIMIT 3");
    $slider_posts = $slider_stmt->fetchAll();
} catch (Exception $e) {
    $slider_posts = [];
}

require_once 'includes/header.php'; 
?>

<section class="hero" style="background-image: url('img/banner.png');">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1>CORE <span>SYSTEM</span></h1>
        <p>ТВОЙ ЦЕНТР УПРАВЛЕНИЯ ГАЧА-МИРАМИ:</p>
    </div>
</section>

<main class="main-content">
    <h2 class="section-title">ПОСЛЕДНИЕ МАТЕРИАЛЫ</h2>

    <div class="slider-container">
        <div class="top-slider" id="mainSlider">
            <?php if (!empty($slider_posts)): ?>
                <?php foreach ($slider_posts as $post): ?>
                    <?php 
                        $img_path = $post['image'] ?? '';
                        
                        if (!empty($img_path)) {
                            // Если это не абсолютный URL и не начинается с img/ или /, добавляем img/
                            if (!filter_var($img_path, FILTER_VALIDATE_URL) && strpos($img_path, 'img/') !== 0 && strpos($img_path, '/') !== 0) {
                                $img_path = 'img/' . $img_path;
                            }
                        } else {
                            $img_path = 'img/default-banner.png';
                        }
                    ?>
                    <a href="post.php?id=<?= $post['id'] ?>" class="slide-item">
                        <img src="<?= htmlspecialchars($img_path) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                        <div class="slide-info">
                            <span class="category-badge">
                                <?= htmlspecialchars($post['sub_category'] ?? 'ГАЙДЫ') ?>
                            </span>
                            <h3><?= htmlspecialchars($post['title']) ?></h3>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="empty-msg">Пока нет опубликованных материалов.</p>
            <?php endif; ?>
        </div>

        <div class="slider-nav">
            <?php for ($i = 0; $i < count($slider_posts); $i++): ?>
                <div class="nav-dot" onclick="currentSlide(<?= $i ?>)"></div>
            <?php endfor; ?>
        </div>
    </div>

    <h2 class="section-title">ВСЕ ИГРЫ</h2>
    <div class="index-grid promo-footer-grid">
        <a href="category.php?game=genshin" class="game-card">
            <img src="https://i.pinimg.com/736x/a2/67/86/a26786b9c7bbffb87e1ebdf626c1cec6.jpg" alt="Genshin Impact">
            <div class="content">
                <span class="category-badge">Genshin Impact</span>
                <h3>Гайды, билды и новости</h3>
            </div>
        </a>

        <a href="category.php?game=zzz" class="game-card">
            <img src="https://i.pinimg.com/736x/fc/7e/4a/fc7e4ab9a142afb49ff522c00f7061a2.jpg" alt="Zenless Zone Zero">
            <div class="content">
                <span class="category-badge">Zenless Zone Zero</span>
                <h3>Агенты и билды</h3>
            </div>
        </a>

        <a href="category.php?game=wuwa" class="game-card">
            <img src="https://i.pinimg.com/736x/f5/9c/51/f59c511d7cd5239529dd452e95f50a22.jpg" alt="Wuthering Waves">
            <div class="content">
                <span class="category-badge">Wuthering Waves</span>
                <h3>Резонаторы и фарм</h3>
            </div>
        </a>
    </div>
</main>

<script>
let slideIndex = 0;
const slider = document.getElementById('mainSlider');
const dots = document.querySelectorAll('.nav-dot');

function showSlides() {
    slideIndex++;
    if (slideIndex >= dots.length) { slideIndex = 0; }
    updateSlider();
}

function currentSlide(n) {
    slideIndex = n;
    updateSlider();
}

function updateSlider() {
    if (!slider) return;
    slider.style.transform = `translateX(-${slideIndex * 100}%)`;
    dots.forEach((dot, index) => {
        dot.classList.toggle('active', index === slideIndex);
    });
}

if (dots.length > 0) {
    updateSlider();
    setInterval(showSlides, 5000);
}
</script>

<?php require_once 'includes/footer.php'; ?>