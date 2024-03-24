<?php
$group = $_GET['group'] ?? '';

if (!empty($group)) {
    // Подключение к базе данных
    $mysqli = new mysqli('localhost', 'root', '', '3labrezv');

    if ($mysqli->connect_errno) {
        echo json_encode(array());
        exit();
    }

    // Формируем SQL-запрос для получения фамилий студентов по выбранной группе
    $sql = "SELECT DISTINCT Фамилия FROM студенты WHERE Группа = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $group);
    $stmt->execute();
    $result = $stmt->get_result();

    // Формируем массив фамилий
    $surnames = array();
    while ($row = $result->fetch_assoc()) {
        $surnames[] = $row['Фамилия'];
    }

    // Отправляем массив фамилий в формате JSON
    echo json_encode($surnames);

    // Закрываем соединение с базой данных
    $stmt->close();
    $mysqli->close();
} else {
    echo json_encode(array());
}
?>
