<?php

//$query_string = $_SERVER['QUERY_STRING'];
//parse_str($query_string, $query_params);
//$id = $query_params['id'];

//$param1='d3b0be83b259a17b04e8c0333e149f8a';

//$param1 =  (string) $_GET['id'];
$mysqli = new mysqli('62.109.2.72', 'avtoparser', '7xXD2rN9i', 'avto1');
$param1 =  'd3b0be83b259a17b04e8c0333e149f8a';
$sqlStartDate1 = "
SELECT
DATE_FORMAT(MIN(date), '%Y-%m-%d') AS start_of_week,
MAX(price) AS max_price,
MIN(price) AS min_price
FROM avto1
WHERE unique_id = '$param1'
GROUP BY YEAR(date), WEEK(date)
ORDER BY start_of_week;

";
$resultStartDate1 = mysqli_query($mysqli, $sqlStartDate1);
$rowsStartDate1 = mysqli_fetch_all($resultStartDate1, MYSQLI_ASSOC);

// Отправка JSON-ответа без вывода ненужного контента страницы
header('Content-Type: application/json');
echo json_encode($rowsStartDate1);

// Остановка скрипта после отправки JSON




