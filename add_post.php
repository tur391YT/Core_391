<?php
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = "Билды";

    $sql = "INSERT INTO posts (title, content, category) VALUES (?, ?, ?)";
    $pdo->prepare($sql)->execute([$title, $content, $category]);
    
    $newId = $pdo->lastInsertId();
    header("Location: post.php?id=$newId");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Создать новый билд | CORE 391</title>
    <link rel="stylesheet" href="css/admin.css?v=<?= time(); ?>">
</head>
<body>

<div class="admin-container">
    <a href="index.php" style="color: #666; text-decoration: none; font-size: 13px;">← Назад в админку</a>
    <h1 style="margin-top: 10px;">Создать новый билд</h1>

    <div class="template-panel">
        <button type="button" class="template-btn special" onclick="insertFullTemplate()">ЗАГРУЗИТЬ ПУСТОЙ ШАБЛОН</button>
        <button type="button" class="template-btn" onclick="quickImage()">🖼 ИКОНКА</button>
    </div>

    <div id="visual-editor" contenteditable="true" class="editor-frame">
        <p style="color: #666;">Нажмите оранжевую кнопку, чтобы развернуть структуру гайда на 9 уровней...</p>
    </div>

    <form action="" method="POST" style="margin-top: 20px;">
        <input type="text" name="title" placeholder="Название персонажа (например: Арлекино)" required 
               style="width:100%; margin-bottom:10px; background:#111; color:#fff; border:1px solid #333; padding:10px;">
        
        <input type="hidden" name="content" id="real-content">
        <button type="submit" class="save-btn">ОПУБЛИКОВАТЬ КАРТОЧКУ</button>
    </form>
</div>

<script>
window.insertFullTemplate = function() {
    let resRows = '';
    const levels = ['1-20','20-40','40-50','50-60','60-70','70-80','80-90'];
    
    // Генерируем строки ресурсов
    levels.forEach(lvl => {
        resRows += `<tr><td>${lvl}</td><td><img src="img/items/placeholder.png"> x0 Предмет</td><td>0 моры</td></tr>`;
    });

    const html = `
        <h2 style="color:#ff4d00">МАТЕРИАЛЫ ВОЗВЫШЕНИЯ</h2>
        <table class="guide-table">
            <thead><tr><th>УРОВЕНЬ</th><th>РЕСУРСЫ</th><th>ВАЛЮТА</th></tr></thead>
            <tbody>${resRows}</tbody>
        </table>

        <h2 style="color:#ff4d00">ЛУЧШЕЕ ОРУЖИЕ</h2>
        <table class="guide-table">
            <thead><tr><th>ОРУЖИЕ</th><th>ОПИСАНИЕ ПАССИВКИ</th><th>РАНГ</th></tr></thead>
            <tbody>
                <tr>
                    <td>
                        <img src="img/weapons/placeholder.png"><br>
                        <small style="color:#888; font-size:10px;">АТК: 674 | КРИТ: 44%</small>
                    </td>
                    <td><b>Название оружия</b><br>Описание эффектов...</td>
                    <td>S+</td>
                </tr>
            </tbody>
        </table>

        <h2 style="color:#ff4d00">ЛУЧШИЕ ОТРЯДЫ</h2>
        <table class="guide-table">
            <thead><tr><th>РОЛЬ</th><th>ПЕРСОНАЖИ</th><th>ЗАМЕНА</th></tr></thead>
            <tbody>
                <tr>
                    <td>Main DPS</td>
                    <td><img src="img/chars/placeholder.png"> <img src="img/chars/placeholder.png"></td>
                    <td>Описание...</td>
                </tr>
            </tbody>
        </table>
    `;

    document.getElementById('visual-editor').innerHTML = html;
    document.getElementById('real-content').value = html;
};
</script>

<script src="js/admin-core.js?v=<?= time(); ?>"></script>
</body>
</html>