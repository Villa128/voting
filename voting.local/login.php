<?php
session_start();

// Подключение к базе данных
$mysqli = new mysqli("localhost", "jam1","root","democracy");



if ($mysqli->connect_error) {
    die("Ошибка подключения: " . $mysqli->connect_error);
}

// Выполнение запроса
$result = $mysqli->query("SELECT user, password FROM voters");

if ($result) {
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[$row['user']] = $row['password'];
    }
    //print_r($users); // Вывод массива пользователей с паролями
} else {
    echo "Ошибка запроса: " . $mysqli->error;
}

$mysqli->close();



$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Проверяем, что пользователь существует и пароль совпадает
    if (isset($users[$username]) && $users[$username] === $password) {
        $_SESSION['username'] = $username;
        header('Location: safp.php');
        exit;
    } else {
        $error = 'Неверное имя пользователя или пароль';
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
</head>
<body>
    <h2>Вход</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" action="login.php">
        <label>
            Имя пользователя: <input type="text" name="username" required>
        </label><br><br>
        <label>
            Пароль: <input type="text" name="password" required>
        </label><br><br>
        <button type="submit">Войти</button>

        <a href="usereg.php">signup</a>
        
    </form>

</body>
</html>
