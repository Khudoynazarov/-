<?php
session_start();
require '../config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Доступ только для администраторов");
}

// Обработка добавления предмета
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    
    if ($name !== '') {
        $stmt = $pdo->prepare("INSERT INTO subjects (name, category) VALUES (?, ?)");
        $stmt->execute([$name, $category]);
    }
}

// Обработка удаления предмета
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM subjects WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: admin_subjects.php");
    exit;
}

// Выбор всех предметов
$stmt = $pdo->query("SELECT * FROM subjects");
$subjects = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление предметами</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h2>Предметы</h2>
    <form method="POST" action="admin_subjects.php">
        <input type="text" name="name" placeholder="Название предмета" required>
        <input type="text" name="category" placeholder="Категория">
        <button type="submit">Добавить предмет</button>
    </form>
    <br>
    <table>
        <thead>
            <tr>
                <th>Название</th>
                <th>Категория</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($subjects as $subject): ?>
            <tr>
                <td><?= htmlspecialchars($subject['name']) ?></td>
                <td><?= htmlspecialchars($subject['category']) ?></td>
                <td>
                    <a href="?delete=<?= $subject['id'] ?>" onclick="return confirm('Удалить этот предмет?');">Удалить</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p><a href="index.php">Вернуться в панель администратора</a></p>
</body>
</html>
