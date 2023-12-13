<?php
// Код для подключения к базе данных
// ...
require('config/config.php');
// Запрос к базе данных
$sqlStartDate1 = "
SELECT
DATE_FORMAT(MIN(date), '%Y-%m-%d') AS start_of_week,
MAX(price) AS max_price,
MIN(price) AS min_price
FROM avto1
WHERE name = 'BOSCH 3397007620'
GROUP BY YEAR(date), WEEK(date)
ORDER BY start_of_week;

";
$resultStartDate1 = mysqli_query($mysqli, $sqlStartDate1);
$rowsStartDate1 = mysqli_fetch_all($resultStartDate1, MYSQLI_ASSOC);

// Отправка JSON-ответа без вывода ненужного контента страницы
header('Content-Type: application/json');
echo json_encode($rowsStartDate1);
?>



