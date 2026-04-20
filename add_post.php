<?php
session_start();
// Проверка админа должна быть ПЕРВОЙ строчкой
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
    $banner_wide = $_POST['banner_wide'];
    $content = $_POST['content']; 

    // Используем INSERT вместо UPDATE для создания новой записи
    $sql = "INSERT INTO posts (title, category, sub_category, image, banner_wide, content) VALUES (?, ?, ?, ?, ?, ?)";
    $pdo->prepare($sql)->execute([$title, $category, $sub_category, $image, $banner_wide, $content]);

    echo "<script>alert('Гайд успешно опубликован!'); window.location.href='index.php';</script>";
}
?>

<script src="js/editor.js"></script>

<main class="main-content">
    <div class="post-container-wide" style="background: #111; margin-top: 20px; padding: 40px; border-radius: 12px; border: 1px solid #333;">
        <h2 style="color: #ff4d00; font-family: 'Inter', sans-serif; margin-bottom: 30px; text-transform: uppercase; font-weight: 900;">
            Создание нового гайда
        </h2>

        <form method="POST" id="editForm">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <div>
                    <label style="color: #666; display: block; margin-bottom: 10px; font-size: 12px; font-weight: bold; text-transform: uppercase;">Название поста</label>
                    <input type="text" name="title" placeholder="Введите название..." style="width: 100%; padding: 12px; background: #1a1a1a; border: 1px solid #333; color: #fff; border-radius: 8px;">
                </div>
                <div>
                    <label style="color: #666; display: block; margin-bottom: 10px; font-size: 12px; font-weight: bold; text-transform: uppercase;">Раздел</label>
                    <select name="category" style="width: 100%; padding: 12px; background: #1a1a1a; border: 1px solid #333; color: #fff; border-radius: 8px;">
                        <option value="zzz">Zenless Zone Zero</option>
                        <option value="genshin">Genshin Impact</option>
                        <option value="hsr">Honkai Star Rail</option>
                        <option value="wuwa">Wuthering Waves</option>
                    </select>
                </div>
                <div>
                    <label style="color: #666; display: block; margin-bottom: 10px; font-size: 12px; font-weight: bold; text-transform: uppercase;">Метка (Тип)</label>
                    <input type="text" name="sub_category" placeholder="Например: Билд" style="width: 100%; padding: 12px; background: #1a1a1a; border: 1px solid #333; color: #fff; border-radius: 8px;">
                </div>
                <div>
                    <label style="color: #666; display: block; margin-bottom: 10px; font-size: 12px; font-weight: bold; text-transform: uppercase;">Превью (URL)</label>
                    <input type="text" name="image" placeholder="Ссылка на картинку..." style="width: 100%; padding: 12px; background: #1a1a1a; border: 1px solid #333; color: #fff; border-radius: 8px;">
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
                        <button type="button" onclick="insertBuildTable()" class="ed-btn" style="background: #ff4d00; color: #000; border: none; font-weight: bold;">+ ТАБЛИЦА БИЛДА</button>
                    </div>
                    <div id="visual-editor" class="entry-content" contenteditable="true" style="min-height: 600px; padding: 30px; background: #080808; outline: none; overflow-y: auto; color: #fff;">
                        </div>
                </div>
                <textarea name="content" id="hidden-content" style="display:none;"></textarea>
            </div>

            <button type="submit" style="width: 100%; background: #ff4d00; color: #000; padding: 20px; border: none; border-radius: 10px; font-weight: 900; cursor: pointer; text-transform: uppercase; font-size: 16px;">Опубликовать</button>
        </form>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>