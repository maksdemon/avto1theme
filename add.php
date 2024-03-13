<?php

require('config/config.php');


// Переменные для хранения значений полей
$url = $notes = "";
$insertQuery = "";
$errors = array();
// Обработка отправленной формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверка поля URL
    if (empty($_POST["url"])) {
        $errors["url"] = "URL is required";
    } else {
        $url = test_input($_POST["url"]);
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $errors["url"] = "Invalid URL format";
        }
    }
    if (!empty($_POST["select_id"])) {
        $idcat = test_input($_POST["select_id"]);
    }

    // Проверка поля заметок
    if (!empty($_POST["notes"])) {
        $notes = test_input($_POST["notes"]);
    }

    // Если нет ошибок, можно выполнять дополнительные действия, например, сохранение в базе данных
    if (empty($errors)) {
        $insertQuery = "INSERT INTO unput (urls,descr,cat) VALUES ('$url', '$notes','$idcat')";
        if (mysqli_query($mysqli, $insertQuery)) {
            // Успешное добавление
            $idcat= $url = $notes = "";
            //   echo $insertQuery;
            echo "URL успешно добавлен!";
            // echo($errors);
            header("Location: tables.php");
            //echo($insertQuery);
        } else {
            // Ошибка при добавлении
            echo "Ошибка при добавлении URL: " . mysqli_error($mysqli);
        }
        // echo(mysqli_error($mysqli));


        //print_r($errors);
        ;
    }
    print_r($errors);
}
// Функция для обработки введенных данных
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "<pre>";
    print_r($_POST); // или var_dump($_POST);
    echo "</pre>";
}
//echo $insertQuery;

/*echo'<pre>';
print_r($result3);
echo '</pre>';
*/
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Blank</title>

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
                    <h2>Add URL and Notes</h2>
                    <div id="layoutSidenav_content">
                        <main>
                            <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
                                <div class="container-fluid px-4">
                                    <div class="page-header-content">
                                    </div>
                                </div>
                            </header>
                            <!-- Main page content-->
                            <div class="container-fluid px-4">
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <div id="solid">
                                    <div class="card mb-4">
                                        <div class="card-header">Custom Solid Form Controls</div>
                                        <div class="card-body">
                                            <!-- Component Preview-->
                                            <div class="sbp-preview">
                                                <div class="sbp-preview-content">

                                                        <div class="mb-3">
                                                            <label for="url">url</label>
                                                            <input class="areas form-control form-control-solid" id="url" type="url" name="url" placeholder="URL" value="<?php echo isset($_POST['url']) ? htmlspecialchars($_POST['url']) : ''; ?>"/>
                                                        </div>
                                                        <div class="mb-3">

                                                            <label for="exampleFormControlSelect1">Принадлежность php</label>
                                                            <select class="form-control form-control-solid" id="exampleFormControlSelect1" name="select_id">

                                                                <?php
                                                                foreach ($rowsqlcat as $row): ?>
                                                                    <option value="<?= $row["brand_id"] ?>">
                                                                        <?= $row["brand"]; ?>
                                                                    </option>
                                                                <?php
                                                                endforeach; ?>
                                                            </select>
                                                        </div>

                                                    <div class="mb-0">
                                                            <label for="notes">Example textarea</label>
                                                            <!--  <textarea class="form-control form-control-solid" id="exampleFormControlTextarea1" rows="3"></textarea>-->
                                                            <textarea class="form-control form-control-solid" name="notes" rows="3" cols="50" id="notes"><?php   echo $notes; ?></textarea>
                                                            <br><br>
                                                        </div>


                                                </div>
                                                <div class="sbp-preview-code">
                                                    <!-- Code sample-->

                                                    <!-- Tab panes-->

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <button class="btn btn-primary" type="submit" name="submit">Добавить</button>
                                    <input type="hidden" name="input_value" value="Значение для input">

                                </form>

                            </div>



                        </main>

                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
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

</body>

</html>