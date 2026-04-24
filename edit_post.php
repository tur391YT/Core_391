<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: login.php');
    exit;
}
require_once 'config/database.php';
require_once 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) { die("Пост не найден!"); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category']; 
    $sub_category = $_POST['sub_category'];
    $image = $_POST['image'];
    $content = $_POST['content'];

    $update_sql = "UPDATE posts SET title = ?, category = ?, sub_category = ?, image = ?, content = ? WHERE id = ?";
    $pdo->prepare($update_sql)->execute([$title, $category, $sub_category, $image, $content, $id]);
    echo "<script>alert('Гайд обновлен!'); window.location.href='index.php';</script>";
}
?>

<link rel="stylesheet" href="css/admin-editor.css">

<main class="main-content">
    <div class="admin-form-container">
        <h2 style="color:#ff4d00; margin-bottom:20px;">⚙️ Редактирование гайда</h2>
        <form action="" method="POST" onsubmit="prepareContent()">
            <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                <div class="form-group" style="grid-column: span 3;">
                    <label>Название статьи</label>
                    <input type="text" name="title" class="admin-input" value="<?= htmlspecialchars($post['title']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Обложка (URL)</label>
                    <input type="text" name="image" class="admin-input" value="<?= htmlspecialchars($post['image']) ?>">
                </div>
                <div class="form-group"><label>Игра</label>
                    <select name="category" class="admin-input">
                        <option value="wuwa" <?= $post['category'] == 'wuwa' ? 'selected' : '' ?>>Wuthering Waves</option>
                        <option value="genshin" <?= $post['category'] == 'genshin' ? 'selected' : '' ?>>Genshin Impact</option>
                        <option value="hsr" <?= $post['category'] == 'hsr' ? 'selected' : '' ?>>Honkai: Star Rail</option>
                    </select>
                </div>
                <div class="form-group"><label>Тип</label>
                    <input type="text" name="sub_category" class="admin-input" value="<?= htmlspecialchars($post['sub_category']) ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Редактирование контента</label>
                <div class="editor-toolbar">
                    <button type="button" onclick="insertImg()" class="ed-btn">🖼️ КАРТИНКА</button>
                    <button type="button" onclick="addRow('weapon')" class="ed-btn btn-add">+ ОРУЖИЕ</button>
                    <button type="button" onclick="addRow('echo')" class="ed-btn btn-add">+ ЭХО</button>
                    <button type="button" onclick="addRow('team')" class="ed-btn btn-add">+ ОТРЯД</button>
                </div>
                <div id="visual-editor" contenteditable="true" style="min-height: 600px; border: 1px solid #333; padding: 30px; background: #000; color: #fff; outline: none; line-height: 1.6;">
                    <?= $post['content'] ?>
                </div>
                <textarea name="content" id="hidden-content" style="display:none;"></textarea>
            </div>

            <button type="submit" class="ed-btn btn-main" style="width:100%; padding:20px; margin-top:20px;">СОХРАНИТЬ ИЗМЕНЕНИЯ</button>
        </form>
    </div>
</main>

<script>
function addRow(type) {
    const editor = document.getElementById('visual-editor');
    editor.focus();

    let html = '';
    if (type === 'weapon') {
        html = `
        <table style="width:100%; border-collapse: collapse; margin: 15px 0; background: #080808; border: 1px solid #1a1a1a;">
            <tr style="background: #000; color: #ff4d00; font-size: 11px; text-transform: uppercase;">
                <th style="border: 1px solid #1a1a1a; padding: 10px; width: 250px;">Оружие</th>
                <th style="border: 1px solid #1a1a1a; padding: 10px; width: 150px;">Редкость</th>
                <th style="border: 1px solid #1a1a1a; padding: 10px;">Эффект</th>
            </tr>
            <tr>
                <td style="border: 1px solid #1a1a1a; padding: 15px; text-align: center; vertical-align: top;">
                    <img src="" style="width: 70px; height: 70px; background: #1a1a1a; border-radius: 5px; border: 1px solid #333; margin-bottom: 10px;">
                    <div style="font-weight: bold; color: #fff; font-size: 14px;">Название</div>
                    <div style="color: #888; font-size: 11px; margin-top: 5px;">Статы...</div>
                </td>
                <td style="border: 1px solid #1a1a1a; padding: 15px; text-align: center; vertical-align: middle; color: #ffb400; font-size: 18px;">⭐⭐⭐⭐⭐</td>
                <td style="border: 1px solid #1a1a1a; padding: 15px; font-size: 13px; color: #ccc; line-height: 1.5; vertical-align: top;">Бонусы...</td>
            </tr>
        </table>`;
    } else if (type === 'echo') {
        html = `
        <table style="width:100%; border-collapse: collapse; margin: 15px 0; border: 1px solid #1a1a1a;">
            <tr style="background: #000; color: #ff4d00; font-size: 11px; text-transform: uppercase;">
                <th style="border: 1px solid #1a1a1a; padding: 10px; width: 40%;">Соната (Set)</th>
                <th style="border: 1px solid #1a1a1a; padding: 10px;">Рекомендуемые Эхо</th>
            </tr>
            <tr>
                <td style="border: 1px solid #1a1a1a; padding: 20px; text-align: center; vertical-align: top;">
                    <img src="" style="width: 40px; height: 40px; background: #1a1a1a; margin-bottom: 10px;">
                    <div style="font-weight: bold; color: #fff;">Название</div>
                    <div style="font-size: 11px; color: #888; margin-top: 8px;">Эффект сета...</div>
                </td>
                <td style="border: 1px solid #1a1a1a; padding: 20px; vertical-align: top; background: #050505;">
                    <div style="display: flex; gap: 20px; align-items: flex-start;">
                        <div style="background: #111; padding: 10px; border-radius: 8px; border: 1px solid #222; width: 120px; text-align: center;">
                            <img src="" style="width: 60px; height: 60px; background: #222; border-radius: 4px;">
                            <div style="font-size: 12px; font-weight: bold; color: #fff; margin-top: 5px;">Имя Эхо</div>
                        </div>
                        <div style="color: #aaa; font-size: 12px; padding-top: 10px;">• Статы...</div>
                    </div>
                </td>
            </tr>
        </table>`;
    } else if (type === 'team') {
        html = `
        <table style="width:100%; border-collapse: collapse; margin: 15px 0; background: #080808; border: 1px solid #1a1a1a;">
            <tr style="background: #000; color: #ff4d00; font-size: 11px; text-transform: uppercase;">
                <th style="border: 1px solid #1a1a1a; padding: 10px; width: 60%;">Персонажи</th>
                <th style="border: 1px solid #1a1a1a; padding: 10px;">Описание команды</th>
            </tr>
            <tr>
                <td style="border: 1px solid #1a1a1a; padding: 20px; text-align: center;">
                    <div style="display: flex; gap: 10px; justify-content: center;">
                        ${['МЕЙН-ДД', 'САП-ДД', 'САППОРТ'].map(role => `
                        <div style="width: 90px; text-align: center;">
                            <div style="font-size: 9px; color: #ff4d00; font-weight: bold;">${role}</div>
                            <img src="" style="width: 65px; height: 65px; background: #1a1a1a; border-radius: 5px; border: 1px solid #333;">
                            <div style="font-size: 12px; font-weight: bold; color: #fff; margin-top: 5px;">Имя</div>
                        </div>`).join('')}
                    </div>
                </td>
                <td style="border: 1px solid #1a1a1a; padding: 15px; vertical-align: top; color: #ccc; font-size: 13px;">Описание...</td>
            </tr>
        </table>`;
    }

    if (!document.execCommand('insertHTML', false, html + '<p><br></p>')) {
        editor.innerHTML += html + '<p><br></p>';
    }
}

function insertImg() {
    const url = prompt("Прямая ссылка на фото:");
    if(url) {
        const editor = document.getElementById('visual-editor');
        editor.focus();
        const imgHtml = `<div style="text-align: center; margin: 20px 0;"><img src="${url}" style="max-width: 100%; height: auto; border-radius: 8px;"></div>`;
        document.execCommand('insertHTML', false, imgHtml + '<p><br></p>');
    }
}

function prepareContent() {
    document.getElementById('hidden-content').value = document.getElementById('visual-editor').innerHTML;
}
</script>

<?php require_once 'includes/footer.php'; ?>