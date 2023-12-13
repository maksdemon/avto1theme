<?php
// Код для подключения к базе данных
// ...
require('config/config.php');
// Запрос к базе данных
$sqlStartDate1 = "
    SELECT
        DATE(date) AS date_day,
        AVG(price) AS avg_price
    FROM avto1
    WHERE name = 'BOSCH 3397007620'
    GROUP BY DATE(date)
";
$resultStartDate1 = mysqli_query($mysqli, $sqlStartDate1);
$rowsStartDate1 = mysqli_fetch_all($resultStartDate1, MYSQLI_ASSOC);

// Отправка JSON-ответа без вывода ненужного контента страницы
header('Content-Type: application/json');
echo json_encode($rowsStartDate1);
?>
