<?php

require __DIR__ . "/includes/functions.php";
require __DIR__ . "/includes/User.php";

if (isset($_SESSION["user_id"])) {
    header("Location: index.php"); // Перенаправление при сессии с аккаунтом 
    exit;
}

$isFieldError = false;
$generalError = "";
$fieldErrors = [
    "username" => "",
    "password" => "",
    "password_confirm" => ""
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $password_confirm = $_POST["password_confirm"] ?? "";

    if (empty($username) || empty($email) || empty($password) || empty($password_confirm)) {
        $generalError = "Заполните все обязательные поля";
        $isFieldError = true;
    }

    if(strlen($username) < 3) {
        $fieldErrors["username"] = "Имя пользователя должно содержать минимум 3 символа ";
        $isFieldError = true;
    }

    if(strlen($password) < 6) {
        $fieldErrors["password"] = "Пароль должен содержать минимум 6 символов";
        $isFieldError = true;
    }

    if($password !== $password_confirm) {
        $fieldErrors["password_confirm"] = "Пароли не совпадают";
        $isFieldError = true;
    }
    
    if ($isFieldError !== true) {
        $usersData = loadData("users.json");
        foreach($usersData as $user) {
            if ($user["username"] === $username) {
                $generalError = "Пользователь с таким именем уже существует ";
                break;
            }
        }

        if (!array_filter($fieldErrors) && !$generalError) {
            $user = new User($username, $email, $password);
            $usersData[] = $user->toArray();
            if (saveData("users.json", $usersData)) {
                header("Location: index.php");
            } else {
                $generalError = "Ошибка при сохранении данных";
            }
        }

    }
}

?>



<html lang="ru">
    <head>
        <title>Мой блог</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
        <header class="header">
            <div class="container">
                <h1>Мой интернет-блог</h1>
                <nav class="nav">
                    <a href="index.php">Главная</a>
                    <a href="login.php">Войти</a>
                </nav>
            </div>
        </header>

        <main class="container">

            <form action="/<?php echo basename(__FILE__); ?>" method="POST" class="form-container">
                <div class="form-group">
                    <label for="username">
                        Имя пользователя:
                        <span class="span-info">Не менее 3 символов</span>
                        <input placeholder="Username" name="username" required />
                        <span class="span-important">
                            <?php echo $fieldErrors["username"]; ?>
                        </span>
                    </label>
                </div>
                

                <div class="form-group">
                    <label for="email">
                        Email:
                        <input placeholder="example@example.com" name="email" type="email" required />
                    </label>
                </div>
                

                <div class="form-group">
                    <label for="password">
                        Пароль:
                        <span class="span-info">Не менее 6 символов</span>
                        <input placeholder="*********" name="password" type="password" required />
                        <span class="span-important">
                            <?php echo $fieldErrors["password"]; ?>
                        </span>
                    </label>
                </div>
                

                <div class="form-group">
                    <label for="password_confirm">
                        Подтверждение пароля:
                        <input placeholder="*********" name="password_confirm" type="password" required />
                        <span class="span-important">
                            <?php echo $fieldErrors["password_confirm"]; ?>
                        </span>
                    </label>
                </div>
                
                <span class="span-important"><?php echo $generalError; ?></span>

                <div class="btn-recomendation">
                    <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
                    <span class="span-recomendation">Уже есть аккаунт? <a href="/login.php">Войти</a></span>
                </div>

            </form>

        </main>

        <footer class="footer">
            <div class="container">
                <p>Мой блог © 2025 - Практический проект на PHP</p>
            </div>
        </footer>
    </body>
</html>
