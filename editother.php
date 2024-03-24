<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование комнаты, общежития, инвентаря и платежей студента</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Редактирование комнаты, общежития, инвентаря и платежей студента</h1>

<?php
$mysqli = new mysqli('localhost', 'root', '', '3labrezv');

if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: " . $mysqli->connect_error;
    exit();
}

// Добавляем кнопку для возврата на главную страницу
echo "<br>";
echo "<a href='index.php'>Вернуться на главную страницу</a>";

// Выводим форму для выбора студента
echo "<form method='get' action='{$_SERVER['PHP_SELF']}'>";
echo "<label>Выберите студента: ";
echo "<select name='student_id'>";
$sql = "SELECT КодСтудента, Имя, Фамилия FROM студенты";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    $selected = isset($_GET['student_id']) && $_GET['student_id'] == $row['КодСтудента'] ? 'selected' : '';
    echo "<option value='{$row['КодСтудента']}' $selected>{$row['Имя']} {$row['Фамилия']}</option>";
}
echo "</select>";
echo "</label>";
echo "<input type='submit' value='Выбрать'>";
echo "</form>";

// Получение данных выбранного студента для редактирования
if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];

    // Редактирование комнаты
    echo "<h2>Редактирование комнаты</h2>";
    echo "<form method='post' action='edit_room.php'>";
    echo "<input type='hidden' name='student_id' value='$student_id'>";
    echo "Номер комнаты: <input type='text' name='room_number'><br>";
    echo "Тип комнаты: <input type='text' name='room_type'><br>";
    echo "<input type='submit' value='Сохранить изменения комнаты'>";
    echo "</form>";

    // Редактирование общежития
    echo "<h2>Редактирование общежития</h2>";
    echo "<form method='post' action='edit_dormitory.php'>";
    echo "<input type='hidden' name='student_id' value='$student_id'>";
    echo "Название общежития: <input type='text' name='dormitory_name'><br>";
    echo "Адрес общежития: <input type='text' name='dormitory_address'><br>";
    echo "<input type='submit' value='Сохранить изменения общежития'>";
    echo "</form>";

    // Редактирование инвентаря
    echo "<h2>Редактирование инвентаря</h2>";
    echo "<form method='post' action='edit_inventory.php'>";
    echo "<input type='hidden' name='student_id' value='$student_id'>";
    echo "Название предмета: <input type='text' name='item_name'><br>";
    echo "Количество: <input type='number' name='quantity'><br>";
    echo "<input type='submit' value='Сохранить изменения инвентаря'>";
    echo "</form>";

    // Редактирование платежей
    echo "<h2>Редактирование платежей</h2>";
    echo "<form method='post' action='edit_payments.php'>";
    echo "<input type='hidden' name='student_id' value='$student_id'>";
    echo "Сумма платежа: <input type='number' name='payment_amount'><br>";
    echo "Дата платежа: <input type='date' name='payment_date'><br>";
    echo "<input type='submit' value='Сохранить изменения платежей'>";
    echo "</form>";
}


$mysqli->close();
?>

</body>
</html>
