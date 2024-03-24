<?php
session_start();

// Подключение к базе данных
$mysqli = new mysqli('localhost', 'root', '', '3labrezv');

// Проверка соединения
if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: " . $mysqli->connect_error;
    exit();
}

// Обработка отправки формы добавления студента
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение данных из формы
    $student_id = $_POST['student_id'] ?? '';
    $room_number = $_POST['room_number'] ?? '';

    // Проверка заполненности всех обязательных полей
    if (!empty($student_id) && !empty($room_number)) {
        // Подготовленный запрос для вставки данных
        $stmt = $mysqli->prepare("INSERT INTO Студенты_Комнаты (КодСтудента, НомерКомнаты) VALUES (?, ?)");
        $stmt->bind_param("ii", $student_id, $room_number);

        // Выполнение запроса
        if ($stmt->execute()) {
            echo "<p>Студент успешно добавлен в комнату</p>";
        } else {
            echo "<p>Ошибка при добавлении студента в комнату: " . $stmt->error . "</p>";
        }

        // Закрытие запроса
        $stmt->close();
    } else {
        echo "<p>Пожалуйста, выберите студента и номер комнаты</p>";
    }
}

// Функция для получения всплывающего списка студентов
function getStudentsDropdown($mysqli) {
    $options = '';
    $sql = "SELECT КодСтудента, Имя, Фамилия FROM Студенты";
    $result = $mysqli->query($sql);
    while ($row = $result->fetch_assoc()) {
        $options .= "<option value='{$row['КодСтудента']}'>{$row['Имя']} {$row['Фамилия']}</option>";
    }
    return $options;
}

// Функция для получения всплывающего списка номеров комнат
function getRoomsDropdown($mysqli) {
    $options = '';
    $sql = "SELECT НомерКомнаты, СвободныеМеста FROM НомерКомнаты";
    $result = $mysqli->query($sql);
    while ($row = $result->fetch_assoc()) {
        $options .= "<option value='{$row['НомерКомнаты']}'>{$row['НомерКомнаты']} (Свободно мест: {$row['СвободныеМеста']})</option>";
    }
    return $options;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавление студента в комнату</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Добавление студента в комнату</h1>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label>Студент: 
        <select name="student_id">
            <?php echo getStudentsDropdown($mysqli); ?>
        </select>
    </label><br>
    <label>Номер комнаты: 
        <select name="room_number">
            <?php echo getRoomsDropdown($mysqli); ?>
        </select>
    </label><br>
    <input type="submit" name="submit" value="Добавить студента в комнату">
</form>
<button class="button-card" onclick="window.location.href='index.php';">Главное меню</button>
<button class="button-card" onclick="window.location.href='add_student.php';">Добавить студента</button>
</body>
</html>

<?php
// Закрытие соединения с базой данных
$mysqli->close();
?>
