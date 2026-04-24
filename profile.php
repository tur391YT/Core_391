<?php
session_start();
require_once 'config/database.php';

$user_get = $_GET['u'] ?? null;

if ($user_get) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$user_get]);
    $user = $stmt->fetch();

    if ($user) {
        $userId = $user['id'];
        $userName = $user['username'];
        $userStatus = $user['status'] ?? "CONTENT CREATOR";
        $regDate = isset($user['reg_date']) ? date("d.m.Y", strtotime($user['reg_date'])) : "23.04.2026";
        $avatarUrl = !empty($user['avatar']) ? $user['avatar'] : "img/avatars/default.jpg";
        $bannerUrl = !empty($user['banner']) ? $user['banner'] : "img/banners/default.jpg";
        
        $b_y = $user['banner_pos_y'] ?? 50;
        $a_x = $user['avatar_pos_x'] ?? 0;
        $a_y = $user['avatar_pos_y'] ?? 0;

        // Синхронизация: если это наш профиль, обновляем аватар в сессии для шапки
        if (isset($_SESSION['user_name']) && $_SESSION['user_name'] === $userName) {
            $_SESSION['user_avatar'] = $avatarUrl;
        }
    } else {
        die("Пользователь не найден");
    }
} else {
    if (isset($_SESSION['user_name'])) {
        header("Location: profile.php?u=" . $_SESSION['user_name']);
        exit();
    } else {
        header("Location: login.php");
        exit();
    }
}

$isOwner = (isset($_SESSION['user_name']) && $_SESSION['user_name'] === $userName);
include 'includes/header.php'; 
?>

<style>
    .profile-header-card { min-height: 350px !important; background: #0a0a0a; }
    .profile-banner { height: 240px !important; background-color: #0a0a0a !important; }
    .avatar-stack { width: 140px; height: 140px; }
    :root {
        --banner-y: <?php echo $b_y; ?>%;
        --av-x: <?php echo $a_x; ?>px;
        --av-y: <?php echo $a_y; ?>px;
    }
</style>

<link rel="stylesheet" href="css/profile.css?v=<?php echo time(); ?>">

<main class="main-content">
    <div class="profile-layout">
        
        <div class="profile-header-card">
            <div class="profile-banner" style="background-image: url('<?php echo $bannerUrl; ?>');"></div>
            <div class="header-info-row">
                <div class="avatar-stack">
                    <img src="<?php echo $avatarUrl; ?>" alt="Avatar" class="profile-avatar">
                    <div class="user-level-badge">25</div>
                </div>
                
                <div class="user-info-text">
                    <div class="name-row" style="display: flex; align-items: center; gap: 8px;">
                        <h2 style="margin:0;"><?php echo htmlspecialchars($userName); ?></h2>
                        <span class="gender-icon" style="font-size: 1.2rem; color: #ff4d00;">
                            <?php echo (isset($user['gender']) && $user['gender'] == 'female') ? '♀' : '♂'; ?>
                        </span>
                        <?php if (!empty($user['special_icon'])): ?>
                            <img src="img/icons/<?php echo $user['special_icon']; ?>.png" class="verify-badge" title="Подтвержденный аккаунт">
                        <?php endif; ?>
                    </div>
                    <div class="status-under-name">
                        <?php echo htmlspecialchars($userStatus); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="profile-grid">
            <div class="main-side">
                <nav class="inner-nav">
                    <button class="nav-btn active" data-tab="overview">Обзор</button>
                    <button class="nav-btn" data-tab="posts">Посты</button>
                    <?php if ($isOwner): ?>
                        <button class="nav-btn" data-tab="settings">Настройки</button>
                    <?php endif; ?>
                </nav>

                <div class="tab-content active" id="overview">
                    <div class="content-block">
                        <h3 class="block-title">Последняя активность</h3>
                        <div class="activity-item">
                            <span class="activity-date">Сегодня</span>
                            <p>Система CORE 391 обновлена. Статус перенесен под никнейм для лучшей читаемости.</p>
                        </div>
                    </div>
                </div>

                <div class="tab-content" id="posts">
                    <div class="content-block">
                        <h3 class="block-title">Ваши публикации</h3>
                        <p style="color: #444;">Здесь пока ничего нет...</p>
                    </div>
                </div>

                <?php if ($isOwner): ?>
                <div class="tab-content" id="settings">
                    <div class="content-block">
                        <h3 class="block-title">Настройки профиля</h3>
                        <form class="settings-form" action="update_profile.php" method="POST" enctype="multipart/form-data">
                            
                            <h4 style="color: #ff4d00; margin-bottom: 10px; font-size: 14px; text-transform: uppercase;">Внешний вид</h4>
                            <div class="form-group">
                                <label>Позиция баннера (Вертикаль)</label>
                                <input type="range" name="banner_y" min="0" max="100" value="<?php echo $b_y; ?>" 
                                       oninput="document.documentElement.style.setProperty('--banner-y', this.value + '%')">
                            </div>

                            <div class="form-group">
                                <label>Смещение аватара (X / Y)</label>
                                <div style="display: flex; gap: 10px;">
                                    <input type="range" name="av_x" min="-100" max="100" value="<?php echo $a_x; ?>" 
                                           oninput="document.documentElement.style.setProperty('--av-x', this.value + 'px')">
                                    <input type="range" name="av_y" min="-50" max="50" value="<?php echo $a_y; ?>" 
                                           oninput="document.documentElement.style.setProperty('--av-y', this.value + 'px')">
                                </div>
                            </div>

                            <h4 style="color: #ff4d00; margin-top: 20px; margin-bottom: 10px; font-size: 14px; text-transform: uppercase;">Данные</h4>
                            <div class="form-group">
                                <label>Никнейм</label>
                                <input type="text" name="nickname" value="<?php echo htmlspecialchars($userName); ?>" style="width: 100%; padding: 8px; background: #111; border: 1px solid #333; color: #fff;">
                            </div>

                            <div class="form-group">
                                <label>Статус</label>
                                <input type="text" name="status" value="<?php echo htmlspecialchars($userStatus); ?>" style="width: 100%; padding: 8px; background: #111; border: 1px solid #333; color: #fff;">
                            </div>

                            <div class="form-group">
                                <label>Пол</label>
                                <select name="gender" style="width: 100%; padding: 8px; background: #111; border: 1px solid #333; color: #fff;">
                                    <option value="male" <?php echo ($user['gender'] ?? '') == 'male' ? 'selected' : ''; ?>>Мужской (♂)</option>
                                    <option value="female" <?php echo ($user['gender'] ?? '') == 'female' ? 'selected' : ''; ?>>Женский (♀)</option>
                                </select>
                            </div>

                            <h4 style="color: #ff4d00; margin-top: 20px; margin-bottom: 10px; font-size: 14px; text-transform: uppercase;">Медиа</h4>
                            <div class="form-group">
                                <label>Загрузить Аватар</label>
                                <input type="file" name="avatar">
                            </div>

                            <div class="form-group">
                                <label>Загрузить Баннер</label>
                                <input type="file" name="banner">
                            </div>

                            <button type="submit" class="btn-look" style="margin-top: 15px; width: 100%;">СОХРАНИТЬ ИЗМЕНЕНИЯ</button>
                        </form>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <aside class="info-side">
                <div class="side-block">
                    <h4 class="block-title">Информация</h4>
                    <ul class="user-details">
                        <li><span>В системе с:</span> <?php echo $regDate; ?></li>
                        </ul>
                </div>
                <?php if ($isOwner): ?>
                    <a href="logout.php" class="logout-btn-simple">Выйти из системы</a>
                <?php endif; ?>
            </aside>
        </div>
    </div>
</main>

<script>
document.querySelectorAll('.nav-btn').forEach(btn => {
    btn.onclick = function() {
        // 1. Убираем активный класс у всех кнопок
        document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
        
        // 2. Скрываем абсолютно все вкладки и убираем у них класс active
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
            tab.style.display = 'none'; 
        });

        // 3. Делаем активной текущую кнопку
        this.classList.add('active');
        
        // 4. Находим нужную вкладку по ID, показываем её и добавляем класс
        const targetId = this.getAttribute('data-tab');
        const activeTab = document.getElementById(targetId);
        
        if (activeTab) {
            activeTab.style.display = 'block';
            // Небольшая задержка для плавности анимации (если она есть в CSS)
            setTimeout(() => {
                activeTab.classList.add('active');
            }, 10);
        }
    };
});
</script>

<?php include 'includes/footer.php'; ?>