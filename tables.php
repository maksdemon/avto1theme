<?php
session_start();
require('config/session.php');
require('config/config.php');
require('get.php');
$sqlcat="SELECT * FROM category ";
$statusFilter = isset($_GET['status-filter']) ? $_GET['status-filter'] : null;
$currentFilterValue = '';
$rowsStartDate = array(
    array(
        "name" => "Item 1",
        "model" => "Model 1",
        "category" => "Category 1"
    ),
    array(
        "name" => "Item 2",
        "model" => "Model 2",
        "category" => "Category 2"
    ),
    array(
        "name" => "Item 3",
        "model" => "Model 3",
        "category" => "Category 3"
    )
);
$sqlStartDate = "
SELECT 
    t.name,
    t.model,
    t.category,
    t.min_price,
    (SELECT date FROM avto1 WHERE name = t.name AND price = t.min_price LIMIT 1) AS min_date,
    t.max_price,
    (SELECT date FROM avto1 WHERE name = t.name AND price = t.max_price LIMIT 1) AS max_date,
    t.avg_price,
    (SELECT price FROM avto1 WHERE name = t.name ORDER BY date DESC LIMIT 1 OFFSET 1) AS prev_price,
    (SELECT price FROM avto1 WHERE name = t.name ORDER BY date DESC LIMIT 1) AS current_price,
    (SELECT url FROM avto1 WHERE name = t.name ORDER BY date DESC LIMIT 1) AS last_url
FROM (
    SELECT
        name,
        category,
        model,
        MIN(price) AS min_price,
        MAX(price) AS max_price,
        AVG(price) AS avg_price
    FROM avto1
    WHERE " . ($modelValue ? "model = $modelValue" : "1") . "
    AND user = $id_user
    GROUP BY  name, category
) AS t
ORDER BY ABS(current_price - min_price);
";

$resultsqlcat = mysqli_query($mysqli, $sqlcat);
$rowssqlcat = mysqli_fetch_all($resultsqlcat, MYSQLI_ASSOC);
$resultStartDate = mysqli_query($mysqli, $sqlStartDate);
$rowsStartDate = mysqli_fetch_all($resultStartDate, MYSQLI_ASSOC);
//$columnNames = array_keys($rowsStartDate[0]);



?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
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
                    <h1 class="h3 mb-2 text-gray-800">Tables</h1>
                    <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
                        For more information about DataTables, please visit the <a target="_blank"
                            href="https://datatables.net">official DataTables documentation</a>.</p>




                        <div class="header clearfix">
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4 ">
                        <div class="card-header py-3">

                            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
                            <form method="GET" action="">
                                <select name="status-filter" id="status-filter">
                                    <?php foreach ($rowssqlcat as $row): ?>
                                        <option value="<?php echo $row['brand_id']; ?>" <?php echo ($row['brand_id'] == $statusFilter) ? 'selected' : ''; ?>><?php echo $row['brand']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" name="submit" value="submit">Выполнить</button>
                            </form>

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="table table-bordered iksweb"  >
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>icon</th>
                                        <th>Category</th>
                                        <th>Min Price</th>
                                        <th>Min Date</th>
                                        <th>Max Price</th>
                                        <th>Max Date</th>
                                        <th>Avg Price</th>
                                        <th>Prev Price</th>
                                        <th>Current Price</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($rowsStartDate as $row): ?>
                                        <tr>
                                            <td><a href="<?php echo $row['last_url']; ?>"><?php
                                                    echo $row['name']; ?></a>
                                            </td>
                                            <td>
                                                <a class="popup-link" href="javascript:void(0);" data-popup data-name="<?php
                                                echo $row['name']; ?>" data-avgprices="<?php
                                                echo htmlspecialchars(json_encode($row['avg_price']), ENT_QUOTES, 'UTF-8'); ?>"><img
                                                            src="img/img.png" alt="Иконка"></a>
                                                <a class="popup-link" href="/page.php?id=<?php echo $row['name']; ?>" ><img
                                                            src="img/podr.png" alt="Иконка"></a>
                                            </td>
                                            <td><?php
                                                echo $row['category']; ?></td>
                                            <td><?php
                                                echo $row['min_price']; ?></td>
                                            <td><?php
                                                echo $row['min_date']; ?></td>
                                            <td><?php
                                                echo $row['max_price']; ?></td>
                                            <td><?php
                                                echo $row['max_date']; ?></td>
                                            <td><?php
                                                echo $row['avg_price']; ?></td>
                                            <td class="<?php
                                            echo ($row['current_price'] < $row['prev_price']) ? 'less-than-prev' : ''; ?>"><?php
                                                echo $row['prev_price']; ?></td>
                                            <td class="<?php
                                            echo ($row['current_price'] <= $row['min_price']) ? 'min-price' : (($row['current_price'] < $row['avg_price']) ? 'current-price' : ''); ?>"><?php
                                                echo $row['current_price']; ?></td>
                                        </tr>
                                    <?php
                                    endforeach; ?>
                                    </tbody>
                                </table>
                                <div class="date-picker">
                                    <label for="start-date">Начальная дата:</label>
                                    <input type="date" id="start-date" name="start-date">
                                    <label for="end-date">Конечная дата:</label>
                                    <input type="date" id="end-date" name="end-date">
                                    <button id="apply-date-range">Применить</button>
                                </div>

                            </div>
                        </div>


                        </div>
                </div>

                </div>
                <!-- /.container-fluid -->


                <div class="container">

                </div>
            </div>
            <!-- End of Main Content -->



            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website <?php echo date("Y"); ?></span>
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
                    <a class="btn btn-primary" href="/logout.php">Logout</a>
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
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>
<script>
    $(document).ready(function () {
        $('#example').DataTable({
            language: {
                search: 'Найти:',
                searchPlaceholder: 'Введите для поиска',
                lengthMenu: 'Показать _MENU_ записей', // Опция выбора количества записей на странице
                info: 'Показано _START_ - _END_ из _TOTAL_ записей', // Информация о показанных записях
                paginate: {
                    first: 'Первая', // Кнопка "Первая"
                    last: 'Последняя', // Кнопка "Последняя"
                    next: 'Следующая', // Кнопка "Следующая"
                    previous: 'Предыдущая' // Кнопка "Предыдущая"
                }
            }
        });
        // Добавляем кнопку фильтрации
        var filterButton = $('<button>Фильтр модели </button>')
            .insertAfter($('#example_wrapper .dataTables_length'));

        // Обработчик события для кнопки
        filterButton.on('click', function() {
            table.column(2).search('Активный').draw();
        });
    });
</script>
</html>
<script src="script.js"></script>
<script src="popup.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>


