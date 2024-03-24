<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование студента</title>
    <!-- Подключение библиотеки inputmask -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Редактирование студента</h1>

<?php
$mysqli = new mysqli('localhost', 'root', '', '3labrezv');

if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: " . $mysqli->connect_error;
    exit();
}
 // Добавляем кнопку для возврата на главную страницу
 echo "<br>";

// Проверка, был ли отправлен запрос на редактирование студента
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $patronymic = $_POST['patronymic'];
    $phone = $_POST['phone'];
    $group = $_POST['group'];
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];

    // Проверка, были ли введены все обязательные поля
    if (!empty($name) && !empty($surname) && !empty($phone) && !empty($group) && !empty($birthdate) && !empty($gender)) {
        $sql = "UPDATE студенты SET Имя=?, Фамилия=?, Отчество=?, НомерТелефона=?, Группа=?, ДатаРождения=?, Пол=? WHERE КодСтудента=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sssssssi", $name, $surname, $patronymic, $phone, $group, $birthdate, $gender, $student_id);

        if ($stmt->execute()) {
            echo "<p>Студент успешно обновлен</p>";
        } else {
            echo "Ошибка при обновлении студента: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "<p>Пожалуйста, заполните все поля формы</p>";
    }
}

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
    $sql = "SELECT * FROM студенты WHERE КодСтудента=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if ($student) {
        // Выводим форму редактирования с текущими значениями
        ?>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" name="student_id" value="<?php echo $student['КодСтудента']; ?>">
            <label>Имя: <input type="text" name="name" value="<?php echo $student['Имя']; ?>"></label><br>
            <label>Фамилия: <input type="text" name="surname" value="<?php echo $student['Фамилия']; ?>"></label><br>
            <label>Отчество: <input type="text" name="patronymic" value="<?php echo $student['Отчество']; ?>"></label><br>
            <!-- Использование маски для номера телефона -->
            <label>Номер телефона: <input type="text" name="phone" value="<?php echo $student['НомерТелефона']; ?>" data-inputmask="'mask': '+7 999 999 99 99'"></label><br>
        
            <label>Группа: 
                <select name="group">
                    <?php
                    // Получение значений для группы из базы данных
                    $sql = "SELECT DISTINCT НомерГруппы FROM номергруппы";
                    $result = $mysqli->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        $selected = $student['Группа'] == $row['НомерГруппы'] ? 'selected' : '';
                        echo "<option value='{$row['НомерГруппы']}' $selected>{$row['НомерГруппы']}</option>";
                    }
                    ?>
                </select>
            </label><br>
            <label>Дата рождения: <input type="date" name="birthdate" value="<?php echo $student['ДатаРождения']; ?>"></label><br>
            <label>Пол:
                <select name="gender">
                    <option value="m" <?php if ($student['Пол'] == 'm') echo 'selected'; ?>>Мужской</option>
                    <option value="f" <?php if ($student['Пол'] == 'f') echo 'selected'; ?>>Женский</option>
                </select>
            </label><br>
            <input type="submit" value="Сохранить изменения">
        </form>
        <?php
    } else {
        echo "<p>Студент с ID $student_id не найден</p>";
    }
    $stmt->close();
}
 

$mysqli->close();
?>

<!-- Инициализация маски для номера телефона -->
<script>
    $(document).ready(function() {
        $('input[name="phone"]').inputmask();
    });
</script>
<button class="button-card" onclick="window.location.href='index.php';">Главное меню</button>
</body>
</html>
