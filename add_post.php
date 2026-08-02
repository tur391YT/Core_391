<?php
// add_post.php
require_once 'config/database.php'; // Твое подключение к БД

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $game = trim($_POST['game_category'] ?? '');
    $content = trim($_POST['content'] ?? ''); // Контент из hidden input

    if (!empty($title) && !empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO posts (title, game_category, content, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$title, $game, $content]);

        header('Location: posts.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Создать пост — CORE 391</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="admin-container">
    <h1>Создать новый гайд / пост</h1>

    <form method="POST" action="add_post.php" onsubmit="return prepareForm();">
        
        <!-- Заголовок и выбор игры -->
        <div class="form-group">
            <label for="title">Заголовок поста:</label>
            <input type="text" id="title" name="title" required placeholder="Например: Гайд на Камелию">
        </div>

        <div class="form-group">
            <label for="game-category">Игра:</label>
            <select id="game-category" name="game_category">
                <option value="genshin">Genshin Impact</option>
                <option value="wuwa">Wuthering Waves</option>
                <option value="hsr">Honkai: Star Rail</option>
                <option value="zzz">Zenless Zone Zero</option>
            </select>
        </div>

        <!-- Панель быстрых шаблонов -->
        <div class="template-panel">
            <button type="button" class="template-btn" onclick="insertTemplate('sectionTitle')">+ Заголовок</button>
            <button type="button" class="template-btn" onclick="insertTemplate('weaponCard')">+ Оружие</button>
            <button type="button" class="template-btn" onclick="insertTemplate('artifactCard')">+ Артефакт</button>
            <button type="button" class="template-btn" onclick="insertTemplate('teamSlots')">+ Отряд</button>
            <button type="button" class="template-btn" onclick="insertTemplate('prosCons')">+ Плюсы/Минусы</button>
            <button type="button" class="template-btn" onclick="addRow()">+ Строка таблицы</button>
            <button type="button" class="template-btn" onclick="deleteRow()">- Удалить строку</button>
        </div>

        <!-- Визуальный редактор -->
        <div id="visual-editor" contenteditable="true" class="editor-area">
            <p>Начните писать пост или добавьте готовый блок с помощью кнопок выше...</p>
        </div>

        <!-- Скрытое поле для отправки чистого HTML в БД -->
        <input type="hidden" name="content" id="real-content">

        <button type="submit" class="submit-btn">Опубликовать пост</button>
    </form>
</div>

<script src="admin-core.js"></script>
<script>
    function prepareForm() {
        const editor = document.getElementById('visual-editor');
        const hiddenInput = document.getElementById('real-content');
        if (editor && hiddenInput) {
            hiddenInput.value = editor.innerHTML;
        }
        return true;
    }
</script>
</body>
</html>