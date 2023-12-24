<?php
require('config/config.php');
$firstDate = null;
$lastDate = null;

/*$sqlStartDated = "
SELECT
DATE_FORMAT(MIN(date), '%Y-%m-%d') AS start_of_week,
MAX(price) AS max_price,
MIN(price) AS min_price
FROM avto1
WHERE name = '$paramd' 
GROUP BY YEAR(date), WEEK(date)
ORDER BY start_of_week;

";*/$sqlStartDated = "
SELECT
    DATE_FORMAT(MIN(a.date), '%Y-%m-%d') AS start_of_week,
    MAX(a.price) AS max_price,
    MIN(a.price) AS min_price,
    ROUND((SELECT c.currency FROM curdate c WHERE DATE_FORMAT(c.date_curdate, '%Y-%m-%d') = DATE_FORMAT(MIN(a.date), '%Y-%m-%d')), 3) AS currency,
    ROUND(MIN(a.price) / (SELECT c.currency FROM curdate c WHERE DATE_FORMAT(c.date_curdate, '%Y-%m-%d') = DATE_FORMAT(MIN(a.date), '%Y-%m-%d')), 3) AS usd
FROM
    avto1 a
WHERE
    a.name = '$paramd'
GROUP BY
    YEAR(a.date), WEEK(a.date)
ORDER BY
    start_of_week;
";

$resultStartDated = mysqli_query($mysqli, $sqlStartDated);
// Получаем значение параметра 'id' из URL и декодируем его

$resultData = [];
while ($row = mysqli_fetch_assoc($resultStartDated)) {
    $resultData[] = $row;
}
if (!empty($resultData)) {
    $firstDate = $resultData[0]['start_of_week'];
    $lastDate = $resultData[count($resultData) - 1]['start_of_week'];
}
$response = [
    'paramd' => $paramd,
    'data' => $resultData,
    'first_date' => $firstDate,
    'last_date' => $lastDate
];
header('Content-Type: application/json');
echo json_encode($response);
