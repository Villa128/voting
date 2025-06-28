<?php

// Подключение к базе данных
$mysqli = new mysqli("localhost", "jam1","root","democracy");

$link = mysqli_connect("localhost","jam1","root","democracy"); //Да, знаю, это можно обьеденить

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
    if (isset($users[$username]) ) {
       $error = 'Такой пользователь уже существует';
    } else {
        
        $username = $_POST['username'];
        $user_pass = $_POST['password'];

        $sql1 = "INSERT INTO voters (user,password)
        VALUES ('$username','$user_pass')";
        $result1 = mysqli_query($link, $sql1);
        echo "ok";
    
    
}

}
else{
    $error = 'Ошибка - введите данные';
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>signup</title>
</head>
<body>

    <h2>signup</h2>
    <a href="login.php">Назад</a>
    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" action="usereg.php">
        <label>
            Имя пользователя: <input type="text" name="username" required>
        </label><br><br>
        <label>
            Пароль: <input type="text" name="password" required>
        </label><br><br>
        <button type="submit">signup</button>

    </form>

    
</body>
</html>
