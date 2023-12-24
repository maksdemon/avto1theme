<?php

require('config/config.php');

$query = "SELECT
    DATEDIFF(MAX(date), MIN(date)) + 1 AS total_days,
    COUNT(DISTINCT CASE WHEN price IS NOT NULL THEN DATE(date) END) AS days_with_price,
    (DATEDIFF(MAX(date), MIN(date)) + 1) - COUNT(DISTINCT CASE WHEN price IS NOT NULL THEN DATE(date) END) AS days_without_price
FROM avto1
WHERE name = ?;
";
// Предполагая, что $mysqli - ваше подключение к базе данных
$stmt = mysqli_prepare($mysqli, $query);
if ($stmt) {
    $name = "ATE 13.0470-2785.2"; // Значение фильтрации
    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $datausd = [
            'totalDays' => $row['total_days'],
            'daysWithPrice' => $row['days_with_price'],
            'daysWithoutPrice' => $row['days_without_price'],
        ];

        echo json_encode($datausd); // Вывод в формате JSON
    } else {
        echo json_encode(["error" => "No results found."]);
    }
} else {
    echo json_encode(["error" => "Error in query execution."]);
}
