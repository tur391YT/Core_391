<?php
require_once 'config/database.php';
$id = (int)($_GET['id'] ?? 0);
$post = $id ? $pdo->query("SELECT * FROM posts WHERE id = $id")->fetch() : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [$_POST['title'], $_POST['content'], $_POST['category'], $_POST['sub_category'], $_POST['image']];
    if ($id) {
        $data[] = $id;
        $pdo->prepare("UPDATE posts SET title=?, content=?, category=?, sub_category=?, image=? WHERE id=?")->execute($data);
    } else {
        $pdo->prepare("INSERT INTO posts (title, content, category, sub_category, image) VALUES (?,?,?,?,?)")->execute($data);
        $id = $pdo->lastInsertId();
    }
    header("Location: post.php?id=$id"); exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style2.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>
    <style>
        body { padding: 20px; }
        .edit-form input, .edit-form select { width: 100%; padding: 10px; margin: 10px 0; background: #1a1a1a; color: #fff; border: 1px solid #333; }
        .ck-editor__editable { min-height: 400px; background: #1a1a1a !important; color: white !important; }
    </style>
</head>
<body>
    <div class="header-container"><form method="POST" class="edit-form">
        <input type="text" name="title" value="<?= $post['title'] ?? '' ?>" placeholder="Заголовок" required>
        <div style="display:flex; gap:10px;">
            <select name="category">
                <option value="genshin" <?= ($post['category']??'')=='genshin'?'selected':'' ?>>Genshin</option>
                <option value="zzz" <?= ($post['category']??'')=='zzz'?'selected':'' ?>>ZZZ</option>
                <option value="wuwa" <?= ($post['category']??'')=='wuwa'?'selected':'' ?>>Wuwa</option>
            </select>
            <input type="text" name="sub_category" value="<?= $post['sub_category'] ?? '' ?>" placeholder="Подкатегория">
        </div>
        <input type="text" name="image" value="<?= $post['image'] ?? '' ?>" placeholder="URL картинки превью">
        <textarea name="content" id="editor"><?= $post['content'] ?? '' ?></textarea>
        <button type="submit" class="btn-accent" style="width:100%; margin-top:20px;">СОХРАНИТЬ</button>
    </form></div>
    <script>
        ClassicEditor.create(document.querySelector('#editor'), { language: 'ru' }).then(ed => {
            window.editor = ed;
        });
    </script>
</body>
</html>