<?php
var_dump($_POST);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверяем, что данные присутствуют и не пустые
    if (isset($_POST["fname"]) && !empty($_POST["fname"]) && isset($_POST["parol"]) && !empty($_POST["parol"])) {
        $fname = $_POST["fname"];
        $password = $_POST["parol"];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $pdo = new mysqli('62.109.2.72', 'avtoparser', '7xXD2rN9i', 'avto1');

        // Проверка, существует ли пользователь с таким именем
        $check_stmt = $pdo->prepare("SELECT name FROM user WHERE name = ?");
        if ($check_stmt) {
            $check_stmt->bind_param("s", $fname);
            $check_stmt->execute();
            $check_stmt->store_result();

            if ($check_stmt->num_rows > 0) {
                echo "Пользователь с именем '$fname' уже существует!";
            } else {
                // Подготовленный запрос для добавления пользователя
                $insert_stmt = $pdo->prepare("INSERT INTO user (name, password) VALUES (?, ?)");
                if ($insert_stmt) {
                    $insert_stmt->bind_param("ss", $fname, $hashed_password);
                    $insert_stmt->execute();

                    if ($insert_stmt->affected_rows > 0) {
                        echo "Пользователь успешно добавлен!";
                        header("Location: /index.php");
                    } else {
                        echo "Ошибка при добавлении пользователя!";
                    }
                } else {
                    echo "Ошибка при подготовке запроса добавления пользователя: " . $pdo->error;
                }
            }
        } else {
            echo "Ошибка при подготовке запроса проверки пользователя: " . $pdo->error;
        }

        // Закрытие запросов и соединения с базой данных
        if (isset($check_stmt)) {
            $check_stmt->close();
        }
        if (isset($insert_stmt)) {
            $insert_stmt->close();
        }
        $pdo->close();
    } else {
        echo "Ошибка: Поля не заполнены!";
    }
} else {
    echo "Ошибка: данные не были отправлены методом POST.";
}