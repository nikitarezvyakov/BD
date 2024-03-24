<?php
// Подключение к базе данных
$mysqli = new mysqli('localhost', 'root', '', '3labrezv');

// Проверка соединения
if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: " . $mysqli->connect_error;
    exit();
}

// Проверяем, был ли отправлен запрос на добавление инвентаря
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем данные из формы
    $inventory_type = $_POST['inventory_type'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $serial_number = $_POST['serial_number'] ?? '';
    $service_life = $_POST['service_life'] ?? '';
    $manufacturer = $_POST['manufacturer'] ?? '';
    $student_id = $_POST['student_id'] ?? '';

    // Проверяем, были ли введены все обязательные данные
    if (!empty($inventory_type) && !empty($start_date) && !empty($service_life) && !empty($manufacturer)) {
        // Подготавливаем запрос для вставки данных
        $stmt = $mysqli->prepare("INSERT INTO Инвентарь (ТипИнвентаря, ДатаНачалаЭксплуатации, СерийныйНомер, СрокСлужбы, Производитель, КодСтудента) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $inventory_type, $start_date, $serial_number, $service_life, $manufacturer, $student_id);

        // Выполняем запрос
        if ($stmt->execute()) {
            echo "<p>Инвентарь успешно добавлен</p>";
            // Перенаправляем пользователя на эту же страницу, чтобы избежать повторной отправки формы
            header("Location: {$_SERVER['REQUEST_URI']}");
            exit();
        } else {
            echo "Ошибка при добавлении инвентаря: " . $stmt->error;
        }

        // Закрываем запрос
        $stmt->close();
    } else {
        // Если не все обязательные данные были введены, выводим сообщение об ошибке
        echo "<p>Пожалуйста, заполните все обязательные поля формы</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавление инвентаря</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Добавление инвентаря</h1>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label>Тип инвентаря: <input type="text" name="inventory_type" placeholder="Пример: ноутбук"></label><br>
    <label>Дата начала эксплуатации: <input type="date" name="start_date" placeholder="Пример: 2022-01-01"></label><br>
    <label>Серийный номер: <input type="text" name="serial_number" placeholder="Пример: SN123456"></label><br>
    <label>Срок службы: <input type="date" name="service_life" placeholder="Пример: 2025-12-31"></label><br>
    <label>Производитель: <input type="text" name="manufacturer" placeholder="Пример: HP"></label><br>
    <label>Код студента:<input type="text" name="student_id" placeholder="Пример: 123"></label><br>
    <input type="submit" value="Добавить инвентарь">
</form>

<!-- Кнопки для перехода на другие страницы -->
<button class="button-card" onclick="window.location.href='index.php';">Главное меню</button>
<button class="button-card" onclick="window.location.href='add_student.php';">Добавить студента</button>


</body>
</html>

<?php
$mysqli->close();
?>
