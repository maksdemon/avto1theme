<?php

require('config/config.php');

$sqlStartDate ="
SELECT 
    DATE_FORMAT(MIN(date), '%Y-%m-%d') AS start_of_week,
    MAX(price) AS max_price,
    MIN(price) AS min_price
FROM avto1
WHERE name = 'BOSCH 3397007620'
GROUP BY YEAR(date), WEEK(date)
ORDER BY start_of_week";

$resultStartDate = mysqli_query($mysqli, $sqlStartDate);
$rowsStartDate = mysqli_fetch_all($resultStartDate, MYSQLI_ASSOC);
$columnNames = array_keys($rowsStartDate[0]);

echo "<pre>";
//var_dump($rowsStartDate );
echo "</pre>";
?>
<!DOCTYPE html>
<html lang="en">
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<link href="css/style.css" rel="stylesheet">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>SB Admin 2 - Tables</title>
    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>




<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>



<!-- Page level plugins -->
<script src="vendor/chart.js/Chart.min.js"></script>


</html>
