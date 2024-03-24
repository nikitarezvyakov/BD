<?php
// Подключение к базе данных
$mysqli = new mysqli('localhost', 'root', '', '3labrezv');

// Проверка соединения
if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: " . $mysqli->connect_error;
    exit();
}

// Получение данных из формы
$surname = $_POST['surname'];
$name = $_POST['name'];
$patronymic = $_POST['patronymic'];
$group = $_POST['group'];
$birthday = $_POST['birthday'];
$gender = $_POST['gender'];
$phone = $_POST['phone'];

// Подготовка SQL-запроса для добавления студента
$sql = "INSERT INTO студенты (Фамилия, Имя, Отчество, НомерТелефона, Группа, ДатаРождения, Пол) 
        VALUES ('$surname', '$name', '$patronymic', '$phone', '$group', '$birthday', '$gender')";

// Выполнение SQL-запроса
if ($mysqli->query($sql)) {
    echo "Студент успешно добавлен.";
} else {
    echo "Ошибка при добавлении студента: " . $mysqli->error;
}

// Закрываем соединение с базой данных
$mysqli->close();
?>
