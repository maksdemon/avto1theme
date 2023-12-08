<?php


$mysqli = new mysqli('62.109.2.72', 'avtoparser', '7xXD2rN9i', 'avto1');

if ($mysqli->connect_error) {
    echo 'Failed to connect to MySQL: ' . $mysqli->connect_error;
} else {
    echo 'Connection established1';
}


//последняя дата для иконки
$sqllastdate = "SELECT DATE FROM avto1 ORDER BY DATE DESC LIMIT 1";
$resultlastdate = mysqli_query($mysqli, $sqllastdate);
$rowlastdate = mysqli_fetch_row($resultlastdate);
$currentDate = new DateTime();
$lastDate = new DateTime($rowlastdate[0]);
$interval = $currentDate->diff($lastDate);

if ($interval->h < 10) {
    $isLessThan10Hours = 'true';
} else {
    $isLessThan10Hours = 'false';
}

echo $isLessThan10Hours; // Выводит true или false


//для даты min
$sqlStartDate = "SELECT MIN(date) AS start_date FROM avto1";
$resultStartDate = mysqli_query($mysqli, $sqlStartDate);
$rowStartDate = mysqli_fetch_assoc($resultStartDate);
$start_date = $rowStartDate['start_date'];


$sql = mysqli_query($mysqli, "SELECT name, category FROM avto1 GROUP BY name, category");
//$result=mysqli_fetch_assoc($sql);
//$result=mysqli_fetch_all($sql);
//print_r($result);
$sql2 = mysqli_query($mysqli, "SELECT *  FROM avto1 LIMIT 5 ");
$result2 = mysqli_fetch_all($sql2, MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["selectedName"])) {
        $selectedName = $_POST["selectedName"];
        $sql3 = mysqli_query(
            $mysqli,
            "SELECT
            DATE(date) AS date_day,
            AVG(price) AS avg_price,
            (SELECT AVG(avg_price) FROM (
                SELECT AVG(price) AS avg_price
                FROM avto1
                WHERE name = '$selectedName'
                GROUP BY DATE(date)
            ) AS subquery) AS overall_avg_price
        FROM avto1
        WHERE name = '$selectedName'
        GROUP BY DATE(date);"
        );

        $result3 = mysqli_fetch_all($sql3, MYSQLI_ASSOC);
    }

    if (isset($_POST["selectedNamemin"])) {
        $selectedNamemin = $_POST["selectedNamemin"];
        $sql4 = mysqli_query(
            $mysqli,
            "SELECT
        MIN(price) AS min_price,
        DATE(date) AS start_date
    FROM
        avto1
    WHERE
        name = '$selectedNamemin'
    GROUP BY
        FLOOR(DATEDIFF(date, (SELECT MIN(date) FROM avto1 WHERE name = '$selectedNamemin')) / 3)
    ORDER BY
        start_date;
    "
        );

        $result4 = mysqli_fetch_all($sql4, MYSQLI_ASSOC);
    }
}

// Дальше можете использовать $result3 и $result4 для вывода данных в HTML или другие действия
///тест графика попапа


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["selectedName"])) {
        $selectedName = $_POST["selectedName"];
        $sql3 = mysqli_query(
            $mysqli,
            "SELECT
            DATE(date) AS date_day,
            AVG(price) AS avg_price,
            (SELECT AVG(avg_price) FROM (
                SELECT AVG(price) AS avg_price
                FROM avto1
                WHERE name = '$selectedName'
                GROUP BY DATE(date)
            ) AS subquery) AS overall_avg_price
        FROM avto1
        WHERE name = '$selectedName'
        GROUP BY DATE(date);"
        );

        $result3 = mysqli_fetch_all($sql3, MYSQLI_ASSOC);
    }

    if (isset($_POST["selectedNamemin"])) {
        $selectedNamemin = $_POST["selectedNamemin"];
        $sql4 = mysqli_query(
            $mysqli,
            "SELECT
        MIN(price) AS min_price,
        DATE(date) AS start_date
    FROM
        avto1
    WHERE
        name = '$selectedNamemin'
    GROUP BY
        FLOOR(DATEDIFF(date, (SELECT MIN(date) FROM avto1 WHERE name = '$selectedNamemin')) / 3)
    ORDER BY
        start_date;
    "
        );

        $result4 = mysqli_fetch_all($sql4, MYSQLI_ASSOC);
    }
}


if (isset($_GET['product'])) {
    $productName = $_GET['product'];

    // SQL-запрос для получения данных
    $query = "SELECT
                DATE(date) AS date_day,
                AVG(price) AS avg_price
            FROM avto1
            WHERE name = ?
            GROUP BY DATE(date)";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $productName);
        $stmt->execute();
        $result = $stmt->get_result();

        $resultArray = array();
        while ($row = $result->fetch_assoc()) {
            $resultArray[] = $row;
        }

        $stmt->close();

        // Отправляем данные в формате JSON
        header('Content-Type: application/json');
        echo json_encode($resultArray);
    } else {
        echo "Error: " . $mysqli->error;
    }
}

// Закрываем соединение с базой данных


?>
