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
                    <select name="category" class="admin-input">
                        <option value="wuwa">Wuthering Waves</option>
                        <option value="genshin">Genshin Impact</option>
                        <option value="hsr">Honkai: Star Rail</option>
                    </select>
                </div>
                <div class="form-group"><label>Тип</label>
                    <input type="text" name="sub_category" class="admin-input" value="БИЛД">
                </div>
            </div>

            <div class="form-group">
                <label>Контент гайда</label>
                <div class="editor-toolbar">
                    <button type="button" onclick="applyWuWaTemplate()" class="ed-btn btn-main">📄 ШАБЛОН (WUWA)</button>
                    <button type="button" onclick="insertImg()" class="ed-btn">🖼️ КАРТИНКА</button>
                    <button type="button" onclick="addRow('weapon')" class="ed-btn btn-add">+ ОРУЖИЕ</button>
                    <button type="button" onclick="addRow('echo')" class="ed-btn btn-add">+ ЭХО</button>
                    <button type="button" onclick="addRow('team')" class="ed-btn btn-add">+ ОТРЯД</button>
                </div>
                <div id="visual-editor" contenteditable="true" style="min-height: 600px; border: 1px solid #333; padding: 30px; background: #000; color: #fff; outline: none; line-height: 1.6;">
                    <div class="empty-area" style="color: #444;">Нажми "ШАБЛОН"...</div>
                </div>
                <textarea name="content" id="hidden-content" style="display:none;"></textarea>
            </div>

            <button type="submit" class="ed-btn btn-main" style="width:100%; padding:20px; margin-top:20px; font-weight: bold;">ОПУБЛИКОВАТЬ ГАЙД</button>
        </form>
    </div>
</main>

<script>
function applyWuWaTemplate() {
    const template = `
        <h3 style="color: #ff4d00; border-left: 4px solid #ff4d00; padding-left: 15px; text-transform: uppercase;">ОПИСАНИЕ РЕЗОНАТОРА</h3>
        <p>Краткое описание персонажа...</p>
        
        <h3 style="color: #ff4d00; border-left: 4px solid #ff4d00; padding-left: 15px; text-transform: uppercase;">ХАРАКТЕРИСТИКИ РЕЗОНАТОРА</h3>
        <p><b>Приоритет:</b> Крит. шанс -> Крит. урон -> ATK%</p>
        
        <h3 style="color: #ff4d00; border-left: 4px solid #ff4d00; padding-left: 15px; text-transform: uppercase;">ПРЕИМУЩЕСТВА И НЕДОСТАТКИ РЕЗОНАТОРА</h3>
        <div style="display: flex; gap: 20px; margin: 20px 0;">
            <div style="flex: 1; background: #051a05; border: 1px solid #0f300f; padding: 15px; border-radius: 8px;">
                <b style="color: #4CAF50;">Преимущества:</b>
                <ul style="margin: 10px 0; padding-left: 20px; color: #ddd; font-size: 14px;"><li>Плюс 1</li></ul>
            </div>
            <div style="flex: 1; background: #1a0505; border: 1px solid #300f0f; padding: 15px; border-radius: 8px;">
                <b style="color: #f44336;">Недостатки:</b>
                <ul style="margin: 10px 0; padding-left: 20px; color: #ddd; font-size: 14px;"><li>Минус 1</li></ul>
            </div>
        </div>

        <h3 style="color: #ff4d00; border-left: 4px solid #ff4d00; padding-left: 15px; text-transform: uppercase;">ЛУЧШИЕ БИЛДЫ НА РЕЗОНАТОРА В ВУВЕ</h3>
        <p>...</p>

        <h3 style="color: #ff4d00; border-left: 4px solid #ff4d00; padding-left: 15px; text-transform: uppercase;">ЛУЧШЕЕ ОРУЖИЕ</h3>
        <table style="width:100%; border-collapse: collapse; margin: 15px 0; background: #080808; border: 1px solid #1a1a1a;">
            <tr style="background: #000; color: #ff4d00; font-size: 11px; text-transform: uppercase;">
                <th style="border: 1px solid #1a1a1a; padding: 10px; width: 250px;">Оружие</th>
                <th style="border: 1px solid #1a1a1a; padding: 10px; width: 150px;">Редкость</th>
                <th style="border: 1px solid #1a1a1a; padding: 10px;">Эффект</th>
            </tr>
            <tr>
                <td style="border: 1px solid #1a1a1a; padding: 15px; text-align: center; vertical-align: top;">
                    <img src="" style="width: 70px; height: 70px; background: #1a1a1a; border-radius: 5px; border: 1px solid #333; margin-bottom: 10px;">
                    <div style="font-weight: bold; color: #fff; font-size: 14px;">Название оружия</div>
                    <div style="color: #888; font-size: 11px; margin-top: 5px;">Статы...</div>
                </td>
                <td style="border: 1px solid #1a1a1a; padding: 15px; text-align: center; vertical-align: middle; color: #ffb400; font-size: 18px;">⭐⭐⭐⭐⭐</td>
                <td style="border: 1px solid #1a1a1a; padding: 15px; font-size: 13px; color: #ccc; line-height: 1.5; vertical-align: top;">Эффект...</td>
            </tr>
        </table>

        <h3 style="color: #ff4d00; border-left: 4px solid #ff4d00; padding-left: 15px; text-transform: uppercase;">ЛУЧШИЕ ЭХО</h3>
        <table style="width:100%; border-collapse: collapse; margin: 15px 0; border: 1px solid #1a1a1a;">
            <tr style="background: #000; color: #ff4d00; font-size: 11px; text-transform: uppercase;">
                <th style="border: 1px solid #1a1a1a; padding: 10px; width: 40%;">Соната (Set)</th>
                <th style="border: 1px solid #1a1a1a; padding: 10px;">Рекомендуемые Эхо</th>
            </tr>
            <tr>
                <td style="border: 1px solid #1a1a1a; padding: 20px; text-align: center; vertical-align: top;">
                    <img src="" style="width: 40px; height: 40px; background: #1a1a1a; margin-bottom: 10px;">
                    <div style="font-weight: bold; color: #fff;">Название сета</div>
                    <div style="font-size: 11px; color: #888; margin-top: 8px;">2 части: +10%<br>5 частей: +30%</div>
                </td>
                <td style="border: 1px solid #1a1a1a; padding: 20px; vertical-align: top; background: #050505;">
                    <div style="display: flex; gap: 20px; align-items: flex-start;">
                        <div style="background: #111; padding: 10px; border-radius: 8px; border: 1px solid #222; width: 120px; text-align: center;">
                            <img src="" style="width: 60px; height: 60px; background: #222; border-radius: 4px;">
                            <div style="font-size: 12px; font-weight: bold; color: #fff; margin-top: 5px;">Имя Эхо</div>
                            <div style="font-size: 10px; color: #888;">Кш / Ку</div>
                        </div>
                        <div style="color: #aaa; font-size: 12px; padding-top: 10px;">• Статы остальных слотов...</div>
                    </div>
                </td>
            </tr>
        </table>

        <h3 style="color: #ff4d00; border-left: 4px solid #ff4d00; padding-left: 15px; text-transform: uppercase;">ОТРЯДЫ ДЛЯ РЕЗОНАТОРА</h3>
        <table style="width:100%; border-collapse: collapse; margin: 15px 0; background: #080808; border: 1px solid #1a1a1a;">
            <tr style="background: #000; color: #ff4d00; font-size: 11px; text-transform: uppercase;">
                <th style="border: 1px solid #1a1a1a; padding: 10px; width: 60%;">Персонажи</th>
                <th style="border: 1px solid #1a1a1a; padding: 10px;">Описание команды</th>
            </tr>
            <tr>
                <td style="border: 1px solid #1a1a1a; padding: 20px; text-align: center;">
                    <div style="display: flex; gap: 10px; justify-content: center;">
                        <div style="width: 90px; text-align: center;">
                            <div style="font-size: 9px; color: #ff4d00; font-weight: bold;">МЕЙН-ДД</div>
                            <img src="" style="width: 65px; height: 65px; background: #1a1a1a; border-radius: 5px;">
                            <div style="font-size: 12px; font-weight: bold; color: #fff; margin-top: 5px;">Имя</div>
                        </div>
                        <div style="width: 90px; text-align: center;">
                            <div style="font-size: 9px; color: #ff4d00; font-weight: bold;">САП-ДД</div>
                            <img src="" style="width: 65px; height: 65px; background: #1a1a1a; border-radius: 5px;">
                            <div style="font-size: 12px; font-weight: bold; color: #fff; margin-top: 5px;">Имя</div>
                        </div>
                        <div style="width: 90px; text-align: center;">
                            <div style="font-size: 9px; color: #ff4d00; font-weight: bold;">САППОРТ</div>
                            <img src="" style="width: 65px; height: 65px; background: #1a1a1a; border-radius: 5px;">
                            <div style="font-size: 12px; font-weight: bold; color: #fff; margin-top: 5px;">Имя</div>
                        </div>
                    </div>
                </td>
                <td style="border: 1px solid #1a1a1a; padding: 15px; vertical-align: top; color: #ccc; font-size: 13px;">Описание...</td>
            </tr>
        </table>

        <h3 style="color: #ff4d00; border-left: 4px solid #ff4d00; padding-left: 15px; text-transform: uppercase;">ИТОГ</h3>
        <p>Резюме по персонажу...</p>
    `;
    document.getElementById('visual-editor').innerHTML = template;
}

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
                    <div style="color: #888; font-size: 11px; margin-top: 5px;">Атака: 0 | Крит: 0%</div>
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