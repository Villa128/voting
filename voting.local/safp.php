<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    
    exit;
}

$link = mysqli_connect("localhost","jam1","root","democracy");

if ($link == false){
    print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
}
else {
    print("Соединение установлено успешно");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверка, был ли выбран вариант
    if (isset($_POST['option'])) {
        $selected_option = $_POST['option'];
        $voter_name = $_SESSION['username'];

        // Проверяем, голосовал ли уже этот пользователь
        $check_sql = "SELECT COUNT(*) as cnt FROM round1 WHERE prname = ?";
        $stmt = mysqli_prepare($link, $check_sql);
        mysqli_stmt_bind_param($stmt, "s", $voter_name);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if ($count > 0) 
        {
            echo "Вы уже голосовали и не можете голосовать повторно.";
        } 
        else { 
        $sql1 = "INSERT INTO round1 (prname,canID)
        VALUES ('$voter_name','$selected_option')";
        $result1 = mysqli_query($link, $sql1);
        echo "Вы выбрали: " . htmlspecialchars($selected_option) . "Ваше имя: " . htmlspecialchars($voter_name);

        }
        
    } else {
        echo "Пожалуйста, выберите вариант.";
    }
}


//$sql2 = "SELECT * FROM acright WHERE ID LIKE '%$selected_option%'";

$sql3 = "SELECT canID, COUNT(*) AS votes_count
FROM round1
GROUP BY canID;";

//$result = mysqli_query($link, $sql1);

$result = mysqli_query($link, $sql3);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo "canID: " . $row['canID'] . " — Голосов: " . $row['votes_count'] . "<br>";
    }
} else {
    echo "Ошибка запроса: " . $mysqli->error;
}


?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Защищённая страница</title>
</head>
<body>
    <h1>Добро пожаловать, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
    <p>Это защищённая страница.</p>

    <form method="POST" action="">
    <!--h3>Введите ваше имя:</h3>
    <input type="text" name="votername" value="anon" /><br-->
    <h3>Выберите вариант:</h3>
    <input type="radio" name="option" value="1" id="option1">
    <label for="option1">Вариант 1</label><br>

    <input type="radio" name="option" value="2" id="option2">
    <label for="option2">Вариант 2</label><br>

    <input type="radio" name="option" value="3" id="option3">
    <label for="option3">Вариант 3</label><br>

    <input type="submit" value="Отправить">
</form>

    
    <a href="login.php">Выйти</a>
</body>
</html>
