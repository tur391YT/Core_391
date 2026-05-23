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

$is_wuwa = ($post['category'] === 'wuwa');
$theme_class = $is_wuwa ? 'theme-wuwa' : '';
?>

<link rel="stylesheet" href="css/admin-editor.css">
<link rel="stylesheet" href="css/content-styles.css">

<main class="main-content <?= $theme_class ?>" id="admin-main-wrapper">
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
                    <select name="category" id="game-category-select" class="admin-input" onchange="updateEditorTheme(this.value)">
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
                <label>Конструктор гайда CORE 391</label>
                <div class="editor-toolbar" style="background: #111; padding: 10px; border-radius: 6px; margin-bottom: 10px; display: flex; gap: 8px; flex-wrap: wrap;">
                    <button type="button" onclick="insertSectionTitle()" class="ed-btn">📝 ЗАГЛОВОК</button>
                    <button type="button" onclick="insertImg()" class="ed-btn">🖼️ КАРТИНКА</button>
                    <span style="color: #444; align-self: center;">|</span>
                    <button type="button" onclick="createNewTable('weapon')" class="ed-btn btn-add">📦 СЕКЦИЯ ОРУЖИЯ</button>
                    <button type="button" onclick="addBlockElement('weapon-row')" class="ed-btn" style="background: #222; color: #ff4d00; border: 1px solid #ff4d00;">+ СТРОКУ ОРУЖИЯ</button>
                    <span style="color: #444; align-self: center;">|</span>
                    <button type="button" onclick="createNewTable('echo')" class="ed-btn btn-add">🧬 БЛОК ЭХО</button>
                    <span style="color: #444; align-self: center;">|</span>
                    <button type="button" onclick="createNewTable('team')" class="ed-btn btn-add">👥 СЕКЦИЯ ОТРЯДА</button>
                    <button type="button" onclick="addBlockElement('team-slot')" class="ed-btn" style="background: #1b3d22; color: #fff; border: 1px solid #4caf50;">+ ПЕРСОНАЖА В ОТРЯД</button>
                </div>
                
                <div class="entry-content" id="visual-editor" contenteditable="true" style="min-height: 600px; border: 1px solid #333; padding: 30px; background: #070707; color: #fff; outline: none; line-height: 1.6; border-radius: 8px;">
                    <?= $post['content'] ?>
                </div>
                <textarea name="content" id="hidden-content" style="display:none;"></textarea>
            </div>

            <button type="submit" class="ed-btn btn-main" style="width:100%; padding:20px; margin-top:20px;">СОХРАНИТЬ ИЗМЕНЕНИЯ</button>
        </form>
    </div>
</main>

<script>
function updateEditorTheme(game) {
    const wrapper = document.getElementById('admin-main-wrapper');
    if (game === 'wuwa') wrapper.classList.add('theme-wuwa');
    else wrapper.classList.remove('theme-wuwa');
}

function insertSectionTitle() {
    const titleText = prompt("Введите название секции:", "НОВЫЙ РАЗДЕЛ");
    if(titleText) insertHtmlAtCursor(`<h3 class="wp-section-title">${titleText}</h3><p><br></p>`);
}

function createNewTable(type) {
    let html = '';
    if (type === 'weapon') {
        html = `
        <div class="wp-table-wrapper">
            <table class="wp-table-weapon">
                <thead>
                    <tr>
                        <th style="width: 30%;">Оружие</th>
                        <th>Эффект / Характеристики</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="wp-cell-center">
                            <img src="https://placehold.co/70" class="wp-avatar-img">
                            <div class="wp-item-name">Название оружия</div>
                            <div class="wp-stars">⭐⭐⭐⭐⭐</div>
                            <div class="wp-item-sub">Базовые параметры</div>
                        </td>
                        <td class="wp-cell-effect">
                            <p>Описание пассивного эффекта оружия...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>`;
    } else if (type === 'echo') {
        html = `
        <div class="wp-artifacts-container">
            <div class="wp-grid-echo">
                <div class="wp-echo-card-left">
                    <div class="wp-block-header-text">Комплект</div>
                    <div class="wp-echo-meta">
                        <img src="https://placehold.co/60" class="wp-avatar-img circle">
                        <div class="wp-item-name">Название сета</div>
                    </div>
                    <div class="wp-set-desc">2 части: Бонус...<br>4 части: Бонус...</div>
                </div>
                <div class="wp-echo-card-right">
                    <div class="wp-block-header-text">Рекомендуемые основные статы</div>
                    <div class="wp-echo-pool">
                        <div class="wp-echo-item">
                            <div class="wp-echo-info">
                                <div class="wp-echo-stats">Часы: <span style="color:#aaa; font-weight:normal;">АТК% / ВЭ%</span></div>
                                <div class="wp-echo-stats" style="margin-top:8px;">Кубок: <span style="color:#aaa; font-weight:normal;">Элем. Урон</span></div>
                                <div class="wp-echo-stats" style="margin-top:8px;">Шапка: <span style="color:#aaa; font-weight:normal;">Крит. Шанс</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
    } else if (type === 'team') {
        html = `
        <div class="wp-table-wrapper">
            <table class="wp-table-team">
                <thead>
                    <tr>
                        <th style="width: 45%;">Компоновка группы</th>
                        <th>Описание синергии и тактика</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 10px; background: #090909; vertical-align: middle;">
                            <div class="wp-team-slots">
                                <div class="wp-slot">
                                    <span class="wp-slot-role main-dd">Мейн-ДД</span>
                                    <img src="https://placehold.co/65" class="wp-avatar-img">
                                    <span class="wp-slot-name">Персонаж 1</span>
                                </div>
                            </div>
                        </td>
                        <td class="wp-cell-effect">
                            Тактика ведения боя данным отрядом...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>`;
    }
    insertHtmlAtCursor(html + '<p><br></p>');
}

// Контекстное добавление элементов по положению курсора
function addBlockElement(type) {
    const selection = window.getSelection();
    if (!selection.rangeCount) {
        alert("Поставьте курсор внутрь нужной таблицы отряда или оружия!");
        return;
    }

    const range = selection.getRangeAt(0);
    const container = range.commonAncestorContainer.nodeType === 3 ? range.commonAncestorContainer.parentNode : range.commonAncestorContainer;

    if (type === 'team-slot') {
        // Ищем ближайший контейнер слотов отряда
        const teamSlots = container.closest('.wp-team-slots');
        if (!teamSlots) {
            alert("Ошибка: Поставьте курсор в черное поле отряда рядом с персонажами!");
            return;
        }

        const role = prompt("Введите роль (main-dd, sub-dd, support):", "sub-dd");
        const name = prompt("Имя персонажа:", "Новый персонаж");
        let roleTitle = "Сап-ДД";
        if (role === 'main-dd') roleTitle = "Мейн-ДД";
        if (role === 'support') roleTitle = "Саппорт";

        const slotHtml = `
            <div class="wp-slot">
                <span class="wp-slot-role ${role}">${roleTitle}</span>
                <img src="https://placehold.co/65" class="wp-avatar-img">
                <span class="wp-slot-name">${name}</span>
            </div>`;
        
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = slotHtml.trim();
        teamSlots.appendChild(tempDiv.firstChild);
    } 
    
    else if (type === 'weapon-row') {
        // Ищем тело таблицы оружия
        const tbody = container.closest('.wp-table-weapon tbody');
        if (!tbody) {
            alert("Ошибка: Поставьте курсор внутрь таблицы оружия!");
            return;
        }

        const name = prompt("Название оружия:", "Альтернативное оружие");
        const stars = prompt("Звезды (например: ⭐⭐⭐⭐):", "⭐⭐⭐⭐");

        const rowHtml = `
            <tr>
                <td class="wp-cell-center">
                    <img src="https://placehold.co/70" class="wp-avatar-img">
                    <div class="wp-item-name">${name}</div>
                    <div class="wp-stars">${stars}</div>
                    <div class="wp-item-sub">Базовые параметры</div>
                </td>
                <td class="wp-cell-effect">
                    <p>Описание пассивного эффекта этого оружия...</p>
                </td>
            </tr>`;

        const tempTable = document.createElement('table');
        tempTable.innerHTML = `<tbody>${rowHtml.trim()}</tbody>`;
        tbody.appendChild(tempTable.querySelector('tr'));
    }
}

function insertImg() {
    const url = prompt("Прямая ссылка на фото:");
    if(url) insertHtmlAtCursor(`<img src="${url}" alt="Медиа" style="max-width:100%; border-radius:8px;"><p><br></p>`);
}

function insertHtmlAtCursor(html) {
    const editor = document.getElementById('visual-editor');
    editor.focus();
    if (window.getSelection) {
        const sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
            let range = sel.getRangeAt(0);
            if (editor.contains(range.commonAncestorContainer)) {
                range.deleteContents();
                const el = document.createElement("div");
                el.innerHTML = html;
                const frag = document.createDocumentFragment();
                let node, lastNode;
                while ((node = el.firstChild)) {
                    lastNode = frag.appendChild(node);
                }
                range.insertNode(frag);
                if (lastNode) {
                    range = range.cloneRange();
                    range.setStartAfter(lastNode);
                    range.collapse(true);
                    sel.removeAllRanges();
                    sel.addRange(range);
                }
                return;
            }
        }
    }
    editor.innerHTML += html;
}

function prepareContent() {
    document.getElementById('hidden-content').value = document.getElementById('visual-editor').innerHTML;
}
</script>
<?php require_once 'includes/footer.php'; ?>