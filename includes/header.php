<?php 
// 1. ОБЯЗАТЕЛЬНО запускаем сессию в самом начале, иначе кнопка "+" не появится
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. ОПРЕДЕЛЯЕМ ТЕМУ (если переменная не задана в основном файле, ставим 'default')
$body_class = isset($body_class) ? $body_class : 'default';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>CORE 391</title>
    <link rel="stylesheet" href="css/header-new.css?v=3">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="<?php echo $body_class; ?>">

<header>
    <div class="header-container">
        <a href="index.php" class="logo">
            <img src="img/logo.png" alt="CORE 391">
        </a>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php">ГЛАВНАЯ</a></li>
                <li><a href="category.php?game=genshin">GENSHIN IMPACT</a></li>
                <li><a href="category.php?game=zzz">ZENLESS ZONE ZERO</a></li>
                <li><a href="category.php?game=hsr">HONKAI STAR RAIL</a></li>
                <li><a href="category.php?game=wuwa">WUTHERING WAVES</a></li>
                
                <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
                    <li class="admin-controls">
                        <a href="admin_panel.php" class="btn-create" title="Создать гайд">
                            <i class="fas fa-plus-circle"></i> + СОЗДАТЬ
                        </a>
                    </li>
                <?php endif; ?>

                <li><a href="profile.php" class="profile-link">ПРОФИЛЬ</a></li>
            </ul>
        </nav>
    </div>
</header>