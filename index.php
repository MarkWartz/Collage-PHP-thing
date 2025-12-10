<?php

require __DIR__ . "/includes/functions.php";
$posts = getPosts();

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
                    <?php if (!isset($_SESSION["user_id"])) { ?>
                        <a href="login.php">Войти</a>
                        <a href="register.php">Регистрация</a>
                    <?php } else { ?>
                        <a href="logout.php">Выход (<?php echo $_SESSION["username"];?>)</a>
                    <?php } ?>
                    <a href="create.php">Создать пост</a>
                </nav>
            </div>
        </header>

        <main class="container">
            <?php if (empty($posts)) { ?>
                <div class="empty-state">
                    <p>Пока здесь нет ни одной записи.</p>
                </div>

                <?php } else { ?>
                <div class="posts-list">
                    <?php foreach ($posts as $post) { ?>
                        <article class="post-card">
                            <h2><a href="post.php?id=<?= $post['id'] ?>"><?= $post['title'] ?></a></h2>
                            <div class="post-meta">Автор ID: <?= $post['author_id'] ?? 'Неизвестен' ?> |
                                <?= $post['created_at'] ?? date('Y-m-d') ?>
                            </div>
                            <p class="post-preview">
                                <?= substr($post['content'], 0, 150) ?>
                            </p>
                        </article>
                    <?php } ?>
                </div>
            <?php } ?>
        </main>

        <footer class="footer">
            <div class="container">
                <p>Мой блог © 2025 - Практический проект на PHP</p>
            </div>
        </footer>
    </body>
</html>