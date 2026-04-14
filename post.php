<?php 
require_once 'config/database.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    die("Статья не найдена.");
}

// Устанавливаем тему перед подключением хедера
$theme_class = htmlspecialchars($post['category']) . "-theme";

require_once 'includes/header.php'; 
?>

<article class="article-content-wrapper">
    <div style="text-align: center; margin-bottom: 40px;">
        <p style="color: var(--accent); font-weight: bold; text-transform: uppercase;">
            <?= htmlspecialchars($post['category']) ?> • <?= htmlspecialchars($post['sub_category']) ?>
        </p>
        <h1 style="font-size: 42px; margin-top: 10px;"><?= htmlspecialchars($post['title']) ?></h1>
        <a href="edit_post.php?id=<?= $id ?>" class="btn-edit">РЕДАКТИРОВАТЬ</a>
    </div>

    <div class="ck-content">
        <?= $post['content'] ?>
    </div>
</article>

<?php require_once 'includes/footer.php'; ?>