<?php
require_once 'config/database.php';

// Получаем название игры из ссылки, например: category.php?game=Genshin
$game = isset($_GET['game']) ? $_GET['game'] : '';

// Фильтруем посты по категории
$stmt = $pdo->prepare("SELECT * FROM posts WHERE category LIKE ? ORDER BY id DESC");
$stmt->execute(["%$game%"]);
$posts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Архив <?= htmlspecialchars($game) ?> | CORE 391</title>
    <link rel="stylesheet" href="css/admin.css">
    <style>
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; padding: 20px; }
        .card { background: #111; border: 1px solid #333; padding: 15px; border-radius: 10px; text-align: center; transition: 0.3s; text-decoration: none; color: white; }
        .card:hover { border-color: #ff4d00; transform: translateY(-5px); }
        .card img { width: 100%; border-radius: 5px; margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="admin-container">
    <h1>Раздел: <span style="color:var(--accent)"><?= htmlspecialchars($game) ?></span></h1>
    
    <div class="grid">
        <?php foreach ($posts as $post): ?>
            <a href="post.php?id=<?= $post['id'] ?>" class="card">
                <h3><?= htmlspecialchars($post['title']) ?></h3>
                <p style="font-size: 12px; color: #666;">Посмотреть билд</p>
            </a>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>