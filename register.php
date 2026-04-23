<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация | CORE 391</title>
    <link rel="stylesheet" href="css/style2.css"> <link rel="stylesheet" href="css/profile.css"> <style>
        .auth-container { display: flex; justify-content: center; align-items: center; height: 100vh; background: #050505; }
        .auth-box { background: #0a0a0a; padding: 40px; border-radius: 15px; border: 1px solid #333; width: 350px; text-align: center; }
        .auth-box h2 { color: #ff5500; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 2px; }
        .auth-input { width: 100%; padding: 12px; margin-bottom: 15px; background: #111; border: 1px solid #222; color: #fff; border-radius: 5px; }
        .auth-btn { width: 100%; padding: 12px; background: #ff5500; border: none; color: #fff; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .auth-btn:hover { background: #e64d00; box-shadow: 0 0 15px rgba(255, 85, 0, 0.4); }
        .auth-link { color: #888; display: block; margin-top: 15px; text-decoration: none; font-size: 14px; }
        .auth-link:hover { color: #ff5500; }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <h2>Регистрация</h2>
            <form action="auth_handler.php?action=register" method="POST">
                <input type="text" name="username" class="auth-input" placeholder="Введите никнейм" required>
                <input type="password" name="password" class="auth-input" placeholder="Придумайте пароль" required>
                <button type="submit" class="auth-btn">СОЗДАТЬ АККАУНТ</button>
            </form>
            <a href="login.php" class="auth-link">Уже есть аккаунт? Войти</a>
        </div>
    </div>
</body>
</html>