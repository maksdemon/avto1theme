<?php
session_start();
if (!isset($_SESSION['username'])) {
    // Если сессия не существует, перенаправляем пользователя на страницу входа или выводим сообщение об ошибке
    header("Location: login.php"); // Замените "login.php" на страницу вашей авторизации
    exit();
}