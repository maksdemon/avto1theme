<?php
session_start();
session_destroy();
header("Location: /"); // Перенаправление на главную страницу или другую страницу после выхода
exit;

