<?php
require_once 'config/database.php';

$id = (int)($_GET['id'] ?? 0);
$post = $id ? $pdo->query("SELECT * FROM posts WHERE id = $id")->fetch() : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        $_POST['title'], 
        $_POST['content'], 
        $_POST['category'], 
        $_POST['sub_category'], 
        $_POST['image']
    ];
    
    if ($id) {
        $data[] = $id;
        $pdo->prepare("UPDATE posts SET title=?, content=?, category=?, sub_category=?, image=? WHERE id=?")->execute($data);
    } else {
        $pdo->prepare("INSERT INTO posts (title, content, category, sub_category, image) VALUES (?,?,?,?,?)")->execute($data);
        $id = $pdo->lastInsertId();
    }
    header("Location: post.php?id=$id"); 
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $id ? 'Редактировать' : 'Добавить' ?> пост | CORE 391</title>
    
    <link rel="stylesheet" href="css/admin.css">
    
    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>
    <script src="js/editor-templates.js"></script>
    <script src="js/admin-core.js"></script>
</head>
<body>

<div class="admin-container">
    <h1><?= $id ? 'Редактирование материала' : 'Новая запись' ?></h1>
    
    <form method="POST" class="edit-form">
        <div class="form-group">
            <label>Заголовок статьи</label>
            <input type="text" name="title" value="<?= htmlspecialchars($post['title'] ?? '') ?>" placeholder="Введите название..." required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Основная категория</label>
                <select name="category">
                    <option value="genshin" <?= ($post['category']??'')=='genshin'?'selected':'' ?>>Genshin Impact</option>
                    <option value="zzz" <?= ($post['category']??'')=='zzz'?'selected':'' ?>>Zenless Zone Zero</option>
                    <option value="wuwa" <?= ($post['category']??'')=='wuwa'?'selected':'' ?>>Wuthering Waves</option>
                    <option value="hsr" <?= ($post['category']??'')=='hsr'?'selected':'' ?>>Honkai: Star Rail</option>
                </select>
            </div>
            <div class="form-group">
                <label>Подкатегория (метка)</label>
                <input type="text" name="sub_category" value="<?= htmlspecialchars($post['sub_category'] ?? '') ?>" placeholder="Например: Гайды">
            </div>
        </div>

        <div class="form-group">
            <label>Имя файла картинки (из папки img/)</label>
            <input type="text" name="image" value="<?= htmlspecialchars($post['image'] ?? '') ?>" placeholder="banner.png">
        </div>

        <div class="form-group">
            <label>Контент статьи</label>
            <div class="template-panel">
                <button type="button" class="template-btn special" onclick="handleInsert('full_guide')">УЛЬТИМАТИВНЫЙ БИЛД</button>
                <button type="button" class="template-btn" onclick="handleInsert('news')">+ Новость (Аккордеоны)</button>
                <button type="button" class="template-btn" onclick="handleInsert('tier')">+ Тир-лист</button>
            </div>
            
            <textarea name="content" id="editor"><?= $post['content'] ?? '' ?></textarea>
            
            <div class="form-actions">
                <button type="submit" class="btn-save">СОХРАНИТЬ ИЗМЕНЕНИЯ</button>
                <button type="button" class="template-btn btn-danger" onclick="confirmClear()" style="margin-left: auto; border-color: #ff0000; color: #ff0000;">⚠ ОЧИСТИТЬ ВСЁ</button>
                <a href="admin.php" class="btn-cancel">Отмена</a>
            </div>
        </div>
    </form>
</div>

<script>
    /**
     * Прослойка для вызова функции из внешнего файла js/editor-templates.js
     * Использует переменную myEditor, которая инициализируется в js/admin-core.js
     */
    function handleInsert(type) {
        if (typeof insertTemplate === 'function' && myEditor) {
            insertTemplate(type, myEditor);
        } else {
            console.error('Ошибка: Редактор или скрипт шаблонов еще не загружены.');
        }
    }
</script>

</body>
</html>