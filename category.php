<?php
require_once 'config/database.php';

// 1. Инициализация сессии и определение игры
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$game = isset($_GET['game']) ? trim($_GET['game']) : 'zzz';

// Настройки соответствия ключа в базе, заголовка и класса темы
$game_settings = [
    "genshin"   => ["title" => "Genshin Impact", "class" => "genshin"],
    "zzz"       => ["title" => "Zenless Zone Zero", "class" => "zzz"],
    "wuwa"      => ["title" => "Wuthering Waves", "class" => "wuwa"],
    "hsr"       => ["title" => "Honkai Star Rail", "class" => "star-rail"]
];

// Установка заголовка и класса для body
$current_title = isset($game_settings[$game]) ? $game_settings[$game]['title'] : strtoupper($game);
$body_class = isset($game_settings[$game]) ? $game_settings[$game]['class'] : "";

// Подключаем шапку (она использует $body_class для смены цветов)
require_once 'includes/header.php';

// 2. Получение постов из базы
$sql = "SELECT * FROM posts WHERE category = ? ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$game]);
$posts = $stmt->fetchAll();

// 3. Выбор баннера для верхней части
$game_banners = [
    "genshin" => "https://i.pinimg.com/1200x/0a/84/9d/0a849d1db2e9b5c7b6a5d196d399f81a.jpg", 
    "zzz"     => "https://i.pinimg.com/1200x/ac/c0/26/acc02683542b899c52129a232b43cb61.jpg",
    "wuwa"    => "https://i.pinimg.com/736x/f3/27/70/f32770d88f356fefbd53de6b40748bc8.jpg",
    "hsr"     => "https://i.pinimg.com/1200x/22/15/b9/2215b99842d6d7b7a96891fc06367a83.jpg"
];

if (isset($game_banners[$game])) {
    $hero_bg = $game_banners[$game];
} else {
    $hero_bg = (!empty($posts[0]['banner_wide'])) ? $posts[0]['banner_wide'] : 'img/banner.png';
}
?>

<section class="hero" style="background-image: url('<?= htmlspecialchars($hero_bg) ?>');">
    <div class="hero-overlay"></div>
    <div class="hero-content">
       <h1><span>РАЗДЕЛ:</span> <?php echo $current_title; ?></h1>
    </div>
</section>

<main class="main-content">
    <div class="index-grid"> 
        <?php if (count($posts) > 0): ?>
            <?php foreach ($posts as $post): ?>
                <a href="post.php?id=<?= $post['id'] ?>" class="game-card">
                    <img src="<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                    <div class="content">
                        <span class="category-badge"><?= htmlspecialchars($post['sub_category']) ?></span>
                        
                        <h3><?= htmlspecialchars($post['title']) ?></h3>
                        
                        <div style="margin-top: 15px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #1a1a1a; padding-top: 10px;">
                            <span class="btn-look">СМОТРЕТЬ</span>
                            
                            <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
                                <object>
                                    <a href="edit_post.php?id=<?= $post['id'] ?>" style="color: #444; font-size: 0.7rem; text-decoration: none;">⚙️ ПРАВКА</a>
                                </object>
                            <?php endif; ?>
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