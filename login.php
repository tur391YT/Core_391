<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход | CORE 391</title>
    <link rel="stylesheet" href="css/style2.css">
    <link rel="stylesheet" href="css/auth.css"> </head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <h2>Вход</h2>
            <form action="auth_handler.php?action=login" method="POST">
                <input type="text" name="username" class="auth-input" placeholder="Никнейм" required>
                <input type="password" name="password" class="auth-input" placeholder="Пароль" required>
                <button type="submit" class="auth-btn">ВОЙТИ В СИСТЕМУ</button>
            </form>
            <a href="register.php" class="auth-link">Нет аккаунта? Зарегистрироваться</a>
        </div>
    </div>
</body>
</html>