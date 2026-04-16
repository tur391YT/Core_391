<?php
require_once 'config/database.php';

$id = (int)($_GET['id'] ?? 0);
$post = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = $_POST['content'];
    $category = $_POST['category'];
    $sub_category = $_POST['sub_category'];
    $image = $_POST['image'];

    if (!empty($title) && !empty($content)) {
        if ($id) {
            $sql = "UPDATE posts SET title=?, content=?, category=?, sub_category=?, image=? WHERE id=?";
            $pdo->prepare($sql)->execute([$title, $content, $category, $sub_category, $image, $id]);
        } else {
            $sql = "INSERT INTO posts (title, content, category, sub_category, image) VALUES (?,?,?,?,?)";
            $pdo->prepare($sql)->execute([$title, $content, $category, $sub_category, $image]);
            $id = $pdo->lastInsertId();
        }
        header("Location: post.php?id=$id"); 
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= $id ? 'Редактировать' : 'Добавить' ?> пост | CORE 391</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
<div class="admin-container">
    <h1><?= $id ? 'Редактирование материала' : 'Новая запись' ?></h1>
    
    <form method="POST" class="edit-form">
        <div class="form-group">
            <label>Заголовок статьи</label>
            <input type="text" name="title" value="<?= htmlspecialchars($post['title'] ?? '') ?>" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Основная категория</label>
                <select name="category" id="category-select" class="category-select <?= htmlspecialchars($post['category']??'') ?>">
                    <option value="genshin" <?= ($post['category']??'')=='genshin'?'selected':'' ?>>Genshin Impact</option>
                    <option value="zzz" <?= ($post['category']??'')=='zzz'?'selected':'' ?>>Zenless Zone Zero</option>
                    <option value="wuwa" <?= ($post['category']??'')=='wuwa'?'selected':'' ?>>Wuthering Waves</option>
                    <option value="hsr" <?= ($post['category']??'')=='hsr'?'selected':'' ?>>Honkai: Star Rail</option>
                </select>
            </div>
            <div class="form-group">
                <label>Подкатегория (метка)</label>
                <input type="text" name="sub_category" value="<?= htmlspecialchars($post['sub_category'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Ссылка на картинку</label>
            <input type="text" name="image" id="post_image" value="<?= htmlspecialchars($post['image'] ?? '') ?>">
            <div class="preview-container">
                <img id="banner-preview" src="<?= htmlspecialchars($post['image'] ?? '') ?>" 
                     style="max-width: 300px; margin-top: 10px; border-radius: 10px; <?= empty($post['image']) ? 'display: none;' : '' ?>">
            </div>
        </div>

       <div class="form-group">
    <label>Контент статьи</label>
<div class="template-panel">
    <button type="button" class="template-btn special" onclick="insertTemplate('full_guide')">УЛЬТИМАТИВНЫЙ БИЛД</button>
    <button type="button" class="template-btn" onclick="insertTemplate('news')">+ НОВОСТЬ</button>
    <span style="border-left: 1px solid #333; margin: 0 10px;"></span>
    <button type="button" class="template-btn" onclick="addRow()" title="Поставьте курсор в таблицу">+ Строка</button>
    <button type="button" class="template-btn" onclick="deleteRow()" style="color:#ff4444;">- Удалить строку</button>
    <button type="button" class="template-btn" onclick="execCmd('bold')"><b>B</b></button>
</div>
    
    <div id="visual-editor" contenteditable="true" spellcheck="false"><?= $post['content'] ?? '' ?></div>
    
    <input type="hidden" name="content" id="real-content" value="<?= htmlspecialchars($post['content'] ?? '') ?>">
    
    <div class="form-actions" style="margin-top: 20px; display: flex; gap: 10px;">
        <button type="submit" class="btn-save">СОХРАНИТЬ ИЗМЕНЕНИЯ</button>
        <button type="button" class="btn-danger" style="background: #ff0000; padding: 0 20px; border-radius: 8px; font-weight: bold; cursor: pointer;" onclick="confirmClear()">⚠ ОЧИСТИТЬ</button>
        <a href="admin.php" class="btn-cancel" style="padding: 20px; text-decoration: none; color: #888;">Отмена</a>
    </div>
</div>

<script src="js/admin-core.js?update=<?= time(); ?>"></script>
<script>
    // Логика переключения категорий (оставляем твою)
    document.getElementById('category-select').addEventListener('change', function() {
        this.className = 'category-select ' + this.value;
    });
</script>
</body>
</html>