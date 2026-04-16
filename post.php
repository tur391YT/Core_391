<?php
require_once 'config/database.php';

// Получаем ID из адресной строки
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch();
}

if (!$post) {
    die("Гайд не найден.");
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($post['title']) ?> | CORE 391 Archive</title>
    <link rel="stylesheet" href="css/admin.css?v=<?= time(); ?>">
    <style>
        .post-content { max-width: 1000px; margin: 0 auto; padding: 40px; background: #0f0f0f; border-radius: 15px; }
        .post-header { text-align: center; margin-bottom: 40px; }
        .post-header h1 { font-size: 48px; text-transform: uppercase; color: #ff4d00; }
    </style>
</head>
<body>

<div class="post-content">
    <div class="post-header">
        <a href="index.php" style="color: #666; text-decoration: none;">← Назад в архив</a>
        <h1><?= htmlspecialchars($post['title']) ?></h1>
        <p style="color: #888;">Категория: <?= htmlspecialchars($post['category']) ?></p>
    </div>

    <div class="entry-content">
        <?= $post['content'] ?>
    </div>
</div>

</body>
</html>