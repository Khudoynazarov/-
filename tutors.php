<?php
require 'config.php';

// Если передан параметр id — выводим подробное описание выбранного репетитора
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role = 'tutor'");
    $stmt->execute([$_GET['id']]);
    $tutor = $stmt->fetch();

    if (!$tutor) {
        echo "Репетитор не найден.";
        exit;
    }
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Профиль репетитора</title>
    </head>
    <body>
        <h2>Профиль репетитора: <?= htmlspecialchars($tutor['full_name']) ?></h2>
        <p>Email: <?= htmlspecialchars($tutor['email']) ?></p>
        <?php if (!empty($tutor['education'])): ?>
            <p>Образование: <?= htmlspecialchars($tutor['education']) ?></p>
        <?php endif; ?>
        <?php if (!empty($tutor['experience'])): ?>
            <p>Опыт: <?= htmlspecialchars($tutor['experience']) ?> лет</p>
        <?php endif; ?>
        <?php if (!empty($tutor['price'])): ?>
            <p>Цена: <?= htmlspecialchars($tutor['price']) ?> руб.</p>
        <?php endif; ?>
        <p><a href="tutors.php">Вернуться к списку репетиторов</a></p>
    </body>
    </html>
    <?php
} else {
    // Вывод списка репетиторов
    $stmt = $pdo->query("SELECT * FROM users WHERE role = 'tutor'");
    $tutors = $stmt->fetchAll();
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Список репетиторов</title>
        <style>
            ul { list-style: none; padding: 0; }
            li { margin-bottom: 10px; }
        </style>
    </head>
    <body>
        <h2>Список репетиторов</h2>
        <ul>
            <?php foreach ($tutors as $tutor): ?>
                <li>
                    <a href="tutors.php?id=<?= $tutor['id'] ?>">
                        <?= htmlspecialchars($tutor['full_name']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </body>
    </html>
    <?php
}
?>
