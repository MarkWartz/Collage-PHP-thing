<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION = [];
session_destroy();

header("Location: index.php");
exit;

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
                    <a href="register.php">Регистрация</a>
                    <a href="create.php">Создать пост</a>
                </nav>
            </div>
        </header>
        <main class="container">
</main>
        <footer class="footer">
            <div class="container">
                <p>Мой блог © 2025 - Практический проект на PHP</p>
            </div>
        </footer>
    </body>
</html>