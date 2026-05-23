<?php
require_once 'config/database.php';
require_once 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$post = null;

if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch();
}

if (!$post) { die("Гайд не найден."); }

$game_titles = [
    "genshin" => "Genshin Impact",
    "zzz"     => "Zenless Zone Zero",
    "wuwa"    => "Wuthering Waves",
    "hsr"     => "Honkai Star Rail"
];
$display_game = isset($game_titles[$post['category']]) ? $game_titles[$post['category']] : $post['category'];
$final_bg = !empty($post['banner_wide']) ? $post['banner_wide'] : $post['image'];

$is_wuwa = ($post['category'] === 'wuwa');
$theme_class = $is_wuwa ? 'theme-wuwa' : '';
?>

<link rel="stylesheet" href="css/content-styles.css">

<section class="hero" style="background-image: url('<?= htmlspecialchars($final_bg) ?>');">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <a href="category.php?game=<?= $post['category'] ?>" class="back-link" style="color: #fff; text-decoration: none; font-size: 0.9rem; opacity: 0.8;">
            ← Назад в раздел <?= htmlspecialchars($display_game) ?>
        </a>
        <h1 style="margin-top: 20px; font-size: 3rem; text-transform: uppercase; font-weight: 900;">
            <?= htmlspecialchars($post['title']) ?>
        </h1>
    </div>
</section>

<main class="main-content <?= $theme_class ?>">
    <div class="post-container-wide" style="max-width: 1000px; margin: 0 auto;">
        <div class="entry-content">
            <?php if (!empty($post['content'])): ?>
                <?= $post['content'] ?>
            <?php else: ?>
                <p style="color: #666; font-style: italic;">Содержание этого гайда скоро будет дополнено...</p>
            <?php endif; ?>
        </div>
        
        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
            <div style="margin-top: 50px; padding-top: 20px; border-top: 1px solid #222; display: flex; justify-content: flex-end;">
                <a href="edit_post.php?id=<?= $post['id'] ?>" style="color: #ff4d00; text-decoration: none; font-size: 0.8rem; border: 1px solid #333; padding: 8px 15px; border-radius: 4px; transition: 0.3s;">
                    ⚙️ РЕДАКТИРОВАТЬ МАТЕРИАЛ
                </a>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>