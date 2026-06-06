<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: login.php');
    exit;
}
require_once 'config/database.php';
require_once 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category']; 
    $sub_category = $_POST['sub_category'];
    $image = $_POST['image'];
    $content = $_POST['content']; 

    $sql = "INSERT INTO posts (title, category, sub_category, image, content) VALUES (?, ?, ?, ?, ?)";
    $pdo->prepare($sql)->execute([$title, $category, $sub_category, $image, $content]);
    echo "<script>alert('Гайд опубликован!'); window.location.href='index.php';</script>";
}
?>

<link rel="stylesheet" href="css/admin-editor.css">
<link rel="stylesheet" href="css/content-styles.css?v=<?= time() ?>">

<main class="main-content" id="admin-main-wrapper">
    <div class="admin-form-container">
        <h2 style="color:#ff4d00; margin-bottom:20px;">📄 Создание нового гайда</h2>
        <form action="" method="POST" onsubmit="prepareContent()">
            <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                <div class="form-group" style="grid-column: span 3;">
                    <label>Название статьи</label>
                    <input type="text" name="title" class="admin-input" required placeholder="Введите название...">
                </div>
                <div class="form-group">
                    <label>Обложка (URL)</label>
                    <input type="text" name="image" class="admin-input" placeholder="https://...">
                </div>
                <div class="form-group"><label>Игра</label>
                    <select name="category" id="game-category-select" class="admin-input" onchange="updateEditorTheme(this.value)">
                        <option value="wuwa">Wuthering Waves</option>
                        <option value="genshin">Genshin Impact</option>
                        <option value="hsr">Honkai: Star Rail</option>
                        <option value="zzz">Zenless Zone Zero</option>
                    </select>
                </div>
                <div class="form-group"><label>Тип</label>
                    <input type="text" name="sub_category" class="admin-input" value="БИЛД">
                </div>
            </div>

            <div class="form-group">
                <label>Конструктор гайда CORE 391</label>
                <div class="editor-toolbar" style="background: #111; padding: 10px; border-radius: 6px; margin-bottom: 10px; display: flex; gap: 8px; flex-wrap: wrap;">
                    <button type="button" onclick="applyGameTemplate()" class="ed-btn btn-main" id="main-template-btn">📄 СГЕНЕРИРОВАТЬ ШАБЛОН</button>
                    <button type="button" onclick="insertSectionTitle()" class="ed-btn">📝 ЗАГЛОВОК</button>
                    <button type="button" onclick="insertImg()" class="ed-btn">🖼️ КАРТИНКА</button>
                    <span style="color: #444; align-self: center;">|</span>
                    <button type="button" onclick="createNewTable('weapon')" class="ed-btn btn-add">📦 СЕКЦИЯ ОРУЖИЯ</button>
                    <button type="button" onclick="addBlockElement('weapon-row')" class="ed-btn" style="background: #222; color: #ff4d00; border: 1px solid #ff4d00;">+ СТРОКУ ОРУЖИЯ</button>
                    <span style="color: #444; align-self: center;">|</span>
                    <button type="button" onclick="createNewTable('echo')" class="ed-btn btn-add">🧬 БЛОК ЭХО/АРТЕФАКТОВ</button>
                    <span style="color: #444; align-self: center;">|</span>
                    <button type="button" onclick="createNewTable('team')" class="ed-btn btn-add">👥 СЕКЦИЯ ОТРЯДА</button>
                    <button type="button" onclick="addBlockElement('team-slot')" class="ed-btn" style="background: #1b3d22; color: #fff; border: 1px solid #4caf50;">+ ПЕРСОНАЖА В ОТРЯД</button>
                    <button type="button" onclick="clearEditor()" class="ed-btn" style="background: #2a0808; color: #ff4a4a; border-color: #5c1313; margin-left: auto;">❌ ОЧИСТИТЬ</button>
                </div>
                
                <div class="entry-content" id="visual-editor" contenteditable="true" style="min-height: 600px; border: 1px solid #333; padding: 30px; background: #070707; color: #fff; outline: none; line-height: 1.6; border-radius: 8px;">
                    <div class="empty-area" style="color: #444; pointer-events: none;">Выберите игру выше и нажмите "СГЕНЕРИРОВАТЬ ШАБЛОН"...</div>
                </div>
                <textarea name="content" id="hidden-content" style="display:none;"></textarea>
            </div>

            <button type="submit" class="ed-btn btn-main" style="width:100%; padding:20px; margin-top:20px; font-weight: bold; text-transform: uppercase;">ОПУБЛИКОВАТЬ ГАЙД</button>
        </form>
    </div>
</main>

<script src="js/editor.js?v=<?= time() ?>"></script>
<script>
    // Инициализация темы при первой загрузке страницы
    updateEditorTheme(document.getElementById('game-category-select').value);
</script>

<?php require_once 'includes/footer.php'; ?>