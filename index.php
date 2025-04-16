<?php
session_start();
require 'config.php';

// Подключаем навигацию для входа/регистрации (можно вынести в header.php)
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Поиск репетиторов</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        nav { margin-bottom: 20px; }
        nav a { margin-right: 15px; text-decoration: none; color: #007bff; }
        nav a:hover { text-decoration: underline; }
        h2 { color: #333; }
        ul { list-style: none; padding: 0; }
        li { margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .btn-book { display: inline-block; margin-top: 10px; padding: 8px 12px; background-color: #28a745; color: #fff; text-decoration: none; border-radius: 4px; }
        .btn-book:hover { background-color: #218838; }
    </style>
</head>
<body>
    <nav>
        <?php if (!isset($_SESSION['user'])): ?>
            <a href="login.php">Войти</a>
            <a href="register.php">Регистрация</a>
        <?php else: ?>
            <span>Добро пожаловать, <?= htmlspecialchars($_SESSION['user']['full_name']); ?></span>
            <a href="logout.php">Выйти</a>
        <?php endif; ?>
    </nav>

    <?php
    // Получение списка предметов для фильтрации
    $stmt = $pdo->query("SELECT * FROM subjects");
    $subjects = $stmt->fetchAll();

    // Формирование условия для выборки репетиторов из таблицы users (роль tutor)
    $whereSql = "role = 'tutor'";
    $params = [];

    if (!empty($_GET['subject'])) {
        $whereSql .= " AND id IN (SELECT tutor_id FROM tutor_subjects WHERE subject_id = ?)";
        $params[] = $_GET['subject'];
    }

    $sql = "SELECT * FROM users WHERE $whereSql";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $tutors = $stmt->fetchAll();
    ?>

    <h2>Поиск репетиторов</h2>
    <form method="GET">
        <select name="subject">
            <option value="">-- Все предметы --</option>
            <?php foreach ($subjects as $subject): ?>
                <option value="<?= htmlspecialchars($subject['id']) ?>" <?= (isset($_GET['subject']) && $_GET['subject'] == $subject['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($subject['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Найти</button>
    </form>

    <h2>Список репетиторов</h2>
    <ul>
    <?php if ($tutors): ?>
        <?php foreach ($tutors as $tutor): ?>
            <li>
                <strong><?= htmlspecialchars($tutor['full_name']) ?></strong><br>
                <?php if (!empty($tutor['education'])): ?>
                    Образование: <?= htmlspecialchars($tutor['education']) ?><br>
                <?php endif; ?>
                <?php if (!empty($tutor['experience'])): ?>
                    Опыт: <?= htmlspecialchars($tutor['experience']) ?> лет<br>
                <?php endif; ?>
                <?php if (!empty($tutor['price'])): ?>
                    Цена: <?= htmlspecialchars($tutor['price']) ?> руб.<br>
                <?php endif; ?>
                <a href="booking.php?tutor_id=<?= $tutor['id'] ?>" class="btn-book">Записаться</a>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <li>Репетиторы не найдены</li>
    <?php endif; ?>
    </ul>
</body>
</html>
