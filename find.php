<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Поиск студентов</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div id="searchStudent">
            <h2>Поиск студентов</h2>
            <form action="#" method="get">
                <label for="students">Выберите студента:</label>
                <select id="students" name="student">
                    <?php
                    // Подключение к базе данных
                    $mysqli = new mysqli('localhost', 'root', '', '3labrezv');

                    // Проверка соединения
                    if ($mysqli->connect_errno) {
                        echo "Не удалось подключиться к MySQL: " . $mysqli->connect_error;
                        exit();
                    }

                    // Выполнение SQL-запроса для получения списка студентов
                    $sql = "SELECT КодСтудента, Фамилия, Имя FROM студенты";
                    $result = $mysqli->query($sql);

                    // Проверка успешности выполнения запроса
                    if ($result) {
                        // Выводим каждую строку результата как опцию для всплывающего списка
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['КодСтудента'] . "'>" . $row['Фамилия'] . " " . $row['Имя'] . "</option>";
                        }
                    } else {
                        echo "Ошибка выполнения запроса: " . $mysqli->error;
                    }

                    // Закрываем соединение с базой данных
                    $mysqli->close();
                    ?>
                </select>
                <br>
                <input type="submit" value="Найти">
            </form>
        </div>
        <div id="searchAttributes">
            <h2>Поиск по атрибутам</h2>
            <form action="#" method="get">
                <label for="group">Группа:</label>
                <select id="group" name="group">
                    <?php
                    // Подключение к базе данных
                    $mysqli = new mysqli('localhost', 'root', '', '3labrezv');

                    // Проверка соединения
                    if ($mysqli->connect_errno) {
                        echo "Не удалось подключиться к MySQL: " . $mysqli->connect_error;
                        exit();
                    }

                    // Выполнение SQL-запроса для получения списка групп
                    $sql = "SELECT DISTINCT Группа FROM студенты";
                    $result = $mysqli->query($sql);

                    // Проверка успешности выполнения запроса
                    if ($result) {
                        // Выводим каждую группу как опцию для всплывающего списка
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['Группа'] . "'>" . $row['Группа'] . "</option>";
                        }
                    } else {
                        echo "Ошибка выполнения запроса: " . $mysqli->error;
                    }

                    // Закрываем соединение с базой данных
                    $mysqli->close();
                    ?>
                </select>
                <br>
                <label for="surname">Фамилия:</label>
                <select id="surname" name="surname">
                    <!-- Здесь будут появляться опции после выбора группы -->
                </select>
                <br>
                <input type="submit" value="Поиск">
            </form>
            
        </div>
        <div id="studentInfo">
            <?php
            // Добавляем кнопку для возврата на главную страницу
            echo "<br>";
            echo "<button class='button-card' onclick=\"window.location.href='index.php';\">Главное меню</button>";

            // PHP-обработчик для получения данных о студенте и вывода их на странице
            if (isset($_GET['student']) || isset($_GET['group']) || isset($_GET['surname'])) {
                // Подключение к базе данных
                $mysqli = new mysqli('localhost', 'root', '', '3labrezv');

                // Инициализация переменных для хранения параметров поиска
                $studentId = $_GET['student'] ?? null;
                $group = $_GET['group'] ?? null;
                $surname = $_GET['surname'] ?? null;

                // Формируем условие для запроса
                $condition = "";
                if (!empty($studentId)) {
                    $condition = "КодСтудента = $studentId";
                } elseif (!empty($group) && !empty($surname)) {
                    $condition = "Группа = '$group' AND Фамилия = '$surname'";
                } elseif (!empty($group)) {
                    $condition = "Группа = '$group'";
                } elseif (!empty($surname)) {
                    $condition = "Фамилия = '$surname'";
                }

                // Выполнение SQL-запроса для получения информации о студенте по условию
                $sql = "SELECT * FROM студенты";
                if (!empty($condition)) {
                    $sql .= " WHERE $condition";
                }
                $result = $mysqli->query($sql);

                // Проверка успешности выполнения запроса
                if ($result && $result->num_rows > 0) {
                    // Выводим информацию о студенте в виде таблицы
                    echo "<h2>Информация о студенте:</h2>";
                    echo "<table>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<th>Поле</th><th>Значение</th>";
                        echo "</tr>";
                        foreach ($row as $key => $value) {
                            echo "<tr>";
                            echo "<td>$key</td>";
                            echo "<td>$value</td>";
                            echo "</tr>";
                        }
                    }
                    echo "</table>";
                } else {
                    echo "Студент не найден.";
                }

                // Закрываем соединение с базой данных
                $mysqli->close();
            }
            ?>
        </div>
        </div>
</div>
<script>
    // Динамическое обновление списка фамилий при выборе группы
    document.getElementById('group').addEventListener('change', function() {
        var group = this.value;
        var surnameSelect = document.getElementById('surname');
        // Очищаем предыдущие значения во втором списке
        surnameSelect.innerHTML = '';
        // Выполняем запрос на получение фамилий для выбранной группы
        fetch('get_surnames.php?group=' + group)
            .then(response => response.json())
            .then(data => {
                // Заполняем второй список полученными фамилиями
                data.forEach(surname => {
                    var option = document.createElement('option');
                    option.value = surname;
                    option.textContent = surname;
                    surnameSelect.appendChild(option);
                });
            });
    });
</script>
</body>
</html>
