<?php
// Подключение к базе данных
$mysqli = new mysqli('localhost', 'root', '', '3labrezv');

// Проверка соединения
if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: " . $mysqli->connect_error;
    exit();
}

// Проверяем, была ли добавлена группа
session_start();
$added_group = $_SESSION['added_group'] ?? '';

// Обработка отправки формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверяем, была ли отправлена форма для добавления студента
    if (isset($_POST['add_student'])) {
        // Получаем данные из формы
        $name = $_POST['name'] ?? '';
        $surname = $_POST['surname'] ?? '';
        $patronymic = $_POST['patronymic'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $group = $_POST['group'] ?? '';
        $birthdate = $_POST['birthdate'] ?? '';
        $gender = $_POST['gender'] ?? '';

        // Проверка, были ли введены данные
        if (!empty($name) && !empty($surname) && !empty($phone) && !empty($birthdate) && !empty($gender)) {
            // Подготовленный запрос для вставки данных
            $stmt = $mysqli->prepare("INSERT INTO студенты (Имя, Фамилия, Отчество, НомерТелефона, Группа, ДатаРождения, Пол) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $name, $surname, $patronymic, $phone, $group, $birthdate, $gender);

            // Выполняем запрос
            if ($stmt->execute()) {
                echo "<p>Студент добавлен</p>";
                // Перенаправляем пользователя на эту же страницу, чтобы избежать повторной отправки формы
                header("Location: {$_SERVER['REQUEST_URI']}");
                exit();
            } else {
                echo "Ошибка при добавлении студента: " . $stmt->error;
            }

            // Закрываем запрос
            $stmt->close();
        } else {
            // Если данные не введены, выводим сообщение об этом
            echo "<p>Пожалуйста, заполните все обязательные поля формы</p>";
        }
    } elseif (isset($_POST['add_room'])) {
        // Обработка добавления комнаты студенту
        // Ваш код для добавления комнаты студенту здесь
        // Например, вы можете предложить всплывающий список комнат и позволить пользователю выбрать комнату, которую он хочет добавить студенту.
        // После выбора комнаты выполните соответствующие действия для добавления комнаты студенту.
    } elseif (isset($_POST['add_inventory'])) {
        // Обработка добавления инвентаря
        // Ваш код для добавления инвентаря здесь
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Добавление студента</title>
</head>
<body>

<h1>Добавление студента</h1>

<?php
// Проверяем, была ли добавлена группа
if (!empty($added_group)) {
    echo "<p>Группа $added_group была успешно добавлена</p>";
    // Очищаем значение добавленной группы из сессии
    unset($_SESSION['added_group']);
}
?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label>Имя: <input type="text" name="name" placeholder="Иван"></label><br>
    <label>Фамилия: <input type="text" name="surname" placeholder="Иванов"></label><br>
    <label>Отчество: <input type="text" name="patronymic" placeholder="Иванович"></label><br>
    <label>Номер телефона: <input type="text" name="phone" placeholder="+79234567890"></label><br>
    <label>Группа: 
        <select name="group">
            <?php
            // Получение всех групп из базы данных
            $sql = "SELECT НомерГруппы FROM НомерГруппы";
            $result = $mysqli->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['НомерГруппы']}'>{$row['НомерГруппы']}</option>";
            }
            ?>
        </select>
    </label><br>
    <label>Дата рождения: <input type="date" name="birthdate"></label><br>
    <label>Пол: 
        <select name="gender">
            <option value="m">Мужской</option>
            <option value="f">Женский</option>
        </select>
    </label><br>
    <input type="submit" name="add_student" value="Добавить студента">
</form>


<!-- Кнопки-карточки -->
<button class="button-card" onclick="window.location.href='add_room.php';">Добавить комнату студенту</button>
<button class="button-card" onclick="window.location.href='add_payment.php';">Добавить платеж</button>
<button class="button-card" onclick="window.location.href='add_inventory.php';">Добавить инвентарь</button>
<button class="button-card" onclick="window.location.href='addGroup.php';">Добавить группу</button>
<button class="button-card" onclick="window.location.href='index.php';">Главное меню</button>



</body>
</html>
<?php
$mysqli->close();
?>
