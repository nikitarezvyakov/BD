<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование комнаты студента</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Редактирование комнаты студента</h1>

<form id="editRoomForm">
    <label>Выберите студента:
        <select id="studentSelect" name="student_id">
            <!-- Здесь должны быть варианты для выбора студента -->
        </select>
    </label><br>
    <label>Номер комнаты: <input type="text" id="roomNumberInput" name="room_number"></label><br>
    <input type="submit" value="Сохранить изменения">
</form>

<script>
    $(document).ready(function() {
        // Заполнение выпадающего списка студентов при загрузке страницы
        $.ajax({
            type: 'GET',
            url: 'get_students.php', // Замените на путь к файлу, который возвращает список студентов
            success: function(data) {
                // Заполнение выпадающего списка полученными данными
                $('#studentSelect').html(data);
            },
            error: function(xhr, status, error) {
                alert('Произошла ошибка при загрузке списка студентов: ' + status);
            }
        });

        // Обработка выбора студента
        $('#studentSelect').change(function() {
            var studentId = $(this).val();

            // Отправка запроса на сервер для получения номера комнаты выбранного студента
            $.ajax({
                type: 'GET',
                url: 'get_room_number.php', // Замените на путь к файлу, который возвращает номер комнаты
                data: { student_id: studentId },
                success: function(roomNumber) {
                    $('#roomNumberInput').val(roomNumber);
                },
                error: function(xhr, status, error) {
                    alert('Произошла ошибка при получении номера комнаты: ' + status);
                }
            });
        });

        // Обработка отправки формы
        $('#editRoomForm').submit(function(event) {
            event.preventDefault(); // Предотвращаем отправку формы по умолчанию

            // Получаем данные из формы
            var formData = $(this).serialize();

            // Отправка данных на сервер для обновления комнаты студента
            $.ajax({
                type: 'POST',
                url: 'update_room.php', // Замените на путь к обработчику на сервере
                data: formData,
                success: function(response) {
                    alert('Данные успешно обновлены!');
                },
                error: function(xhr, status, error) {
                    alert('Произошла ошибка при отправке запроса: ' + status);
                }
            });
        });
    });
</script>
<button class="button-card" onclick="window.location.href='index.php';">Главное меню</button>
</body>
</html>
