<?php
// Получаем значение name из GET-параметра
$productName = $_GET['product'];

// Создаем подключение к базе данных (если оно не было создано ранее)
$mysqli = new mysqli('62.109.2.72', 'avtoparser', '7xXD2rN9i', 'avto1');

//// Проверяем соединение на ошибки
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Готовим SQL-запрос с использованием подготовленного выражения
$query = "SELECT
            DATE(date) AS date_day,
            AVG(price) AS avg_price
        FROM avto1
        WHERE name = ?
        GROUP BY DATE(date)";

if ($stmt = $mysqli->prepare($query)) {
    // Привязываем параметры к подготовленному выражению
    $stmt->bind_param("s", $productName);

    // Выполняем запрос
    $stmt->execute();

    // Получаем результаты запроса
    $result = $stmt->get_result();

    // Преобразуем результаты в массив данных
    $resultArray = array();
    while ($row = $result->fetch_assoc()) {
        $resultArray[] = $row;
    }

    // Закрываем подготовленное выражение
    $stmt->close();

    // Отправляем данные в формате JSON
    header('Content-Type: application/json');
    echo json_encode($resultArray);
} else {
    // Если произошла ошибка при подготовке запроса
    echo "Error: " . $mysqli->error;
}

// Закрываем соединение с базой данных
$mysqli->close();
?>