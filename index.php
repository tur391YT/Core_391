<?php 
require_once 'config/database.php';

// Берем 3 самых свежих поста для слайдера
$slider_stmt = $pdo->query("SELECT * FROM posts ORDER BY date DESC LIMIT 3");
$slider_posts = $slider_stmt->fetchAll();

// Берем остальные посты (например, 3 штуки) для нижней сетки
$grid_stmt = $pdo->query("SELECT * FROM posts ORDER BY date DESC LIMIT 3 OFFSET 3");
$grid_posts = $grid_stmt->fetchAll();

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
    <h2 style="margin-bottom: 20px;">ПОСЛЕДНИЕ МАТЕРИАЛЫ</h2>
    
    <div class="top-slider">
        <?php foreach ($slider_posts as $post): ?>
            <a href="post.php?id=<?= $post['id'] ?>" class="slide-item">
                <img src="<?= htmlspecialchars($post['image']) ?>" alt="">
                <div class="slide-info">
                    <span class="category-badge"><?= $post['category'] ?></span>
                    <h3><?= htmlspecialchars($post['title']) ?></h3>
                </div>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="game-section-title">АКТУАЛЬНЫЕ ГАЙДЫ</div>
    
    <div class="index-grid">
        <?php foreach ($grid_posts as $post): ?>
            <a href="post.php?id=<?= $post['id'] ?>" class="game-card">
                <img src="<?= htmlspecialchars($post['image']) ?>" alt="">
                <div class="content">
                    <span class="category-badge"><?= $post['category'] ?></span>
                    <h3><?= htmlspecialchars($post['title']) ?></h3>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>