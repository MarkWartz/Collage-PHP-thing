<?php

require __DIR__ . "/includes/functions.php";
require __DIR__ . "/includes/User.php";

if (isset($_SESSION["user_id"])) {
    header("Location: index.php"); // Перенаправление при сессии с аккаунтом 
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    $usersData = loadData("users.json");
    $foundUser = null;
    
    foreach($usersData as $user) {
        if ($user["username"] === $username) {
            $foundUser = $user;
            break;
        }
    }
    if ($foundUser && password_verify($password, $foundUser["password_hash"])) {
        $_SESSION["user_id"] = $foundUser["id"];
        $_SESSION["username"] = $foundUser["username"];
        header("Location: index.php");
    } else {
        $error = "Неверное имя пользователя или пароль";
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
                    <a href="register.php">Регистрация</a>
                </nav>
            </div>
        </header>

        <main class="container">

            <form action="/<?php echo basename(__FILE__); ?>" method="POST" class="form-container">
                <div class="form-group">
                    <label for="username">
                        Имя пользователя:
                        <input placeholder="Username" name="username" reqired />
                    <label>
                </div>

                <div class="form-group">
                    <label for="password" class="form-group">
                        Пароль:
                        <input placeholder="*********" name="password" type="password" reqiured />
                    <label>
                    <span class="span-important"><?php echo $error?></span>
                </div>
                
                <div class="btn-recomendation">
                    <button class="btn btn-primary">Войти</button>
                    <span class="span-recomendation">Ещё нет аккаунта? <a href="/login.php">Зарегистрироваться</a></span>
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