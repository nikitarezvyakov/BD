<?php
// Подключение к базе данных
$mysqli = new mysqli('localhost', 'root', '', '3labrezv');

// Проверка соединения
if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: " . $mysqli->connect_error;
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем данные из формы
    $group_number = $_POST['group_number'] ?? '';

    // Проверка, были ли введены данные
    if (!empty($group_number)) {
        // Подготовленный запрос для вставки данных
        $stmt = $mysqli->prepare("INSERT INTO НомерГруппы (НомерГруппы) VALUES (?)");
        $stmt->bind_param("s", $group_number);

        // Выполняем запрос
        if ($stmt->execute()) {
            echo "<p>Группа добавлена</p>";

            // Сохраняем добавленную группу в сессии
            session_start();
            $_SESSION['added_group'] = $group_number;
        } else {
            echo "Ошибка при добавлении группы: " . $stmt->error;
        }

        // Закрываем запрос
        $stmt->close();
    } else {
        // Если данные не введены, выводим сообщение об этом
        echo "<p>Пожалуйста, заполните все поля формы</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавление группы</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Добавление группы</h1>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label>Номер группы: <input type="text" name="group_number" placeholder="ИВТ-Б21"></label><br>
    <input type="submit" value="Добавить группу">
</form>
<button class="button-card" onclick="window.location.href='index.php';">Главное меню</button>
<button class="button-card" onclick="window.location.href='add_student.php';">Добавить студента</button>


</body>
</html>
