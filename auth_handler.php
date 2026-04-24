<?php
session_start();
require_once 'config/database.php';

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($action === 'login') {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user) {
            if (password_verify($password, $user['password']) || md5($password) === $user['password']) {
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['username'];
                
                if (!empty($user['avatar'])) {
                    $_SESSION['user_avatar'] = $user['avatar'];
                }

                // ВЫДАЕМ ПРАВА АДМИНА, ЕСЛИ ЭТО ТЫ
                if ($user['username'] === 'tur391') {
                    $_SESSION['admin'] = true;
                } else {
                    $_SESSION['admin'] = false; // На всякий случай сбрасываем для других
                }

                header("Location: index.php");
                exit();
            } else {
                die("Неверный пароль!");
            }
        } else {
            die("Пользователь не найден!");
        }
    }
}