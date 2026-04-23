<?php
session_start();
session_unset(); // Удаляем все переменные сессии
session_destroy(); // Уничтожаем саму сессию

// Перенаправляем на страницу входа или главную
header("Location: login.php");
exit();
?>