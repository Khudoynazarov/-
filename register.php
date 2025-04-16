<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение данных формы
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = isset($_POST['role']) ? $_POST['role'] : 'student';

    // Проверка обязательных полей
    if (empty($full_name) || empty($email) || empty($password)) {
        $error = "Пожалуйста, заполните все обязательные поля.";
    } else {
        // Проверяем, существует ли пользователь с таким email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Пользователь с таким email уже существует.";
        } else {
            // Хеширование пароля
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Добавляем пользователя в БД
            $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$full_name, $email, $hashed_password, $role]);

            header("Location: login.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { max-width: 400px; margin: 0 auto; }
        input, select { display: block; width: 100%; padding: 10px; margin-bottom: 15px; }
        button { padding: 10px 20px; }
        .error { color: red; margin-bottom: 15px; }
    </style>
</head>
<body>
    <h2>Регистрация</h2>
    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST" action="register.php">
        <input type="text" name="full_name" placeholder="ФИО" required value="<?= isset($full_name) ? htmlspecialchars($full_name) : '' ?>">
        <input type="email" name="email" placeholder="Email" required value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
        <input type="password" name="password" placeholder="Пароль" required>
        <select name="role">
            <option value="student" <?= (isset($role) && $role === 'student') ? 'selected' : '' ?>>Ученик</option>
            <option value="tutor" <?= (isset($role) && $role === 'tutor') ? 'selected' : '' ?>>Репетитор</option>
        </select>
        <button type="submit">Зарегистрироваться</button>
    </form>
    <p>Уже зарегистрированы? <a href="login.php">Войти</a></p>
</body>
</html>
