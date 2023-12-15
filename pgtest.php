<?php
$param1 = urldecode($_GET['id']);
$mysqli = new mysqli('62.109.2.72', 'avtoparser', '7xXD2rN9i', 'avto1');

$sqlStartDate1 = "
SELECT
DATE_FORMAT(MIN(date), '%Y-%m-%d') AS start_of_week,
MAX(price) AS max_price,
MIN(price) AS min_price
FROM avto1
WHERE name = 'ATE 13.0470-2785.2'
GROUP BY YEAR(date), WEEK(date)
ORDER BY start_of_week;

";

$mysqli = new mysqli('62.109.2.72', 'avtoparser', '7xXD2rN9i', 'avto1');
$resultStartDate1 = mysqli_query($mysqli, $sqlStartDate1);
// Получаем значение параметра 'id' из URL и декодируем его


// Преобразуем значение в JSON
$json_data2 = json_encode($param1);

// Отправляем данные в формате JSON
header('Content-Type: application/json');
echo json_encode($json_data2);
