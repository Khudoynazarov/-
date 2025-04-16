<?php
session_start();
require '../config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Доступ только для администраторов");
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Панель администратора</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        ul { list-style: none; padding: 0; }
        li { margin-bottom: 10px; }
    </style>
</head>
<body>
    <h2>Панель администратора</h2>
    <ul>
        <li><a href="admin_tutors.php">Управление репетиторами</a></li>
        <li><a href="admin_subjects.php">Управление предметами</a></li>
    </ul>
</body>
</html>
