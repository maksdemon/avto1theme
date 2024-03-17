<?php
require('config/config.php');
if (isset($_GET['id'])) {
    $param1 = $_GET['id'];
}
$modelValue = null;
; // Кодирование URL
//$param1 = json_encode($param2);
//$param1 = 'd3b0be83b259a17b04e8c0333e149f8a';
// Устанавливаем заголовок для JSON-формата

//$param1 = 'd3b0be83b259a17b04e8c0333e149f8a';
if (isset($_GET['submit'])) {
    $modelValue = $_GET['status-filter'];
    // Теперь $modelValue содержит выбранное значение фильтра
}

