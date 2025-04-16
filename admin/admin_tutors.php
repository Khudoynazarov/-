<?php
session_start();
require '../config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Доступ только для администраторов");
}

// Удаление репетитора, если передан параметр delete
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'tutor'");
    $stmt->execute([$_GET['delete']]);
    header("Location: admin_tutors.php");
    exit;
}

// Выбор репетиторов (пользователей с ролью 'tutor')
$stmt = $pdo->query("SELECT * FROM users WHERE role = 'tutor'");
$tutors = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление репетиторами</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h2>Репетиторы</h2>
    <table>
        <thead>
            <tr>
                <th>ФИО</th>
                <th>Email</th>
                <th>Опыт</th>
                <th>Образование</th>
                <th>Цена</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tutors as $tutor): ?>
            <tr>
                <td><?= htmlspecialchars($tutor['full_name']) ?></td>
                <td><?= htmlspecialchars($tutor['email']) ?></td>
                <td><?= htmlspecialchars($tutor['experience']) ?></td>
                <td><?= htmlspecialchars($tutor['education']) ?></td>
                <td><?= htmlspecialchars($tutor['price']) ?></td>
                <td>
                    <a href="?delete=<?= $tutor['id'] ?>" onclick="return confirm('Удалить этого репетитора?');">Удалить</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p><a href="index.php">Вернуться в панель администратора</a></p>
</body>
</html>
