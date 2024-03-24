<?php
// Подключение к базе данных
$mysqli = new mysqli('localhost', 'root', '', '3labrezv');

// Проверка соединения
if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: " . $mysqli->connect_error;
    exit();
}

// Проверяем, были ли переданы данные методом POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем данные из формы
    $student_id = $_POST['student_id'] ?? '';
    $room_number = $_POST['room_number'] ?? '';

    // Проверяем, что данные не пустые
    if (!empty($student_id) && !empty($room_number)) {
        // Подготовленный запрос для добавления комнаты студенту
        $stmt = $mysqli->prepare("UPDATE Комнаты SET КодСтудента = ? WHERE НомерКомнаты = ?");
        $stmt->bind_param("ii", $student_id, $room_number);

        // Выполняем запрос
        if ($stmt->execute()) {
            echo "<p>Комната успешно добавлена студенту</p>";
        } else {
            echo "Ошибка при добавлении комнаты студенту: " . $stmt->error;
        }

        // Закрываем запрос
        $stmt->close();
    } else {
        // Выводим сообщение об ошибке, если данные не были переданы
        echo "<p>Пожалуйста, выберите студента и комнату</p>";
    }
}

// Закрываем соединение с базой данных
$mysqli->close();
?>
