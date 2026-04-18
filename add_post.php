<?php
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
    $banner_wide = $_POST['banner_wide'];
    $content = $_POST['content']; 

    $update_sql = "UPDATE posts SET title = ?, category = ?, sub_category = ?, image = ?, banner_wide = ?, content = ? WHERE id = ?";
    $pdo->prepare($update_sql)->execute([$title, $category, $sub_category, $image, $banner_wide, $content, $id]);

    echo "<script>alert('Изменения сохранены!'); window.location.href='post.php?id=$id';</script>";
}
?>

<script src="js/editor.js"></script>

<main class="main-content">
    <div class="post-container-wide" style="background: #111; margin-top: 20px;">
        <h2 style="color: #ff4d00; font-family: 'Inter', sans-serif; margin-bottom: 30px; text-transform: uppercase; font-weight: 900;">
            Редактирование поста #<?= $id ?>
        </h2>

        <form method="POST" id="editForm">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <div>
                    <label style="color: #666; display: block; margin-bottom: 10px; font-size: 12px; font-weight: bold; text-transform: uppercase;">Название поста</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" style="width: 100%; padding: 12px; background: #1a1a1a; border: 1px solid #333; color: #fff; border-radius: 8px;">
                </div>
                <div>
                    <label style="color: #666; display: block; margin-bottom: 10px; font-size: 12px; font-weight: bold; text-transform: uppercase;">Раздел</label>
                    <select name="category" style="width: 100%; padding: 12px; background: #1a1a1a; border: 1px solid #333; color: #fff; border-radius: 8px;">
                        <option value="zzz" <?= $post['category'] == 'zzz' ? 'selected' : '' ?>>Zenless Zone Zero</option>
                        <option value="genshin" <?= $post['category'] == 'genshin' ? 'selected' : '' ?>>Genshin Impact</option>
                        <option value="hsr" <?= $post['category'] == 'hsr' ? 'selected' : '' ?>>Honkai Star Rail</option>
                        <option value="wuwa" <?= $post['category'] == 'wuwa' ? 'selected' : '' ?>>Wuthering Waves</option>
                    </select>
                </div>
                <div>
                    <label style="color: #666; display: block; margin-bottom: 10px; font-size: 12px; font-weight: bold; text-transform: uppercase;">Метка (Тип)</label>
                    <input type="text" name="sub_category" value="<?= htmlspecialchars($post['sub_category']) ?>" style="width: 100%; padding: 12px; background: #1a1a1a; border: 1px solid #333; color: #fff; border-radius: 8px;">
                </div>
                <div>
                    <label style="color: #666; display: block; margin-bottom: 10px; font-size: 12px; font-weight: bold; text-transform: uppercase;">Превью (URL)</label>
                    <input type="text" name="image" value="<?= htmlspecialchars($post['image']) ?>" style="width: 100%; padding: 12px; background: #1a1a1a; border: 1px solid #333; color: #fff; border-radius: 8px;">
                </div>
            </div>

            <div style="margin-bottom: 30px;">
                <label style="color: #666; display: block; margin-bottom: 10px; font-size: 12px; font-weight: bold; text-transform: uppercase;">Содержание</label>
                <div class="editor-wrapper" style="border: 1px solid #333; border-radius: 12px; overflow: hidden;">
                    <div class="editor-toolbar" style="background: #1a1a1a; padding: 10px; display: flex; gap: 8px; border-bottom: 1px solid #333; flex-wrap: wrap;">
                        <button type="button" onclick="formatDoc('bold')" class="ed-btn"><b>B</b></button>
                        <button type="button" onclick="formatDoc('italic')" class="ed-btn"><i>I</i></button>
                        <button type="button" onclick="formatDoc('insertUnorderedList')" class="ed-btn">• Список</button>
                        <button type="button" onclick="addImage()" class="ed-btn">🖼️ Картинка</button>
                        <button type="button" onclick="resizeImage()" class="ed-btn">↔️ Размер</button>
                        <button type="button" onclick="insertBuildTable()" class="ed-btn" style="background: var(--accent); color: #000; border: none; font-weight: bold;">+ ТАБЛИЦА БИЛДА</button>
                    </div>
                    <div id="visual-editor" class="entry-content" contenteditable="true" style="min-height: 600px; padding: 30px; background: #080808; outline: none; overflow-y: auto;">
                        <?= $post['content'] ?>
                    </div>
                </div>
                <textarea name="content" id="hidden-content" style="display:none;"></textarea>
            </div>

            <button type="submit" style="width: 100%; background: var(--accent); color: #000; padding: 20px; border: none; border-radius: 10px; font-weight: 900; cursor: pointer; text-transform: uppercase; font-size: 16px;">Сохранить гайд</button>
        </form>
    </div>
</main>

<div id="imageResizerPanel" style="display:none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #1a1a1a; padding: 25px; border: 2px solid #ff4d00; border-radius: 15px; z-index: 10000; width: 320px; box-shadow: 0 0 40px rgba(0,0,0,0.9);">
    <div style="color: #fff; margin-bottom: 20px; font-weight: bold; text-align: center; text-transform: uppercase;">Размер: <span id="sizeValue" style="color: #ff4d00;">100</span>%</div>
    <input type="range" id="sizeSlider" min="10" max="100" value="100" style="width: 100%; cursor: pointer; accent-color: #ff4d00; margin-bottom: 20px;">
    <div style="display: flex; gap: 10px;">
        <button onclick="closeResizer()" style="flex: 1; padding: 12px; background: #333; color: #fff; border: none; border-radius: 8px; cursor: pointer;">ОТМЕНА</button>
        <button onclick="applySize()" style="flex: 1; padding: 12px; background: #ff4d00; color: #000; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">ГОТОВО</button>
    </div>
</div>
<div id="resizerOverlay" onclick="closeResizer()" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.8); z-index: 9999;"></div>

<style>
    .editor-wrapper .ed-btn { background: #222; color: #fff; border: 1px solid #333; padding: 8px 15px; cursor: pointer; border-radius: 6px; font-size: 13px; font-weight: bold; transition: 0.2s; }
    .editor-wrapper .ed-btn:hover { background: #333; border-color: var(--accent); }
</style>

<script>
    document.getElementById('editForm').onsubmit = function() { syncEditor(); };
</script>

<?php require_once 'includes/footer.php'; ?>