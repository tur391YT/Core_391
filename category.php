<?php
require_once 'config/database.php';
require_once 'includes/header.php';

$game = isset($_GET['game']) ? trim($_GET['game']) : 'zzz';
$sql = "SELECT * FROM posts WHERE category = ? ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$game]);
$posts = $stmt->fetchAll();

$game_settings = [
    "genshin" => ["title" => "Genshin Impact", "color" => "#ff4d00"],
    "zzz"     => ["title" => "Zenless Zone Zero", "color" => "#ffff00"],
    "wuwa"    => ["title" => "Wuthering Waves", "color" => "#ffffff"],
    "hsr"     => ["title" => "Honkai Star Rail", "color" => "#00ccff"]
];

$current_title = isset($game_settings[$game]) ? $game_settings[$game]['title'] : strtoupper($game);
$current_accent = isset($game_settings[$game]) ? $game_settings[$game]['color'] : "#ff4d00";
$hero_bg = (!empty($posts[0]['banner_wide'])) ? $posts[0]['banner_wide'] : 'img/banner.png';
?>

<section class="hero" style="background-image: url('<?= htmlspecialchars($hero_bg) ?>');">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1 class="section-title">РАЗДЕЛ: <span style="color:<?= $current_accent ?>"><?= htmlspecialchars($current_title) ?></span></h1>
    </div>
</section>

<main class="main-content">
    <div class="index-grid"> <?php if (count($posts) > 0): ?>
            <?php foreach ($posts as $post): ?>
                <a href="post.php?id=<?= $post['id'] ?>" class="game-card">
                    <img src="<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                    <div class="content">
                        <span class="category-badge" style="color:<?= $current_accent ?> !important;">
                            <?= htmlspecialchars($post['sub_category']) ?>
                        </span>
                        <h3><?= htmlspecialchars($post['title']) ?></h3>
                        <div style="margin-top: 15px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #1a1a1a; padding-top: 10px;">
                            <span style="color:<?= $current_accent ?>; font-size: 0.8rem; font-weight: 900;">СМОТРЕТЬ</span>
                            <object><a href="edit_post.php?id=<?= $post['id'] ?>" style="color: #444; font-size: 0.7rem; text-decoration: none;">⚙️ ПРАВКА</a></object>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 100px 0;">
                <p style="color: #555;">В этом разделе пока пусто.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>