<?php
require('config/config.php');
$paramd = isset($_GET['id']) ? $_GET['id'] : "0bd965d14fd96884e5f1f3604f826494";
$sqlStartDated = "
SELECT
DATE_FORMAT(MIN(date), '%Y-%m-%d') AS start_of_week,
MAX(price) AS max_price,
MIN(price) AS min_price
FROM avto1
WHERE unique_id = '$paramd' 
GROUP BY YEAR(date), WEEK(date)
ORDER BY start_of_week;

";

$mysqli = new mysqli('62.109.2.72', 'avtoparser', '7xXD2rN9i', 'avto1');
$resultStartDated = mysqli_query($mysqli, $sqlStartDated);
// Получаем значение параметра 'id' из URL и декодируем его

$resultData = [];
while ($row = mysqli_fetch_assoc($resultStartDated)) {
    $resultData[] = $row;
}
$response = [
    'paramd' => $paramd,
    'data' => $resultData
];
header('Content-Type: application/json');
echo json_encode($response);
