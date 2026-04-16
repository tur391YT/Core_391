<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CORE 391 | Архив</title>
    <link rel="stylesheet" href="css/style2.css?v=<?= time(); ?>">
</head>
<body class="<?= isset($theme_class) ? $theme_class : '' ?>">

<header>
    <div class="header-container">
        <a href="index.php" class="logo">
            <img src="img/logo.png" alt="CORE 391">
        </a>
        <nav class="nav-menu">
            <a href="index.php">Главная</a>
            <a href="category.php?game=genshin">Genshin Impact</a>
            <a href="category.php?game=zzz">Zenless Zone Zero</a>
            <a href="category.php?game=wuwa">Wuthering Waves</a>
            <a href="edit_post.php" class="add-post-link">Добавить пост</a>
        </nav>
    </div>
</header>