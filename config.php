<?php
// Настройки подключения к базе данных
$host    = 'localhost';
$db      = 'tutor_search';   // Имя базы данных
$user    = 'root';           // Ваш пользователь БД
$pass    = '';               // Пароль для доступа к БД
$charset = 'utf8mb4';

// Формирование DSN (Data Source Name)
$dsn = "mysql:host={$host};dbname={$db};charset={$charset}";

// Дополнительные настройки PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Выбрасывать исключения при ошибках
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Ассоциативный массив по умолчанию
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Отключить эмуляцию подготовленных запросов
];

try {
    // Создаём объект PDO для подключения
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Если возникает ошибка подключения, выводим сообщение и завершаем выполнение
    die("Database connection failed: " . $e->getMessage());
}
?>
