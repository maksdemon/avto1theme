<?php
//require('grafdemosql.php');
require('config/config.php');
$today = date("Y-m-d");
//$url = "https://api.nbrb.by/ExRates/Rates/Dynamics/431?startDate=2023-01-01&endDate=2023-12-24";
$url = "https://api.nbrb.by/ExRates/Rates/Dynamics/431?startDate=2023-01-01&endDate=$today";
//$url = "https://api.nbrb.by/ExRates/Rates/Dynamics/431?startDate=" . urlencode($firstDate) . "&endDate=" . urlencode($lastDate);

$data = file_get_contents($url);
/*
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
*/
if ($data !== false) {
    $decoded_data = json_decode($data);

    // Подключение к базе данных


    foreach ($decoded_data as $item) {
        $date = $item->Date;
        $rate = $item->Cur_OfficialRate;

        // Проверка, существует ли запись с текущей датой в базе данных
        $check_sql = "SELECT * FROM curdate WHERE date_curdate = '$date'";
        $result = $mysqli->query($check_sql);

        if ($result->num_rows === 0) {
            $insert_sql = "INSERT INTO curdate (date_curdate, currency) VALUES ('$date', $rate)";
            if ($mysqli->query($insert_sql) === TRUE) {
                echo "Запись успешно добавлена в базу данных для даты $date<br>";
            } else {
                echo "Ошибка: " . $insert_sql . "<br>" . $mysqli->error;
            }
        } else {
            echo "Запись для даты $date уже существует в базе данных<br>";
        }
    }
    $mysqli->close();
} else {
    echo "Не удалось получить данные.";
}
