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

// Очистка старого контента из базы от инлайновых стилей
$clean_content = $post['content'];
$clean_content = preg_replace('/style="[^"]*background:[^"]*#[^"]*"/', '', $clean_content);
$clean_content = preg_replace('/style="[^"]*color:[^"]*#000[^"]*"/', '', $clean_content);
?>

<link rel="stylesheet" href="css/admin-editor.css">
<link rel="stylesheet" href="css/content-styles.css?v=<?= time() ?>">

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
                        <option value="zzz" <?= $post['category'] == 'zzz' ? 'selected' : '' ?>>Zenless Zone Zero</option>
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
                    <button type="button" onclick="createNewTable('echo')" class="ed-btn btn-add">🧬 БЛОК ЭХО/АРТЕФАКТОВ</button>
                    <span style="color: #444; align-self: center;">|</span>
                    <button type="button" onclick="createNewTable('team')" class="ed-btn btn-add">👥 СЕКЦИЯ ОТРЯДА</button>
                    <button type="button" onclick="addBlockElement('team-slot')" class="ed-btn" style="background: #1b3d22; color: #fff; border: 1px solid #4caf50;">+ ПЕРСОНАЖА В ОТРЯД</button>
                    <button type="button" onclick="clearEditor()" class="ed-btn" style="background: #2a0808; color: #ff4a4a; border-color: #5c1313; margin-left: auto;">❌ ОЧИСТИТЬ</button>
                </div>
                
                <div class="entry-content" id="visual-editor" contenteditable="true" style="min-height: 600px; border: 1px solid #333; padding: 30px; background: #070707; color: #fff; outline: none; line-height: 1.6; border-radius: 8px;">
                    <?= $clean_content ?>
                </div>
                <textarea name="content" id="hidden-content" style="display:none;"></textarea>
            </div>

            <button type="submit" class="ed-btn btn-main" style="width:100%; padding:20px; margin-top:20px; font-weight: bold; text-transform: uppercase;">СОХРАНИТЬ ИЗМЕНЕНИЯ</button>
        </form>
    </div>
</main>

<script>
// Полная зеркальная JS-логика для бесшовного редактирования
function updateEditorTheme(game) {
    const wrapper = document.getElementById('admin-main-wrapper');
    if (game === 'wuwa') wrapper.classList.add('theme-wuwa');
    else wrapper.classList.remove('theme-wuwa');
}

function clearEditor() {
    if(confirm("Полностью очистить редактор? Все изменения будут потеряны.")) {
        document.getElementById('visual-editor').innerHTML = '<p><br></p>';
    }
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
                                <div class="wp-echo-stats">Часы / 4-Cost: <span style="color:#aaa; font-weight:normal;">АТК% / Криты</span></div>
                                <div class="wp-echo-stats" style="margin-top:8px;">Кубок / 3-Cost: <span style="color:#aaa; font-weight:normal;">Элем. Урон</span></div>
                                <div class="wp-echo-stats" style="margin-top:8px;">Шапка / 1-Cost: <span style="color:#aaa; font-weight:normal;">АТК%</span></div>
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
                        <th style="width: 35%;">Компоновка группы</th>
                        <th>Описание синергии и тактика</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 10px; background: #090909; vertical-align: middle;">
                            <div class="wp-team-slots">
                                <div class="wp-slot" contenteditable="false">
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

function addBlockElement(type) {
    const selection = window.getSelection();
    if (!selection.rangeCount) {
        alert("Поставьте курсор внутрь нужной таблицы или блока!");
        return;
    }

    const range = selection.getRangeAt(0);
    const container = range.commonAncestorContainer.nodeType === 3 ? range.commonAncestorContainer.parentNode : range.commonAncestorContainer;

    if (type === 'team-slot') {
        const teamSlots = container.closest('.wp-team-slots');
        if (!teamSlots) {
            alert("Ошибка: Поставьте курсор в поле отряда рядом с другими персонажами!");
            return;
        }

        const name = prompt("Имя персонажа:", "Новый персонаж");
        if (!name) return;

        const roleInput = prompt("Выберите роль цифрой:\n1 - Мейн-ДД (main-dd)\n2 - Сап-ДД (sub-dd)\n3 - Саппорт (support)\n4 - Саппорт/Хил (heal)", "4");
        if (!roleInput) return;

        let imgUrl = prompt("URL аватарки персонажа (оставьте пустым для заглушки):");
        if (!imgUrl || imgUrl.trim() === "") {
            imgUrl = "https://placehold.co/65";
        }

        let roleTitle = "Саппорт/Хил";
        let roleClass = "heal";
        
        if (roleInput === '1' || roleInput === 'main-dd') {
            roleTitle = "Мейн-ДД";
            roleClass = "main-dd";
        } else if (roleInput === '2' || roleInput === 'sub-dd') {
            roleTitle = "Сап-ДД";
            roleClass = "sub-dd";
        } else if (roleInput === '3' || roleInput === 'support') {
            roleTitle = "Саппорт";
            roleClass = "support";
        }

        const slotHtml = `
            <div class="wp-slot" contenteditable="false">
                <span class="wp-slot-role ${roleClass}">${roleTitle}</span>
                <img src="${imgUrl}" class="wp-avatar-img">
                <span class="wp-slot-name">${name}</span>
            </div>`;
        
        const template = document.createElement('template');
        template.innerHTML = slotHtml.trim();
        teamSlots.appendChild(template.content.firstChild);
    } 
    
    else if (type === 'weapon-row') {
        const tbody = container.closest('.wp-table-weapon tbody');
        if (!tbody) {
            alert("Ошибка: Поставьте курсор внутрь таблицы оружия!");
            return;
        }

        const name = prompt("Название оружия:", "Альтернативное оружие");
        if (!name) return;

        const stars = prompt("Звезды (например: ⭐⭐⭐⭐):", "⭐⭐⭐⭐");
        let imgUrl = prompt("URL иконки оружия:");
        if (!imgUrl || imgUrl.trim() === "") {
            imgUrl = "https://placehold.co/70";
        }

        const rowHtml = `
            <tr>
                <td class="wp-cell-center">
                    <img src="${imgUrl}" class="wp-avatar-img">
                    <div class="wp-item-name">${name}</div>
                    <div class="wp-stars">${stars}</div>
                    <div class="wp-item-sub">Базовые параметры</div>
                </td>
                <td class="wp-cell-effect">
                    <p>Описание пассивного эффекта этого оружия...</p>
                </td>
            </tr>`;

        const template = document.createElement('template');
        template.innerHTML = rowHtml.trim();
        tbody.appendChild(template.content.firstChild);
    }
}

function insertImg() {
    const url = prompt("Прямая ссылка на изображение:");
    if(url) insertHtmlAtCursor(`<img src="${url}" alt="Медиа"><p><br></p>`);
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
                
                const template = document.createElement('template');
                template.innerHTML = html.trim();
                const frag = template.content;
                
                const lastNode = frag.lastChild;
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
    const editor = document.getElementById('visual-editor');
    const clone = editor.cloneNode(true);
    
    clone.querySelectorAll('div:not(.wp-slot):not(.wp-team-slots):not(.wp-table-wrapper)').forEach(el => {
        if (el.innerHTML.trim() === "" || el.innerHTML === "<br>") {
            el.remove();
        }
    });

    document.getElementById('hidden-content').value = clone.innerHTML;
}
</script>

<?php require_once 'includes/footer.php'; ?>