<?php
session_start(); // Запускаем сессию для отслеживания загруженной аватарки

// Полная тишина для ошибок, чтобы не ломать верстку
error_reporting(0);
ini_set('display_errors', 0);

$root = $_SERVER['DOCUMENT_ROOT'] . '/Core_391/';
$headerPath = $root . 'includes/header-new.php';

if (file_exists($headerPath)) {
    include $headerPath;
} else {
    echo '<!DOCTYPE html><html lang="ru" style="background:#050505;"><head><meta charset="UTF-8">';
    echo '<link rel="stylesheet" href="css/style2.css">';
    echo '</head><body style="margin:0; padding:0; background:#050505; color:#fff;">';
}

$userName = "TUR391";
$userStatus = "CONTENT CREATOR";
$regDate = "21.04.2026";

// Динамическая проверка: берем из сессии или ставим дефолт
$avatarUrl = isset($_SESSION['user_avatar']) ? $_SESSION['user_avatar'] : "img/avatars/default.jpg"; 
?>

<link rel="stylesheet" href="css/profile.css?v=<?php echo time(); ?>">

<main class="main-content">
    <div class="profile-container">
        <aside class="profile-sidebar">
            <div class="avatar-wrapper">
                <img src="<?php echo $avatarUrl; ?>" alt="User Avatar" class="profile-avatar" 
                     onerror="this.src='https://cdn-icons-png.flaticon.com/512/149/149071.png'">
            </div>
            <span class="user-status"><?php echo $userStatus; ?></span>
            
            <nav class="profile-nav">
                <button type="button" class="nav-btn active" data-tab="overview">Обзор</button>
                <button type="button" class="nav-btn" data-tab="settings">Настройки</button>
                <a href="index.php" class="nav-btn">На главную</a>
            </nav>
        </aside>

        <section class="profile-info">
            <div class="profile-header">
                <h2><?php echo $userName; ?></h2>
                <p class="reg-date">В системе с: <?php echo $regDate; ?></p>
            </div>

            <div class="tab-content active" id="overview">
                <div class="profile-stats">
                    <div class="stat-card">
                        <span>12</span>
                        <label>Гайдов</label>
                    </div>
                    <div class="stat-card">
                        <span>450</span>
                        <label>Просмотров</label>
                    </div>
                </div>

                <div class="recent-activity">
                    <h3 class="section-title">Последняя активность</h3>
                    <div class="activity-list">
                        <div class="activity-item">Загружен новый гайд по Genshin Impact</div>
                        <div class="activity-item">Настройка системы CORE 391 завершена</div>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="settings">
                <h3 class="section-title">Настройки аккаунта</h3>
                <form class="settings-form" action="update_profile.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Ваша аватарка</label>
                        <input type="file" name="avatar" accept="image/*" class="file-input">
                    </div>
                    <div class="form-group">
                        <label>Изменить никнейм</label>
                        <input type="text" name="nickname" placeholder="<?php echo $userName; ?>">
                    </div>
                    <div class="form-group">
                        <label>Новый пароль</label>
                        <input type="password" name="password" placeholder="********">
                    </div>
                    <button type="submit" class="btn-look">СОХРАНИТЬ ИЗМЕНЕНИЯ</button>
                </form>
            </div>
        </section>
    </div>
</main>

<script src="js/profile.js?v=<?php echo time(); ?>"></script>

<?php 
$footerPath = $root . 'includes/footer.php';
if (file_exists($footerPath)) {
    include $footerPath;
} else {
    @include 'includes/footer.php';
}
?>