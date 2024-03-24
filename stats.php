<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Статистика по количеству студентов в группах</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <h1>Статистика по количеству студентов в группах</h1>

    <?php
    // Подключение к базе данных
    $mysqli = new mysqli('localhost', 'root', '', '3labrezv');

    // Проверка соединения
    if ($mysqli->connect_errno) {
        echo "Не удалось подключиться к MySQL: " . $mysqli->connect_error;
        exit();
    }

    // Выполнение SQL-запроса для подсчета количества студентов в каждой группе
    $sql = "SELECT COUNT(*) AS количество_студентов, Группа
            FROM студенты
            GROUP BY Группа";

    $result = $mysqli->query($sql);

    // Проверка успешности выполнения запроса
    if ($result) {
        // Если есть строки в результате, выводим их
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Группа</th><th>Количество студентов</th></tr>";
            $total_students = 0; // Переменная для подсчета общего количества студентов
            // Выводим каждую строку результата
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["Группа"] . "</td>"; // выводим название группы
                echo "<td>" . $row["количество_студентов"] . "</td>"; // выводим количество студентов
                echo "</tr>";
                $total_students += $row["количество_студентов"]; // добавляем количество студентов в текущей группе к общему количеству
            }
            // Выводим строку с общим количеством студентов в конце таблицы
            echo "<tr><td><strong>Общее количество:</strong></td><td>$total_students</td></tr>";
            echo "</table>";

            // Добавляем кнопку для возврата на главную страницу
           
        } else {
            echo "Нет данных.";
        }
    } else {
        echo "Ошибка выполнения запроса: " . $mysqli->error;
    }

    // Закрываем соединение с базой данных
    $mysqli->close();
    ?>
<button class="button-card" onclick="window.location.href='index.php';">Главное меню</button>
</div>

</body>
</html>
