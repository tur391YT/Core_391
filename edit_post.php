<?php
require_once 'config/database.php';

$id = (int)($_GET['id'] ?? 0);
$post = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch();
}

// Если пост не найден, возвращаемся на главную
if (!$post) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать: <?= htmlspecialchars($post['title']) ?> | CORE 391</title>
    <link rel="stylesheet" href="css/admin.css?v=<?= time(); ?>">
</head>
<body>

<div class="admin-container">
    <a href="index.php" style="color: #666; text-decoration: none; font-size: 13px;">← Назад в архив</a>
    <h1 style="margin-top: 10px;">Редактирование: <span style="color: var(--accent);"><?= htmlspecialchars($post['title']) ?></span></h1>

    <div class="template-panel">
        <button type="button" class="template-btn" onclick="addRow()">+ Добавить строку</button>
        <button type="button" class="template-btn delete-btn" onclick="deleteRow()" style="color: #ff4444;">- Удалить строку</button>
        <button type="button" class="template-btn" onclick="document.execCommand('bold')"><b>B</b></button>
        <button type="button" class="template-btn" onclick="quickImage()">🖼 Иконка</button>
    </div>

    <div id="visual-editor" contenteditable="true" class="editor-frame">
        <?= $post['content'] ?>
    </div>

    <form action="" method="POST" id="edit-form" style="margin-top: 20px;">
        <input type="hidden" name="id" value="<?= $post['id'] ?>">
        
        <label>Заголовок:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" class="admin-input">
        
        <label>Категория:</label>
        <input type="text" name="category" value="<?= htmlspecialchars($post['category']) ?>" class="admin-input">

        <input type="hidden" name="content" id="real-content">
        
        <button type="submit" class="save-btn">СОХРАНИТЬ ВСЕ ПРАВКИ</button>
    </form>
</div>

<?php
// Обработка сохранения прямо здесь для надежности
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];

    $sql = "UPDATE posts SET title=?, content=?, category=? WHERE id=?";
    $pdo->prepare($sql)->execute([$title, $content, $category, $id]);
    
    echo "<script>alert('Сохранено!'); window.location.href='post.php?id=$id';</script>";
}
?>

<script src="js/admin-core.js?v=<?= time(); ?>"></script>
</body>
</html>