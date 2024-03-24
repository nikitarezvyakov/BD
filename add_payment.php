<?php
// Подключение к базе данных
$mysqli = new mysqli('localhost', 'root', '', '3labrezv');

// Проверка соединения
if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: " . $mysqli->connect_error;
    exit();
}

// Проверяем, был ли отправлен запрос на добавление платежа
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем данные из формы
    $student_id = $_POST['student_id'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $payment_date = $_POST['payment_date'] ?? '';
    $email = $_POST['email'] ?? '';
    $description = $_POST['description'] ?? '';

    // Проверяем, были ли введены все обязательные данные
    if (!empty($student_id) && !empty($amount) && !empty($payment_date)) {
        // Подготавливаем запрос для вставки данных
        $stmt = $mysqli->prepare("INSERT INTO Платежи (КодСтудента, Сумма, ДатаПлатежа, Почта, Описание) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiss", $student_id, $amount, $payment_date, $email, $description);

        // Выполняем запрос
        if ($stmt->execute()) {
            echo "<p>Платеж успешно добавлен</p>";
            // Перенаправляем пользователя на эту же страницу, чтобы избежать повторной отправки формы
            header("Location: {$_SERVER['REQUEST_URI']}");
            exit();
        } else {
            echo "Ошибка при добавлении платежа: " . $stmt->error;
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
    <title>Добавление платежа</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Добавление платежа</h1>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label>Код студента: <input type="number" name="student_id" placeholder="Пример: 123"></label><br>
    <label>Сумма: <input type="number" name="amount" placeholder="Пример: 1000"></label><br>
    <label>Дата платежа: <input type="date" name="payment_date" placeholder="Пример: 2024-03-23"></label><br>
    <label>Почта: <input type="text" name="email" placeholder="Пример: example@example.com"></label><br>
    <label>Описание: <input type="text" name="description" placeholder="Пример: Оплата за обучение"></label><br>
    <input type="submit" value="Добавить платеж">
</form>

<!-- Кнопки для перехода на другие страницы -->
<button class="button-card" onclick="window.location.href='index.php';">Главное меню</button>
<button class="button-card" onclick="window.location.href='add_student.php';">Добавить студента</button>

</body>
</html>

<?php
$mysqli->close();
?>
