<?php
$mysqli = new mysqli('localhost', 'root', '', '3labrezv');

if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: " . $mysqli->connect_error;
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['student_id'])) {
        $student_id = $_GET['student_id'];

        $sql = "SELECT НомерКомнаты FROM комнаты WHERE КодСтудента=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $stmt->bind_result($room_number);
        $stmt->fetch();

        echo $room_number;

        $stmt->close();
    }
}

$mysqli->close();
?>
