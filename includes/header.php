<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$body_class = isset($body_class) ? $body_class : 'default';

// СИНХРОНИЗАЦИЯ АВАТАРКИ: 
// Если на странице профиля определена переменная $avatarUrl, берем её. 
// Иначе берем из сессии или ставим дефолт.
$current_avatar = (isset($avatarUrl)) ? $avatarUrl : ($_SESSION['user_avatar'] ?? 'img/avatars/default.jpg');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>CORE 391</title>
    
    <link rel="stylesheet" href="css/style2.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/header-new.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Эти стили исправят "кашу" и сделают шапку ровной */
        header {
            background: #000;
            border-bottom: 1px solid #1a1a1a;
            padding: 10px 0;
            min-height: 70px;
        }
        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }
        .nav-links {
            display: flex;
            list-style: none;
            gap: 20px;
            align-items: center;
            margin: 0;
            padding: 0;
        }
        .nav-links a {
            color: #fff;
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
        }
        .header-profile-box {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255,255,255,0.05);
            padding: 5px 15px;
            border-radius: 20px;
        }
        .header-mini-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ff4d00;
        }
        .header-username {
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
        }
        /* Убираем прыжки при наведении */
        .nav-links a:hover { color: #ff4d00; }
    </style>
</head>

<body class="<?php echo $body_class; ?>">

<header>
    <div class="header-container">
        <a href="index.php" class="logo">
            <img src="img/logo.png" alt="CORE 391" style="height: 40px;">
        </a>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php">ГЛАВНАЯ</a></li>
                <li><a href="category.php?game=genshin">GENSHIN IMPACT</a></li>
                <li><a href="category.php?game=zzz">ZENLESS ZONE ZERO</a></li>
                <li><a href="category.php?game=hsr">HONKAI STAR RAIL</a></li>
                <li><a href="category.php?game=wuwa">WUTHERING WAVES</a></li>
                
                <li class="header-user-section">
                    <?php if (isset($_SESSION['user_name'])): ?>
                        <a href="profile.php?u=<?php echo $_SESSION['user_name']; ?>" class="header-profile-box">
                            <img src="<?php echo $current_avatar; ?>" alt="Ava" class="header-mini-avatar">
                            <span class="header-username"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                        </a>
                    <?php else: ?>
                        <div class="auth-guest-links">
                            <a href="login.php" class="nav-auth-link">ВХОД</a>
                        </div>
                    <?php endif; ?>
                </li>
            </ul>
        </nav>
    </div>
</header>