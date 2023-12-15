<?php
require('config/config.php');
$param1 = $_GET['id'];
; // Кодирование URL
//$param1 = json_encode($param2);
//$param1 = 'd3b0be83b259a17b04e8c0333e149f8a';
// Устанавливаем заголовок для JSON-формата
echo '<pre>';
print_r($param1);
echo '</pre>';
//$param1 = 'd3b0be83b259a17b04e8c0333e149f8a';
