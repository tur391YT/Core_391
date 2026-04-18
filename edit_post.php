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

<main class="main-content" style="background: #000; padding-top: 100px; min-height: 100vh;">
    <div style="max-width: 1100px; margin: 0 auto; background: #111; padding: 40px; border-radius: 15px; border: 1px solid #222;">
        <h2 style="color: #ff4d00; font-family: 'Unbounded', sans-serif; margin-bottom: 30px; text-transform: uppercase;">
            Полное редактирование поста #<?= $id ?>
        </h2>

        <form method="POST" id="editForm">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 30px;">
                <div>
                    <label style="color: #666; display: block; margin-bottom: 10px;">Название поста</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" style="width: 100%; padding: 15px; background: #1a1a1a; border: 1px solid #333; color: #fff; border-radius: 8px;">
                </div>
                <div>
                    <label style="color: #666; display: block; margin-bottom: 10px;">Раздел игры</label>
                    <select name="category" style="width: 100%; padding: 15px; background: #1a1a1a; border: 1px solid #333; color: #fff; border-radius: 8px;">
                        <option value="zzz" <?= $post['category'] == 'zzz' ? 'selected' : '' ?>>Zenless Zone Zero</option>
                        <option value="genshin" <?= $post['category'] == 'genshin' ? 'selected' : '' ?>>Genshin Impact</option>
                        <option value="hsr" <?= $post['category'] == 'hsr' ? 'selected' : '' ?>>Honkai Star Rail</option>
                        <option value="wuwa" <?= $post['category'] == 'wuwa' ? 'selected' : '' ?>>Wuthering Waves</option>
                    </select>
                </div>
                <div>
                    <label style="color: #666; display: block; margin-bottom: 10px;">Тип (МЕТКА)</label>
                    <input type="text" name="sub_category" value="<?= htmlspecialchars($post['sub_category']) ?>" style="width: 100%; padding: 15px; background: #1a1a1a; border: 1px solid #333; color: #fff; border-radius: 8px;">
                </div>
                <div>
                    <label style="color: #666; display: block; margin-bottom: 10px;">Превью (URL)</label>
                    <input type="text" name="image" value="<?= htmlspecialchars($post['image']) ?>" style="width: 100%; padding: 15px; background: #1a1a1a; border: 1px solid #333; color: #fff; border-radius: 8px;">
                </div>
            </div>

            <div style="margin-bottom: 30px;">
                <label style="color: #666; display: block; margin-bottom: 10px;">Широкий баннер фона (URL)</label>
                <input type="text" name="banner_wide" value="<?= htmlspecialchars($post['banner_wide']) ?>" style="width: 100%; padding: 15px; background: #1a1a1a; border: 1px solid #333; color: #fff; border-radius: 8px;">
            </div>

            <div style="margin-bottom: 30px;">
                <div class="custom-editor-box" style="border: 1px solid #333; border-radius: 10px; overflow: hidden;">
                    <div class="editor-toolbar" style="background: #222; padding: 10px; display: flex; gap: 8px; border-bottom: 1px solid #333; flex-wrap: wrap;">
                        <button type="button" onclick="formatDoc('bold')" class="ed-btn"><b>B</b></button>
                        <button type="button" onclick="formatDoc('italic')" class="ed-btn"><i>I</i></button>
                        <button type="button" onclick="formatDoc('insertUnorderedList')" class="ed-btn">• Список</button>
                        <button type="button" onclick="addImage()" class="ed-btn">🖼️ Картинка</button>
                        <button type="button" onclick="resizeImage()" class="ed-btn" style="border-color: #ff4d00;">↔️ Размер фото</button>
                        <button type="button" onclick="insertBuildTable()" class="ed-btn" style="background: #ff4d00; color: #000; border: none;">+ ТАБЛИЦА БИЛДА</button>
                    </div>
                    <div id="visual-editor" contenteditable="true" style="width: 100%; min-height: 500px; padding: 25px; background: #151515; color: #fff; outline: none; line-height: 1.6;">
                        <?= $post['content'] ?>
                    </div>
                </div>
                <textarea name="content" id="hidden-content" style="display:none;"></textarea>
            </div>

            <div style="display: flex; gap: 20px;">
                <button type="submit" style="flex: 2; background: #ff4d00; color: #000; padding: 20px; border: none; border-radius: 10px; font-weight: bold; cursor: pointer; text-transform: uppercase;">Сохранить всё</button>
                <a href="post.php?id=<?= $id ?>" style="flex: 1; display: flex; align-items: center; justify-content: center; background: #222; color: #fff; border-radius: 10px; text-decoration: none; font-weight: bold;">Отмена</a>
            </div>
        </form>
    </div>
</main>

<div id="imageResizerPanel" style="display:none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #1a1a1a; padding: 25px; border: 2px solid #ff4d00; border-radius: 15px; z-index: 10000; width: 320px; box-shadow: 0 0 40px rgba(0,0,0,0.9);">
    <div style="color: #fff; margin-bottom: 20px; font-weight: bold; text-align: center;">Размер: <span id="sizeValue" style="color: #ff4d00;">100</span>%</div>
    <input type="range" id="sizeSlider" min="10" max="100" value="100" style="width: 100%; cursor: pointer; accent-color: #ff4d00; margin-bottom: 20px;">
    <div style="display: flex; gap: 10px;">
        <button onclick="closeResizer()" style="flex: 1; padding: 12px; background: #333; color: #fff; border: none; border-radius: 8px; cursor: pointer;">ОТМЕНА</button>
        <button onclick="applySize()" style="flex: 1; padding: 12px; background: #ff4d00; color: #000; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">ГОТОВО</button>
    </div>
</div>
<div id="resizerOverlay" onclick="closeResizer()" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.8); z-index: 9999;"></div>

<style>
    .custom-editor-box .ed-btn { background: #333; color: #fff; border: 1px solid #444; padding: 7px 14px; cursor: pointer; border-radius: 4px; font-size: 14px; transition: 0.2s; }
    .custom-editor-box .ed-btn:hover { background: #444; border-color: #ff4d00; }
</style>

<script>
    document.getElementById('editForm').onsubmit = function() { syncEditor(); };
</script>

<?php require_once 'includes/footer.php'; ?>