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
        <h1>CORE <span style="color: #ff4d00 !important;">SYSTEM</span></h1>
        <p>ТВОЙ ЦЕНТР УПРАВЛЕНИЯ ГАЧА-МИРАМИ:</p>
    </div>
</section>

<main class="main-content">
    <h2 class="section-title">ПОСЛЕДНИЕ МАТЕРИАЛЫ</h2>

    <div class="slider-container" style="position: relative; overflow: hidden; border-radius: 15px; margin-bottom: 40px; height: 450px;">
        <div class="top-slider" id="mainSlider" style="display: flex; transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1); height: 100%;">
            <?php if (!empty($slider_posts)): ?>
                <?php foreach ($slider_posts as $post): ?>
                    <?php 
                        $img_path = $post['image'];
                        if (!filter_var($img_path, FILTER_VALIDATE_URL)) {
                            $img_path = 'img/' . $img_path;
                        }
                    ?>
                    <a href="post.php?id=<?= $post['id'] ?>" class="slide-item" style="flex: 0 0 100%; position: relative; text-decoration: none;">
                        <img src="<?= htmlspecialchars($img_path ?: 'img/default-banner.png') ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <div class="slide-info" style="position: absolute; bottom: 0; left: 0; right: 0; padding: 40px; background: linear-gradient(transparent, rgba(0,0,0,0.9));">
                            <span class="category-badge" style="background: #ff4d00; color: #fff; padding: 5px 12px; border-radius: 4px; font-size: 12px; font-weight: 900;">
                                <?= htmlspecialchars($post['sub_category'] ?? 'ГАЙДЫ') ?>
                            </span>
                            <h3 style="color: #fff; font-size: 28px; margin-top: 15px; text-transform: uppercase; font-weight: 900;">
                                <?= htmlspecialchars($post['title']) ?>
                            </h3>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align:center; color:#555; width:100%; padding:20px;">Пока нет опубликованных материалов.</p>
            <?php endif; ?>
        </div>

        <div style="position: absolute; bottom: 20px; right: 40px; display: flex; gap: 10px; z-index: 10;">
            <?php for($i = 0; $i < count($slider_posts); $i++): ?>
                <div class="nav-dot" onclick="currentSlide(<?= $i ?>)" style="width: 12px; height: 12px; border-radius: 50%; background: rgba(255,255,255,0.3); cursor: pointer; transition: 0.3s;"></div>
            <?php endfor; ?>
        </div>
    </div>

    <div style="text-align: center; margin-bottom: 50px;">
        <a href="add_post.php" class="template-btn special">
            + СОЗДАТЬ НОВЫЙ БИЛД
        </a>
    </div>

    <h2 class="section-title">ВСЕ ИГРЫ</h2>
    <div class="index-grid promo-footer-grid">
        <a href="category.php?game=Genshin" class="game-card">
            <img src="https://i.pinimg.com/736x/a2/67/86/a26786b9c7bbffb87e1ebdf626c1cec6.jpg" alt="Genshin Impact">
            <div class="content">
                <span class="category-badge">Genshin Impact</span>
                <h3>Гайды, билды и новости</h3>
            </div>
        </a>

        <a href="category.php?game=ZZZ" class="game-card">
            <img src="https://i.pinimg.com/736x/fc/7e/4a/fc7e4ab9a142afb49ff522c00f7061a2.jpg" alt="Zenless Zone Zero">
            <div class="content">
                <span class="category-badge">Zenless Zone Zero</span>
                <h3>Агенты и билды</h3>
            </div>
        </a>

        <a href="category.php?game=Wuthering" class="game-card">
            <img src="https://i.pinimg.com/736x/f5/9c/51/f59c511d7cd5239529dd452e95f50a22.jpg" alt="Wuthering Waves">
            <div class="content">
                <span class="category-badge">Wuthering Waves</span>
                <h3>Резонаторы и фарм</h3>
            </div>
        </a>
    </div>
</main>

<style>
/* Эффект выделения для всех карточек */
.game-card, .slide-item {
    transition: transform 0.4s ease, box-shadow 0.4s ease !important;
}
.game-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 15px 30px rgba(255, 77, 0, 0.3);
}
.nav-dot:hover, .nav-dot.active {
    background: #ff4d00 !important;
    transform: scale(1.3);
}
</style>

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
    slider.style.transform = `translateX(-${slideIndex * 100}%)`;
    dots.forEach((dot, index) => {
        dot.classList.toggle('active', index === slideIndex);
    });
}

// Запуск автопрокрутки
if (dots.length > 0) {
    updateSlider();
    setInterval(showSlides, 5000); // Смена каждые 5 секунд
}
</script>

<?php require_once 'includes/footer.php'; ?>