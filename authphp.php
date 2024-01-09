<?php
// Начать сессию
session_start();
// Установить значение переменной сессии

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["fname"]) && isset($_POST["pas"])) {
        $fname = $_POST["fname"];
        $password = $_POST["pas"];
        // Подключение к базе данных
        $pdo = new mysqli('62.109.2.72', 'avtoparser', '7xXD2rN9i', 'avto1');

        // Проверка подключения
        if ($pdo->connect_error) {
            die("Ошибка подключения: " . $pdo->connect_error);
        }

        // Подготовленный запрос для получения пароля пользователя
        $stmt = $pdo->prepare("SELECT password FROM user WHERE name = ?");
        if ($stmt) {
            $stmt->bind_param("s", $fname);
            $stmt->execute();
            $stmt->store_result(); // Для получения количества строк

            if ($stmt->num_rows == 1) {
                $stmt->bind_result($hashed_password);
                $stmt->fetch();

                // Вывод данных для отладки
                echo "Имя пользователя: " . $fname . "<br>";
                echo "Пароль: " . $password . "<br>";
                echo "Хеш пароля из базы данных: " . $hashed_password . "<br>";

                // Проверка пароля
                if (password_verify($password, $hashed_password)) {

                    $_SESSION['username'] = $fname;
                    if (isset($_SESSION['username'])) {
                        $username = $_SESSION['username'];
                        echo "Имя пользователя: $username";
                    } else {
                        echo "Сессия не содержит имени пользователя";
                    }


                    echo "Вы успешно авторизованы!";
                    header("Location: /index.php");
                    // Редирект на другую страницу или выполнение других действий после успешной авторизации
                } else {
                    echo "Неправильное имя пользователя или пароль!";

                }
            } else {
                echo "Неправильное имя пользователя или пароль!";
            }
        } else {
            echo "Ошибка при подготовке запроса: " . $pdo->error;
        }

        // Закрытие подключения
        $pdo->close();
    } else {
        echo "Ошибка: Поля не заполнены!";
    }
} else {
    echo "Ошибка: данные не были отправлены методом POST.";
}

/*
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["fname"]) && isset($_POST["pas"])) {
        $fname = $_POST["fname"];
        $password = $_POST["pas"];

        // Подключение к базе данных
        $pdo = new mysqli('62.109.2.72', 'avtoparser', '7xXD2rN9i', 'avto1');

        // Проверка подключения
        if ($pdo->connect_error) {
            die("Ошибка подключения: " . $pdo->connect_error);
        }

        // Подготовленный запрос для получения пароля пользователя
        $stmt = $pdo->prepare("SELECT password FROM user WHERE name = ?");
        if ($stmt) {
            $stmt->bind_param("s", $fname);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $hashed_password = $row['password'];


                // Вывод данных для отладки
                echo "Имя пользователя: " . $fname . "<br>";
                echo "Пароль: " . $password . "<br>";
                echo "Хеш пароля из базы данных: " . $hashed_password . "<br>";


                // Проверка пароля
                if (password_verify($password, $hashed_password)) {
                    $_SESSION['username'] = $fname;
                    echo "Вы успешно авторизованы!";
                    // Редирект на другую страницу или выполнение других действий после успешной авторизации
                } else {
                    echo "Неправильное имя пользователя или пароль!";
                }
            } else {
                echo "Неправильное имя пользователя или пароль!";
            }
        } else {
            echo "Ошибка при подготовке запроса: " . $pdo->error;
        }

        // Закрытие подключения
        $pdo->close();
    } else {
        echo "Ошибка: Поля не заполнены!";
    }
} else {
    echo "Ошибка: данные не были отправлены методом POST.";
}

*/