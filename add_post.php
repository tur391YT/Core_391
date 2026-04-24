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
    $image = $_POST['image']; // Обложка
    $banner_wide = $_POST['banner_wide'];
    $content = $_POST['content']; 

    $sql = "INSERT INTO posts (title, category, sub_category, image, banner_wide, content) VALUES (?, ?, ?, ?, ?, ?)";
    $pdo->prepare($sql)->execute([$title, $category, $sub_category, $image, $banner_wide, $content]);
    echo "<script>alert('Гайд опубликован!'); window.location.href='index.php';</script>";
}
?>

<style>
    .admin-form-container { max-width: 1100px; margin: 20px auto; background: #0a0a0a; border: 1px solid #1a1a1a; border-radius: 15px; padding: 30px; color: #fff; font-family: 'Segoe UI', sans-serif; }
    .admin-input { background: #151515; border: 1px solid #222; padding: 12px; color: #fff; border-radius: 6px; width: 100%; box-sizing: border-box; }
    .form-group label { color: #ff4d00; font-size: 11px; font-weight: bold; text-transform: uppercase; display: block; margin-bottom: 8px; }
    
    .editor-toolbar { background: #1a1a1a; padding: 10px; display: flex; flex-wrap: wrap; gap: 8px; border: 1px solid #222; border-bottom: none; border-radius: 8px 8px 0 0; position: sticky; top: 0; z-index: 10; }
    .ed-btn { background: #222; color: #fff; border: 1px solid #444; padding: 8px 15px; cursor: pointer; border-radius: 4px; font-size: 12px; font-weight: bold; }
    .btn-main { background: #ff4d00 !important; color: #000 !important; }
    .btn-add { background: #333 !important; border-color: #555 !important; }

    #visual-editor { min-height: 800px; padding: 30px; background: #050505; color: #fff; border: 1px solid #222; border-radius: 0 0 8px 8px; outline: none; line-height: 1.6; }
    #visual-editor h3 { color: #ff4d00; border-left: 4px solid #ff4d00; padding-left: 15px; text-transform: uppercase; margin: 15px 0 20px 0; }
    
    .pros-box { border: 1px solid #143314; background: #0a150a; border-radius: 8px; padding: 15px; flex: 1; }
    .cons-box { border: 1px solid #3d1414; background: #1a0a0a; border-radius: 8px; padding: 15px; flex: 1; }
    .empty-area { min-height: 30px; margin: 10px 0; border: 1px dashed transparent; }
    .empty-area:hover { border-color: #444; }
    
    table { width:100%; border-collapse:collapse; background:#0c0c0c; border:1px solid #222; margin: 15px 0; table-layout: fixed; }
    th { color:#ff4d00; font-size:11px; text-transform:uppercase; border:1px solid #222; padding: 10px; text-align: center; background: #111; }
    td { padding:12px; border:1px solid #222; vertical-align: middle; text-align: center; }
    
    .icon-box { width: 50px; height: 50px; background: #1a1a1a; border: 1px solid #333; border-radius: 6px; margin: 0 auto 8px; overflow: hidden; display: flex; align-items: center; justify-content: center; }
</style>

<main class="main-content">
    <div class="admin-form-container">
        <form action="" method="POST" onsubmit="prepareContent()">
            <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                <div class="form-group" style="grid-column: span 3;">
                    <label>Название статьи</label>
                    <input type="text" name="title" class="admin-input" required>
                </div>
                <div class="form-group">
                    <label>Обложка (URL)</label>
                    <input type="text" name="image" class="admin-input" placeholder="https://...">
                </div>
                <div class="form-group"><label>Игра</label>
                    <select name="category" class="admin-input"><option value="wuwa">Wuthering Waves</option></select>
                </div>
                <div class="form-group"><label>Тип</label>
                    <input type="text" name="sub_category" class="admin-input" value="БИЛД">
                </div>
            </div>

            <div class="form-group">
                <label>Контент гайда</label>
                <div class="editor-toolbar">
                    <button type="button" onclick="applyWuWaTemplate()" class="ed-btn btn-main">📄 ШАБЛОН</button>
                    <button type="button" onclick="insertImg()" class="ed-btn">🖼️ КАРТИНКА</button>
                    <button type="button" onclick="addRow('weapon-table')" class="ed-btn btn-add">+ ОРУЖИЕ</button>
                    <button type="button" onclick="addRow('echo-table')" class="ed-btn btn-add">+ ЭХО</button>
                    <button type="button" onclick="addRow('team-table')" class="ed-btn btn-add">+ КОМАНДА</button>
                </div>
                <div id="visual-editor" contenteditable="true">
                    <div class="empty-area">Нажми "ШАБЛОН"...</div>
                </div>
                <textarea name="content" id="hidden-content" style="display:none;"></textarea>
            </div>

            <button type="submit" class="ed-btn btn-main" style="width:100%; padding:20px; margin-top:20px; font-size: 14px;">ОПУБЛИКОВАТЬ</button>
        </form>
    </div>
</main>

<script>
function applyWuWaTemplate() {
    const template = `
        <div class="empty-area"></div>
        <h3>Описание резонатора</h3>
        <p>Описание...</p>

        <div class="empty-area"></div>
        <h3>Характеристики резонатора</h3>
        <p>Приоритет: Крит. шанс -> Крит. урон -> АТК%</p>

        <div class="empty-area"></div>
        <h3>Преимущества и недостатки резонатора</h3>
        <div style="display: flex; gap: 20px;">
            <div class="pros-box"><b style="color:#2ecc71;">Преимущества:</b><ul><li>Плюс</li></ul></div>
            <div class="cons-box"><b style="color:#e74c3c;">Недостатки:</b><ul><li>Минус</li></ul></div>
        </div>

        <div class="empty-area"></div>
        <h3>Лучшие билды на резонатора в ВуВе</h3>
        <p>Текст...</p>

        <div class="empty-area"></div>
        <h3>Лучшее оружие</h3>
        <table id="weapon-table">
            <thead><tr><th>Оружие</th><th>Редкость</th><th>Эффект</th></tr></thead>
            <tbody><tr>
                <td><div class="icon-box"></div><b>Название</b></td>
                <td style="color:#f1c40f;">⭐⭐⭐⭐⭐</td>
                <td style="text-align:left;">Эффект...</td>
            </tr></tbody>
        </table>

        <div class="empty-area"></div>
        <h3>Лучшие Эхо</h3>
        <table id="echo-table">
            <thead><tr><th>Соната (Set)</th><th>Рекомендуемые Эхо</th></tr></thead>
            <tbody><tr>
                <td><div class="icon-box" style="width:30px; height:30px;"></div>Соната</td>
                <td>
                    <div style="background:#151515; padding:10px; border-radius:8px; display:flex; align-items:center; gap:15px; text-align:left;">
                        <div class="icon-box" style="margin:0;"></div>
                        <div><b>Монстр</b><br><small>Статы</small></div>
                    </div>
                </td>
            </tr></tbody>
        </table>

        <div class="empty-area"></div>
        <h3>Лучшие команды</h3>
        <table id="team-table">
            <thead><tr><th>Мейн-ДД</th><th>Сап-ДД</th><th>Сап-ДД / Саппорт</th><th>Саппорт</th></tr></thead>
            <tbody><tr>
                <td><div class="icon-box"></div><b>Имя</b><br><small style="color:#888;">Роль</small></td>
                <td><div class="icon-box"></div><b>Имя</b><br><small style="color:#888;">Роль</small></td>
                <td><div class="icon-box"></div><b>Имя</b><br><small style="color:#888;">Роль</small></td>
                <td><div class="icon-box"></div><b>Имя</b><br><small style="color:#888;">Роль</small></td>
            </tr></tbody>
        </table>

        <div class="empty-area"></div>
        <h3>Итог</h3>
        <p>Вывод...</p>
        <div class="empty-area"></div>
    `;
    document.getElementById('visual-editor').innerHTML = template;
}

function addRow(tableId) {
    const table = document.getElementById(tableId);
    if (!table) return;
    const tbody = table.querySelector('tbody');
    const newRow = tbody.insertRow();

    if (tableId === 'weapon-table') {
        newRow.innerHTML = `<td><div class="icon-box"></div><b>Название</b></td><td style="color:#f1c40f;">⭐⭐⭐⭐⭐</td><td style="text-align:left;">...</td>`;
    } else if (tableId === 'echo-table') {
        newRow.innerHTML = `<td><div class="icon-box" style="width:30px; height:30px;"></div>...</td><td><div style="background:#151515; padding:10px; border-radius:8px; display:flex; align-items:center; gap:15px; text-align:left;"><div class="icon-box" style="margin:0;"></div><div><b>...</b><br><small>...</small></div></div></td>`;
    } else if (tableId === 'team-table') {
        newRow.innerHTML = `<td><div class="icon-box"></div><b>...</b><br><small style="color:#888;">...</small></td><td><div class="icon-box"></div><b>...</b><br><small style="color:#888;">...</small></td><td><div class="icon-box"></div><b>...</b><br><small style="color:#888;">...</small></td><td><div class="icon-box"></div><b>...</b><br><small style="color:#888;">...</small></td>`;
    }
}

function insertImg() {
    const url = prompt("Ссылка на фото:");
    if(url) document.execCommand('insertHTML', false, `<img src="${url}" style="max-width:100%; border-radius:10px; margin:20px 0; display:block;">`);
}

function prepareContent() {
    document.getElementById('hidden-content').value = document.getElementById('visual-editor').innerHTML;
}
</script>

<?php require_once 'includes/footer.php'; ?>