<?php

require('config/config.php');


echo "<pre>";
//var_dump($rowsStartDate );
echo "</pre>";
?>
<!DOCTYPE html>
<html lang="en">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<head>
    <title>График</title>
    <!-- Подключаем библиотеку Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="chart-pie pt-4 pb-2">
    <canvas id="mylineChart"></canvas>
</div>

<script>
    // Ваши данные для линейного графика (пример данных)
    var data = {
        labels: ["Янв", "Фев", "Март", "Апр", "Май", "Июн"],
        datasets: [
            {
                label: 'Продажи',
                data: [120, 150, 180, 170, 200, 190], // Пример данных для первой линии
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2
            },
            {
                label: 'Прибыль',
                data: [50, 80, 60, 70, 90, 85], // Пример данных для второй линии
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2
            }
        ]
    };

    // Опции для линейного графика
    var options = {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    };

    // Получаем элемент canvas, к которому привязан график
    var ctx = document.getElementById("mylineChart").getContext("2d");

    // Создаем линейный график
    var myLineChart = new Chart(ctx, {
        type: 'line',
        data: data,
        options: options
    });
</script>


</body>
</html>


<!-- Page level plugins -->
<script src="vendor/chart.js/Chart.min.js"></script>


</html>
