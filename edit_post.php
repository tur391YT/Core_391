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
    $banner_wide = $_POST['banner_wide'];
    $content = $_POST['content'];

    $update_sql = "UPDATE posts SET title = ?, category = ?, sub_category = ?, image = ?, banner_wide = ?, content = ? WHERE id = ?";
    $pdo->prepare($update_sql)->execute([$title, $category, $sub_category, $image, $banner_wide, $content, $id]);

    echo "<script>alert('Гайд обновлен!'); window.location.href='post.php?id=$id';</script>";
}
?>

<script src="js/editor.js"></script>

<style>
    /* Копируем те же стили из add_post для таблицы */
    .admin-form-container { max-width: 1100px; margin: 40px auto; background: #0a0a0a; border: 1px solid #1a1a1a; border-radius: 15px; padding: 30px; }
    .form-header { border-bottom: 2px solid #ff4d00; padding-bottom: 15px; margin-bottom: 30px; }
    .input-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px; }
    .form-group { display: flex; flex-direction: column; gap: 8px; color: #fff; }
    .admin-input { background: #151515; border: 1px solid #222; padding: 12px; color: #fff; border-radius: 6px; }
    .submit-btn { background: #ff4d00; color: #000; padding: 18px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; width: 100%; text-transform: uppercase; }
    .ed-btn { background: #222; color: #fff; border: 1px solid #333; padding: 8px 15px; cursor: pointer; border-radius: 4px; }
    #visual-editor table { width: 100%; border-collapse: collapse; margin: 20px 0; border: 1px solid #333; }
    #visual-editor td { padding: 15px; border: 1px solid #222; }
</style>

<main class="main-content">
    <div class="admin-form-container">
        <div class="form-header">
            <h2 style="color:#fff;">⚙️ Редактирование: <?= htmlspecialchars($post['title']) ?></h2>
        </div>

        <form action="" method="POST" id="editForm" onsubmit="prepareContent()">
            <div class="input-grid">
                <div class="form-group" style="grid-column: span 2;">
                    <label style="color: #ff4d00;">Заголовок</label>
                    <input type="text" name="title" class="admin-input" value="<?= htmlspecialchars($post['title']) ?>" required>
                </div>
                </div>

            <div class="form-group">
                <label style="color: #ff4d00;">Контент</label>
                <div style="background: #080808; border: 1px solid #222; border-radius: 8px;">
                    <div class="editor-toolbar" style="background: #1a1a1a; padding: 10px; display: flex; gap: 8px; border-bottom: 1px solid #333;">
                        <button type="button" onclick="formatDoc('bold')" class="ed-btn"><b>B</b></button>
                        <button type="button" onclick="formatDoc('italic')" class="ed-btn"><i>I</i></button>
                        <button type="button" onclick="addImage()" class="ed-btn">🖼️ Фото</button>
                        <button type="button" onclick="insertBuildTable()" class="btn-insert-table ed-btn" style="background: #ff4d00; color:#000; border:none; font-weight:bold;">+ БИЛД</button>
                    </div>
                    <div id="visual-editor" contenteditable="true" style="min-height: 500px; padding: 25px; color: #fff; outline: none;">
                        <?= $post['content'] ?>
                    </div>
                </div>
                <textarea name="content" id="hidden-content" style="display:none;"></textarea>
            </div>

            <button type="submit" class="submit-btn">Сохранить изменения</button>
        </form>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>