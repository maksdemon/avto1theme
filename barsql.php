<?php
require('config/config.php');
if ($mysqli->connect_error) {
    die("Ошибка подключения к базе данных: " . $mysqli->connect_error);
}
$sqlbar= " 
SELECT
    a.name AS name,
    DATE(a.date) AS day,
    MIN(a.price) AS min_price,
    MAX(a.price) AS max_price
FROM
    avto1 a
WHERE
    a.name = '$paramd'
GROUP BY
    DATE(a.date)";

$resultsqlbar= mysqli_query($mysqli, $sqlbar);
$rowssqlbar = mysqli_fetch_all($resultsqlbar, MYSQLI_ASSOC);

header('Content-Type: application/json');
echo json_encode($rowssqlbar);
// Закрываем соединение
$mysqli->close();