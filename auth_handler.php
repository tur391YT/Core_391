<?php
session_start();
require_once 'config/database.php';

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($action === 'register') {
        // 1. Проверяем, не занят ли ник
        $check = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $check->execute([$username]);
        
        if ($check->fetch()) {
            die("Этот никнейм уже занят!");
        }

        // 2. Хешируем пароль (современный стандарт BCRYPT)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        
        if ($stmt->execute([$username, $hashedPassword])) {
            header("Location: login.php?msg=success");
            exit();
        } else {
            die("Ошибка при регистрации");
        }
    } 
    
    elseif ($action === 'login') {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user) {
            // ПРОВЕРКА ПАРОЛЯ:
            // 1. password_verify — для новых хешей (BCRYPT)
            // 2. md5() === ... — для твоего админа, которому ты поставил MD5 в базе
            if (password_verify($password, $user['password']) || md5($password) === $user['password']) {
                
                // Успешный вход! Сохраняем данные в сессию
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['username'];
                
                // Если у юзера есть аватар в базе, запоминаем его сразу
                if (!empty($user['avatar'])) {
                    $_SESSION['user_avatar'] = $user['avatar'];
                }

                header("Location: profile.php?u=" . $user['username']);
                exit();
            } else {
                die("Неверный пароль!");
            }
        } else {
            die("Пользователь с таким ником не найден!");
        }
    }
}