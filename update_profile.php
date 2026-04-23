<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Сбор текстовых данных и координат
    $nickname = !empty($_POST['nickname']) ? $_POST['nickname'] : $_SESSION['user_name'];
    $status   = $_POST['status'] ?? 'CONTENT CREATOR';
    $gender   = $_POST['gender'] ?? 'male';
    $banner_y = $_POST['banner_y'] ?? 50;
    $av_x     = $_POST['av_x'] ?? 0;
    $av_y     = $_POST['av_y'] ?? 0;

    // Обновляем основные данные одним запросом
    $stmt = $pdo->prepare("UPDATE users SET 
        username = ?, 
        status = ?, 
        gender = ?, 
        banner_pos_y = ?, 
        avatar_pos_x = ?, 
        avatar_pos_y = ? 
        WHERE id = ?");
    
    $stmt->execute([$nickname, $status, $gender, $banner_y, $av_x, $av_y, $userId]);

    // Обновляем имя в сессии, чтобы редирект и шапка не глючили
    $_SESSION['user_name'] = $nickname;

    // 2. Обработка файлов (Баннер)
    if (!empty($_FILES['banner']['name'])) {
        $bannerName = time() . '_banner_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $_FILES['banner']['name']);
        if (move_uploaded_file($_FILES['banner']['tmp_name'], 'img/banners/' . $bannerName)) {
            $pdo->prepare("UPDATE users SET banner = ? WHERE id = ?")->execute(['img/banners/' . $bannerName, $userId]);
        }
    }
    
// 3. Обработка файлов (Аватар)
    if (!empty($_FILES['avatar']['name'])) {
        // Формируем имя файла
        $avatarName = time() . '_av_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $_FILES['avatar']['name']);
        $fullPath = 'img/avatars/' . $avatarName;

        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $fullPath)) {
            // 1. Обновляем путь в базе данных
            $pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?")->execute([$fullPath, $userId]);
            
            // 2. ОБЯЗАТЕЛЬНО обновляем путь в сессии, чтобы в шапке сразу сменилась картинка
            $_SESSION['user_avatar'] = $fullPath; 
        }
    }

    // Возвращаемся на страницу профиля с флагом успеха
    header("Location: profile.php?u=" . urlencode($nickname) . "&success=1");
    exit();
}