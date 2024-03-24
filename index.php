<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление базой данных</title>
    <link rel="stylesheet" href="style.css">
    
</head>
<body>
    <div class="container">
        <h1>Управление базой данных</h1>
        <p>Данная БД создана для быстрого поиска информации о студентах, добавления, изменения и удаления данных о них. В данной БД пользователь может найти информацию о студентах, вывести список студентов определенной группы, узнать о комнате проживания студента, общежитии, выданном инвентаре, а также платежную информацию.</p>
        <form action="/find.php" method="get">
            <input type="submit" name="search" value="Поиск">
        </form>
        <form action="/stats.php" method="get">
            <input type="submit" name="view_statistics" value="Статистика">
        </form>
        <form action="/add_student.php" method="get">
            <input type="submit" name="add_student" value="Добавить студента">
        </form>
        <form action="edit_student.php" method="get">
            <input type="submit" name="edit_student" value="Редактировать студента">
        </form>
        <form action="allTables.php" method="get">
            <input type="submit" name="delete_student" value="Посмотреть все записи">
        </form>
        <hr>
        <footer>
            <p>Выполнил: студент группы ИВТ2-Б21 Резвяков Никита Евгеньевич</p>
        </footer>
    </div>
</body>
</html>
