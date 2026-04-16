<?php
require_once 'config/database.php';

try {
    // Используем созданную нами колонку created_at
    $slider_stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT 3");
    $slider_posts = $slider_stmt->fetchAll();
} catch (Exception $e) {
    $slider_posts = [];
}

require_once 'includes/header.php'; 
?>

<section class="hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1>CORE <span class="accent-text">SYSTEM</span></h1>
        <p>ТВОЙ ЦЕНТР УПРАВЛЕНИЯ ГАЧА-МИРАМИ:</p>
    </div>
</section>

<main class="main-content">
    <h2 class="section-title">ПОСЛЕДНИЕ МАТЕРИАЛЫ</h2>
    
    <div class="top-slider">
        <?php if (!empty($slider_posts)): ?>
            <?php foreach ($slider_posts as $post): ?>
                <a href="post.php?id=<?= $post['id'] ?>" class="slide-item">
                    <img src="img/<?= htmlspecialchars($post['image'] ?? 'default-banner.png') ?>" alt="">
                    <div class="slide-info">
                        <span class="category-badge"><?= htmlspecialchars($post['sub_category'] ?? 'Гайд') ?></span>
                        <h3><?= htmlspecialchars($post['title']) ?></h3>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center; color:#555; width:100%; padding:20px;">Пока нет опубликованных материалов.</p>
        <?php endif; ?>
    </div>

    <div style="text-align: center; margin-bottom: 30px;">
        <a href="add_post.php" class="template-btn special" style="text-decoration: none; padding: 15px 30px; display: inline-block;">
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

<?php require_once 'includes/footer.php'; ?>