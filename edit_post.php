<?php
// edit_post.php
require_once 'config/database.php';

$id = intval($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("Пост не найден!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $game = trim($_POST['game_category'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if (!empty($title) && !empty($content)) {
        $updateStmt = $pdo->prepare("UPDATE posts SET title = ?, game_category = ?, content = ? WHERE id = ?");
        $updateStmt->execute([$title, $game, $content, $id]);

        header("Location: edit_post.php?id={$id}&success=1");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование поста #<?php echo $post['id']; ?> — CORE 391</title>
    
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/content-styles.css">

    <style>
        /* Принудительная тёмная тема для редактора */
        body {
            background-color: #0d0d0f !important;
            color: #e1e1e6 !important;
            font-family: 'Segoe UI', Arial, sans-serif;
            padding: 20px;
        }

        .admin-container {
            max-width: 1100px;
            margin: 0 auto;
            background: #16161a;
            padding: 25px;
            border-radius: 10px;
            border: 1px solid #26262b;
        }

        /* Кнопки в админке */
        .template-panel {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 15px;
            background: #101012;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #26262b;
        }

        .template-btn {
            background: #1f1f24;
            color: #fff;
            border: 1px solid #33333a;
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
        }

        .template-btn:hover {
            background: #ff7700;
            color: #000;
            border-color: #ff7700;
        }

        /* ОСНОВНОЙ РЕДАКТОР */
        #visual-editor {
            min-height: 500px;
            background-color: #121214 !important;
            color: #e1e1e6 !important;
            padding: 25px;
            border: 1px solid #29292e;
            border-radius: 8px;
            outline: none;
        }

        /* СТИЛИ БЛОКОВ ВНУТРИ РЕДАКТОРА */
        #visual-editor .wp-section-title {
            color: #ff7700 !important;
            border-bottom: 2px solid #ff7700 !important;
            padding-bottom: 5px;
            margin-top: 20px;
            font-size: 18px;
            text-transform: uppercase;
        }

        /* Таблицы */
        #visual-editor table, #visual-editor .wp-table-weapon {
            width: 100% !important;
            border-collapse: collapse !important;
            background: #18181c !important;
            margin: 15px 0 !important;
            border: 1px solid #29292e !important;
        }

        #visual-editor th {
            background: #222226 !important;
            color: #ff7700 !important;
            padding: 10px !important;
            text-align: left !important;
            border-bottom: 1px solid #333 !important;
        }

        #visual-editor td {
            padding: 12px !important;
            border-bottom: 1px solid #26262b !important;
            color: #ccc !important;
        }

        /* Плюсы / Минусы */
        #visual-editor .wp-pros-cons-container {
            display: flex !important;
            gap: 15px !important;
            margin: 15px 0 !important;
        }

        #visual-editor .pros-box {
            flex: 1 !important;
            background: #162419 !important;
            border-left: 4px solid #4caf50 !important;
            padding: 12px !important;
            border-radius: 6px !important;
        }

        #visual-editor .cons-box {
            flex: 1 !important;
            background: #2a1818 !important;
            border-left: 4px solid #f44336 !important;
            padding: 12px !important;
            border-radius: 6px !important;
        }

        /* Отряды */
        #visual-editor .wp-team-slots {
            display: flex !important;
            gap: 10px !important;
            margin: 15px 0 !important;
        }

        #visual-editor .wp-slot {
            flex: 1 !important;
            background: #18181c !important;
            border: 1px solid #2a2a30 !important;
            padding: 10px !important;
            text-align: center !important;
            border-radius: 6px !important;
        }

        #visual-editor img {
            max-width: 100%;
            border-radius: 6px;
        }
    </style>
</head>
<body>

<div class="admin-container">
    <h1 style="color: #fff; border-bottom: 2px solid #ff7700; padding-bottom: 10px;">Редактирование поста #<?php echo $post['id']; ?></h1>

    <?php if (isset($_GET['success'])): ?>
        <div style="background: #1b3d2f; color: #4de6a6; padding: 10px; border-radius: 6px; margin-bottom: 15px;">
            Изменения успешно сохранены!
        </div>
    <?php endif; ?>

    <form method="POST" action="edit_post.php?id=<?php echo $id; ?>" onsubmit="return prepareForm();">
        
        <div style="margin-bottom: 15px;">
            <label style="display:block; color:#aaa; margin-bottom:5px;">Заголовок поста:</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($post['title'] ?? ''); ?>" required style="width:100%; padding:10px; background:#121214; border:1px solid #333; color:#fff; border-radius:6px; box-sizing:border-box;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display:block; color:#aaa; margin-bottom:5px;">Игра:</label>
            <?php $currentGame = $post['game_category'] ?? $post['category'] ?? $post['game'] ?? ''; ?>
            <select name="game_category" style="width:100%; padding:10px; background:#121214; border:1px solid #333; color:#fff; border-radius:6px; box-sizing:border-box;">
                <option value="genshin" <?php echo ($currentGame === 'genshin') ? 'selected' : ''; ?>>Genshin Impact</option>
                <option value="wuwa" <?php echo ($currentGame === 'wuwa') ? 'selected' : ''; ?>>Wuthering Waves</option>
                <option value="hsr" <?php echo ($currentGame === 'hsr') ? 'selected' : ''; ?>>Honkai: Star Rail</option>
                <option value="zzz" <?php echo ($currentGame === 'zzz') ? 'selected' : ''; ?>>Zenless Zone Zero</option>
            </select>
        </div>

        <!-- Панель быстрых шаблонов -->
        <div class="template-panel">
            <button type="button" class="template-btn" onclick="insertTemplate('sectionTitle')">+ Заголовок</button>
            <button type="button" class="template-btn" onclick="insertTemplate('weaponTable')">+ Оружие</button>
            <button type="button" class="template-btn" onclick="insertTemplate('artifacts')">+ Артефакты</button>
            <button type="button" class="template-btn" onclick="insertTemplate('teamSlots')">+ Отряд</button>
            <button type="button" class="template-btn" onclick="insertTemplate('prosCons')">+ Плюсы/Минусы</button>
            <button type="button" class="template-btn" onclick="addRow()">+ Строка таблицы</button>
            <button type="button" class="template-btn" onclick="deleteRow()">- Удалить строку</button>
        </div>

        <!-- Поле Visual Editor -->
        <div id="visual-editor" contenteditable="true">
            <?php echo $post['content'] ?? ''; ?>
        </div>

        <input type="hidden" name="content" id="real-content">

        <button type="submit" style="margin-top:20px; background:#ff7700; color:#000; font-weight:bold; border:none; padding:12px 24px; border-radius:6px; cursor:pointer;">
            Сохранить изменения
        </button>
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