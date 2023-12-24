<?php
require('grafdemosql.php');
require('config/config.php');
$url = "https://api.nbrb.by/ExRates/Rates/Dynamics/431?startDate=2023-01-01&endDate=2023-12-24";
//$url = "https://api.nbrb.by/ExRates/Rates/Dynamics/431?startDate=" . urlencode($firstDate) . "&endDate=" . urlencode($lastDate);

$data = file_get_contents($url);

if ($data !== false) {
    $decoded_data = json_decode($data);

    // Вывод данных
    echo "<pre>";
    var_dump($decoded_data);
    echo "</pre>";
} else {
    echo "Не удалось получить данные.";
}

foreach ($decoded_data as $item) {
    $date = $item->Date;
    $rate = $item->Cur_OfficialRate;
    $sql = "INSERT INTO curdate (date_curdate, currency) VALUES ('$date', $rate)";

    if ($mysqli->query($sql) === TRUE) {
        echo "Запись успешно добавлена в базу данных";
    } else {
        echo "Ошибка: " . $sql . "<br>" . $mysqli->error;
    }
}