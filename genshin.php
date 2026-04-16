<?php
require_once 'config/database.php';

// Запрос: выбираем все посты для конкретной игры
$stmt = $pdo->prepare("SELECT id, title, category, image FROM posts WHERE category = 'Genshin Impact' ORDER BY id DESC");
$stmt->execute();
$posts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Genshin Impact Archive | CORE 391</title>
    <link rel="stylesheet" href="css/style2.css"> </head>
<body>

<div class="archive-container">
    <h1>АРХИВ: <span style="color: var(--accent);">GENSHIN IMPACT</span></h1>

    <div class="posts-grid">
        <?php if ($posts): ?>
            <?php foreach ($posts as $post): ?>
                <a href="post.php?id=<?= $post['id'] ?>" class="post-card">
                    <div class="card-image">
                        <img src="<?= $post['image'] ?: 'img/default-char.png' ?>" alt="<?= $post['title'] ?>">
                    </div>
                    <div class="card-info">
                        <h3><?= htmlspecialchars($post['title']) ?></h3>
                        <span><?= htmlspecialchars($post['category']) ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p>В этой категории пока нет билдов...</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>