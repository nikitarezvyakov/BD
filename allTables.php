<?php
// Подключение к базе данных
$mysqli = new mysqli('localhost', 'root', '', '3labrezv');

// Проверка соединения
if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: " . $mysqli->connect_error;
    exit();
}

// Функция для получения данных из таблицы и вывода их в виде таблицы
function displayData($table, $mysqli) {
    $result = $mysqli->query("SELECT * FROM $table");
    if ($result->num_rows > 0) {
        echo "<h2>Данные из таблицы $table:</h2>";
        echo "<table border='1'>";
        echo "<tr>";
        while ($finfo = $result->fetch_field()) {
            echo "<th>{$finfo->name}</th>";
        }
        echo "</tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>$value</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Таблица $table пуста.</p>";
    }
}

// Проверяем, была ли нажата какая-либо кнопка
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    switch ($action) {
        case 'view_students':
            displayData('студенты', $mysqli);
            break;
        case 'view_dormitories':
            displayData('Общежитие', $mysqli);
            break;
        case 'view_rooms':
            displayData('студенты_Комнаты', $mysqli);
            break;
        case 'view_payments':
            displayData('Платежи', $mysqli);
            break;
        case 'view_room_numbers':
            displayData('НомерКомнаты', $mysqli);
            break;
        case 'view_students_in_dormitories':
            displayData('Общежития', $mysqli);
            break;
        default:
            echo "<p>Неверное действие.</p>";
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Просмотр данных</title>
    <link rel="stylesheet" href="style.css">
    
</head>
<body>

<h1>Просмотр данных</h1>

<!-- Формы для просмотра данных -->
<form action="?" method="get">
    <input type="hidden" name="action" value="view_students">
    <button class="button-card">Посмотреть всех студентов</button>
</form>

<form action="?" method="get">
    <input type="hidden" name="action" value="view_dormitories">
    <button class="button-card">Посмотреть все общежития</button>
</form>

<form action="?" method="get">
    <input type="hidden" name="action" value="view_rooms">
    <button class="button-card">Посмотреть все комнаты</button>
</form>

<form action="?" method="get">
    <input type="hidden" name="action" value="view_payments">
    <button class="button-card">Посмотреть все платежи</button>
</form>

<form action="?" method="get">
    <input type="hidden" name="action" value="view_room_numbers">
    <button class="button-card">Посмотреть номера комнат</button>
</form>

<form action="?" method="get">
    <input type="hidden" name="action" value="view_students_in_dormitories">
    <button class="button-card">Посмотреть студентов в общежитиях</button>
</form>

<form action="index.php" method="get">
    <button class="button-card">Вернуться на главную страницу</button>
</form>

</body>
</html>
