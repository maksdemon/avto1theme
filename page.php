<?php
require('config/config.php');
require('config/session.php');
// Установка значения переменных
$ninetyDaysAgo = date('Y-m-d', strtotime('-90 days'));
$thirtyDaysAgo = date('Y-m-d', strtotime('-30 days'));
$sevenDaysAgo = date('Y-m-d', strtotime('-7 days'));
$allTime = '1900-01-01'; // Начальная дата для всего времени
$sqlStartDate = "
SELECT 
    t.name,
    t.category,
    t.min_price,
    t.max_price,
    t.avg_price,
    (SELECT price FROM avto1 WHERE name = t.name ORDER BY date DESC LIMIT 1 OFFSET 1) AS prev_price,
    (SELECT price FROM avto1 WHERE name = t.name ORDER BY date DESC LIMIT 1) AS current_price,
    (SELECT url FROM avto1 WHERE name = t.name ORDER BY date DESC LIMIT 1) AS last_url
FROM (
    SELECT
        name,
        category,
        MIN(price) AS min_price,
        MAX(price) AS max_price,
        AVG(price) AS avg_price
    FROM avto1
    WHERE date >= ?
    GROUP BY  name, category
) AS t
WHERE t.name = '$paramd';

";
$sqlStartDateusd = "
SELECT
     a.name AS name,
    MIN(a.date) AS first_date,
    MAX(a.date) AS last_date,
    MIN(a.price) AS min_price,
    MAX(a.price) AS last_price,
     DATEDIFF(NOW(), MIN(a.date)) AS days_since_first_date,
    ROUND(MIN(a.price / c.currency), 3) AS min_price_usd,
    ROUND(MAX(a.price / c.currency), 3) AS last_price_usd,
    ROUND(((MAX(a.price / c.currency) - MIN(a.price / c.currency)) / MIN(a.price / c.currency)) * 100, 2) AS price_change_percent_usd,
    ROUND(((MAX(a.price) - MIN(a.price)) / MIN(a.price)) * 100, 2) AS price_change_percent
FROM
    avto1 a
JOIN
    curdate c ON DATE_FORMAT(a.date, '%Y-%m-%d') = DATE_FORMAT(c.date_curdate, '%Y-%m-%d')
WHERE
    a.name = '$paramd'
GROUP BY
    a.name;
";


if ($stmt = mysqli_prepare($mysqli, $sqlStartDate)) {
    mysqli_stmt_bind_param($stmt, "s", $allTime); // "s" для строки
    mysqli_stmt_execute($stmt);
    $resultStartDate = mysqli_stmt_get_result($stmt);
    $rowsStartDate = mysqli_fetch_all($resultStartDate, MYSQLI_ASSOC);
    $columnNames = array_keys($rowsStartDate[0]);

}
$rowsStartDateusd = [];
if ($resultStartDateusd = mysqli_query($mysqli, $sqlStartDateusd)) {
    $rowsStartDateusd = mysqli_fetch_all($resultStartDateusd, MYSQLI_ASSOC);
 //   var_dump($rowsStartDateusd);
} else {
    echo "Ошибка выполнения запроса: " . mysqli_error($mysqli);
}

if ($stmt = mysqli_prepare($mysqli, $sqlStartDate)) {
    mysqli_stmt_bind_param($stmt, "s", $thirtyDaysAgo ); // "s" для строки
    mysqli_stmt_execute($stmt);
    $resultStartDate30 = mysqli_stmt_get_result($stmt);
    $rowsStartDate30 = mysqli_fetch_all($resultStartDate30, MYSQLI_ASSOC);
    $columnNames = array_keys($rowsStartDate30[0]);
}
if ($stmt = mysqli_prepare($mysqli, $sqlStartDate)) {
    mysqli_stmt_bind_param($stmt, "s", $ninetyDaysAgo  ); // "s" для строки
    mysqli_stmt_execute($stmt);
    $resultStartDate90 = mysqli_stmt_get_result($stmt);
    $rowsStartDate90 = mysqli_fetch_all($resultStartDate90, MYSQLI_ASSOC);
    $columnNames = array_keys($rowsStartDate90[0]);
}

?>
<!DOCTYPE html>
<html lang="en">
<?php
echo "<pre>";


echo "</pre>";
echo '<pre>';
//print_r($paramd);
echo '</pre>';

?>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->

    <?php

    include('template/menu.php');
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <?php
            include('template/head.php');
            ?>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">История товара</h1>
                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                            class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                </div>

                <!-- Content Row -->
                <div class="row">

                    <?php
                    foreach ($rowsStartDate as $row): ?>
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-center mb-4">
                                        <div class="text-xs font-weight-bold text-uppercase mb-1"> За все время
                                            <?php
                                            echo $row['name']; ?></div>
                                    </div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Средняя цена</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                                echo $row['avg_price']; ?></div>
                                        </div>
                                        <div class="col mr-2">
                                            <div  class="col text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Мин цена</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                                echo $row['min_price']; ?></div>
                                        </div>
                                        <div class="col mr-2">
                                            <div  class="col text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Макс цена</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                                echo $row['max_price']; ?></div>
                                        </div>
                                        <div class="col mr-2">
                                            <div  class="col text-xs font-weight-bold  text-primary text-uppercase mb-1">
                                                Тек цена</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                                echo $row['current_price']; ?></div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    <?php
                    endforeach; ?>
                    <?php
                    foreach ($rowsStartDate90 as $row): ?>
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-center mb-4">
                                        <div class="text-xs font-weight-bold text-uppercase mb-1"> За 90 дней
                                            <?php
                                            echo $row['name']; ?></div>
                                    </div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Средняя цена</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                                echo $row['avg_price']; ?></div>
                                        </div>
                                        <div class="col mr-2">
                                            <div  class="col text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Мин цена</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                                echo $row['min_price']; ?></div>
                                        </div>
                                        <div class="col mr-2">
                                            <div  class="col text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Макс цена</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                                echo $row['max_price']; ?></div>
                                        </div>
                                        <div class="col mr-2">
                                            <div  class="col text-xs font-weight-bold  text-primary text-uppercase mb-1">
                                                Тек цена</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                                echo $row['current_price']; ?></div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    <?php
                    endforeach; ?>
                    <?php
                    foreach ($rowsStartDate30 as $row): ?>
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-center mb-4">
                                        <div class="text-xs font-weight-bold text-uppercase mb-1"> За 30 дней
                                            <?php
                                            echo $row['name']; ?></div>
                                    </div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Средняя цена</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                                echo $row['avg_price']; ?></div>
                                        </div>
                                        <div class="col mr-2">
                                            <div  class="col text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Мин цена</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                                echo $row['min_price']; ?></div>
                                        </div>
                                        <div class="col mr-2">
                                            <div  class="col text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Макс цена</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                                echo $row['max_price']; ?></div>
                                        </div>
                                        <div class="col mr-2">
                                            <div  class="col text-xs font-weight-bold  text-primary text-uppercase mb-1">
                                                Тек цена</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                                echo $row['current_price']; ?></div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    <?php
                    endforeach; ?>
                    <?php
                    foreach ($rowsStartDateusd as $row): ?>
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-center mb-4">
                                        <div class="text-xs font-weight-bold text-uppercase mb-1">Динамика цены
                                            <?php
                                            echo $row['name']; ?></div>
                                    </div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Дин usd</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                                echo $row['price_change_percent_usd']; ?> %</div>
                                        </div>
                                        <div class="col mr-2">
                                            <div  class="col text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Дин byn</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                                echo $row['price_change_percent']; ?> %</div>
                                        </div>
                                        <div class="col mr-2">
                                            <div  class="col text-xs font-weight-bold text-warning text-uppercase mb-1">
                                               За дней</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                                echo $row['days_since_first_date']; ?></div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>

                    <?php
                    endforeach; ?>
                </div>


                <div id="expandedContent" class="row" style="display: none;">
                    <!-- Дополнительные строки с элементами -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks
                                        </div>
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-auto">
                                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>
                                            </div>
                                            <div class="col">
                                                <div class="progress progress-sm mr-2">
                                                    <div class="progress-bar bg-info" role="progressbar"
                                                         style="width: 50%" aria-valuenow="50" aria-valuemin="0"
                                                         aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks
                                        </div>
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-auto">
                                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>
                                            </div>
                                            <div class="col">
                                                <div class="progress progress-sm mr-2">
                                                    <div class="progress-bar bg-info" role="progressbar"
                                                         style="width: 50%" aria-valuenow="50" aria-valuemin="0"
                                                         aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                </div>
                <!-- Content Row -->

                <div class="row">

                    <!-- Area Chart -->
                    <div class="col-xl-8 col-lg-7">
                        <div class="card shadow mb-4">
                            <!-- Card Header - Dropdown -->
                            <div
                                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                         aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">Dropdown Header:</div>
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#">Something else here</a>
                                    </div>
                                </div>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="chart-area">
                                    <canvas id="myAreaChart1"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pie Chart -->
                    <div class="col-xl-4 col-lg-5">
                        <div class="card shadow mb-4">
                            <!-- Card Header - Dropdown -->
                            <div
                                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Revenue Sources</h6>
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                         aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">Dropdown Header:</div>
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#">Something else here</a>
                                    </div>
                                </div>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="chart-pie pt-4 pb-2">
                                    <canvas id="myPieChart"></canvas>
                                    <div id="centerText" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 20px;"></div>
                                </div>
                                <div class="mt-4 text-center small">
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-primary"></i> Direct
                                        </span>
                                    <span class="mr-2">
                                            <i class="fas fa-circle text-success"></i> Social
                                        </span>
                                    <span class="mr-2">
                                            <i class="fas fa-circle text-info"></i> Referral
                                        </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Дополнительные строки с элементами -->
                <div class="row">
                    <div class="col-xl-12 col-lg-7">
                        <!-- Bar Chart -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Bar Chart</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-bar">
                                    <canvas id="myBarChart"></canvas>
                                </div>
                                <hr>
                                Styling for the bar chart can be found in the
                                <code>/js/demo/chart-bar-demo.js</code> file.
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Content Row -->
                <div class="row">

                    <!-- Content Column -->
                    <div class="col-lg-6 mb-4">

                        <!-- Project Card Example -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Projects</h6>
                            </div>
                            <div class="card-body">
                                <h4 class="small font-weight-bold">Server Migration <span
                                        class="float-right">20%</span></h4>
                                <div class="progress mb-4">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 20%"
                                         aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <h4 class="small font-weight-bold">Sales Tracking <span
                                        class="float-right">40%</span></h4>
                                <div class="progress mb-4">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 40%"
                                         aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <h4 class="small font-weight-bold">Customer Database <span
                                        class="float-right">60%</span></h4>
                                <div class="progress mb-4">
                                    <div class="progress-bar" role="progressbar" style="width: 60%"
                                         aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <h4 class="small font-weight-bold">Payout Details <span
                                        class="float-right">80%</span></h4>
                                <div class="progress mb-4">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 80%"
                                         aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <h4 class="small font-weight-bold">Account Setup <span
                                        class="float-right">Complete!</span></h4>
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%"
                                         aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Color System -->
                        <div class="row">
                            <div class="col-lg-6 mb-4">
                                <div class="card bg-primary text-white shadow">
                                    <div class="card-body">
                                        Primary
                                        <div class="text-white-50 small">#4e73df</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-4">
                                <div class="card bg-success text-white shadow">
                                    <div class="card-body">
                                        Success
                                        <div class="text-white-50 small">#1cc88a</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-4">
                                <div class="card bg-info text-white shadow">
                                    <div class="card-body">
                                        Info
                                        <div class="text-white-50 small">#36b9cc</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-4">
                                <div class="card bg-warning text-white shadow">
                                    <div class="card-body">
                                        Warning
                                        <div class="text-white-50 small">#f6c23e</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-4">
                                <div class="card bg-danger text-white shadow">
                                    <div class="card-body">
                                        Danger
                                        <div class="text-white-50 small">#e74a3b</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-4">
                                <div class="card bg-secondary text-white shadow">
                                    <div class="card-body">
                                        Secondary
                                        <div class="text-white-50 small">#858796</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-4">
                                <div class="card bg-light text-black shadow">
                                    <div class="card-body">
                                        Light
                                        <div class="text-black-50 small">#f8f9fc</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-4">
                                <div class="card bg-dark text-white shadow">
                                    <div class="card-body">
                                        Dark
                                        <div class="text-white-50 small">#5a5c69</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-6 mb-4">

                        <!-- Illustrations -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Illustrations</h6>
                            </div>
                            <div class="card-body">
                                <div class="text-center">
                                    <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                                         src="img/undraw_posting_photo.svg" alt="...">
                                </div>
                                <p>Add some quality, svg illustrations to your project courtesy of <a
                                        target="_blank" rel="nofollow" href="https://undraw.co/">unDraw</a>, a
                                    constantly updated collection of beautiful svg images that you can use
                                    completely free and without attribution!</p>
                                <a target="_blank" rel="nofollow" href="https://undraw.co/">Browse Illustrations on
                                    unDraw &rarr;</a>
                            </div>
                        </div>

                        <!-- Approach -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Development Approach</h6>
                            </div>
                            <div class="card-body">
                                <p>SB Admin 2 makes extensive use of Bootstrap 4 utility classes in order to reduce
                                    CSS bloat and poor page performance. Custom CSS classes are used to create
                                    custom components and custom utility classes.</p>
                                <p class="mb-0">Before working with this theme, you should become familiar with the
                                    Bootstrap framework, especially the utility classes.</p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Your Website 2021</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="login.php">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="vendor/chart.js/Chart.min.js"></script>

<!-- Page level custom scripts -->
<script src="js/demo/chart-area-demo.js"></script>
<script src="js/demo/chart-pie-demo.js"></script>
<script src="js/demo/chart-bar-demo.js"></script>
<div id="js-data" data-param1="<?php echo $param1; ?>"></div>


</body>

</html>