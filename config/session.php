<?php
session_start();
if (!isset($_SESSION['username'])) {
    // Если сессия не существует, перенаправляем пользователя на страницу входа или выводим сообщение об ошибке
    header("Location: login.php"); // Замените "login.php" на страницу вашей авторизации
    exit();
}
else{
     $fname =$_SESSION['username']  ;
    $id_user=$_SESSION['id'];
    //$id_user=1;
}