<?php
// Подключение к базе данных
$mysqli = new mysqli('localhost', 'root', '', '3labrezv');

// Проверка соединения
if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: " . $mysqli->connect_error;
    exit();
}

// Обработка отправки формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверяем, была ли отправлена форма
    if (isset($_POST['submit'])) {
        // Получаем данные из формы
        $student_id = $_POST['student_id'] ?? '';
        $dormitory_id = $_POST['dormitory_id'] ?? '';

        // Проверяем, были ли введены данные
        if (!empty($student_id) && !empty($dormitory_id)) {
            // Подготовленный запрос для вставки данных
            $stmt = $mysqli->prepare("INSERT INTO Общежития (КодСтудента, КодОбщежития) VALUES (?, ?)");
            $stmt->bind_param("ii", $student_id, $dormitory_id);

            // Выполняем запрос
            if ($stmt->execute()) {
                echo "<p>Данные успешно добавлены.</p>";
            } else {
                echo "<p>Ошибка при добавлении данных: " . $stmt->error . "</p>";
            }

            // Закрываем запрос
            $stmt->close();
        } else {
            echo "<p>Пожалуйста, заполните все поля формы.</p>";
        }
    }
}

// Получение списка студентов
$students_result = $mysqli->query("SELECT КодСтудента, Имя, Фамилия, Отчество FROM студенты");
$students = [];
while ($row = $students_result->fetch_assoc()) {
    $students[$row['КодСтудента']] = $row['Фамилия'] . ' ' . $row['Имя'] . ' ' . $row['Отчество'];
}

// Получение списка общежитий
$dormitories_result = $mysqli->query("SELECT КодОбщежития, Название FROM Общежитие");
$dormitories = [];
while ($row = $dormitories_result->fetch_assoc()) {
    $dormitories[$row['КодОбщежития']] = $row['Название'];
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавление данных для Общежития</title>
</head>
<body>

<h1>Добавление данных для Общежития</h1>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label>Студент: 
        <select name="student_id">
            <?php foreach ($students as $id => $name): ?>
                <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
            <?php endforeach; ?>
        </select>
    </label><br>
    <label>Общежитие: 
        <select name="dormitory_id">
            <?php foreach ($dormitories as $id => $name): ?>
                <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
            <?php endforeach; ?>
        </select>
    </label><br>
    <input type="submit" name="submit" value="Добавить данные">
</form>

<a href="index.php">На главную</a>

</body>
</html>
