<?php
require 'config.php';
session_start();

// Доступ к бронированию разрешён только студентам
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    die("Доступ к бронированию разрешён только для студентов.");
}

// Если передан tutor_id через GET, используем его, иначе предполагаем, что студент введёт его вручную
$tutor_id = isset($_GET['tutor_id']) ? $_GET['tutor_id'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Если tutor_id отсутствует, прерываем выполнение
    if (empty($_POST['tutor_id'])) {
        die("Репетитор не выбран.");
    }

    $tutor_id = $_POST['tutor_id'];
    $student_id = $_SESSION['user']['id'];
    $booking_datetime = $_POST['booking_datetime']; // ожидается формат 'YYYY-MM-DDTHH:MM'
    $format = $_POST['format'];
    $contact = $_POST['contact'];

    // Преобразуем datetime из формата 'YYYY-MM-DDTHH:MM' в 'YYYY-MM-DD HH:MM:SS'
    $datetime = str_replace("T", " ", $booking_datetime);

    $stmt = $pdo->prepare("INSERT INTO bookings (tutor_id, student_id, booking_datetime, format, contact) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$tutor_id, $student_id, $datetime, $format, $contact])) {
        echo "Бронирование успешно создано!";
    } else {
        echo "Ошибка при создании бронирования.";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Бронирование занятия</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { max-width: 400px; }
        input, select { display: block; width: 100%; padding: 10px; margin-bottom: 15px; }
        button { padding: 10px 20px; }
    </style>
</head>
<body>
    <h2>Бронирование занятия</h2>
    <form method="POST" action="booking.php">
        <?php if ($tutor_id): ?>
            <!-- Если tutor_id передан через GET, вставляем его скрытым полем -->
            <input type="hidden" name="tutor_id" value="<?= htmlspecialchars($tutor_id) ?>">
            <p>Выбран репетитор с ID: <?= htmlspecialchars($tutor_id) ?></p>
        <?php else: ?>
            <!-- Если tutor_id не передан, студент должен ввести его вручную -->
            <input type="number" name="tutor_id" placeholder="ID репетитора" required>
        <?php endif; ?>
        <input type="datetime-local" name="booking_datetime" required>
        <select name="format">
            <option value="online">Онлайн</option>
            <option value="offline">Оффлайн</option>
        </select>
        <input type="text" name="contact" placeholder="Контакт для связи" required>
        <button type="submit">Забронировать</button>
    </form>
</body>
</html>
