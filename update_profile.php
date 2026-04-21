<?php
session_start();

// ПУТЬ: Теперь без "../", так как файл в корне рядом с папкой img
$uploadDir = 'img/avatars/';

// Создаем папку, если её нет
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        
        $fileTmpPath = $_FILES['avatar']['tmp_name'];
        $fileName = $_FILES['avatar']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $newFileName = 'avatar_' . time() . '.' . $fileExtension;
        $dest_path = $uploadDir . $newFileName;

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($fileExtension, $allowedExtensions)) {
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                
                // Сохраняем путь для отображения в профиле
                $_SESSION['user_avatar'] = 'img/avatars/' . $newFileName;
                
                header("Location: profile.php?success=1");
                exit();
            }
        }
    }
    // Если файл не выбран или ошибка, просто возвращаемся назад
    header("Location: profile.php");
    exit();
}
?>