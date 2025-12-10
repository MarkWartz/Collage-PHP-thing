<?php

require __DIR__ . "/includes/functions.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$error = "";
$success = "";


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"] ?? "");
    $content = trim($_POST["content"] ?? "");
    $mediaPath = null;

    if (empty($title) || empty($content)) {
        $error = 'Заполните заголовок и содержимое записи';
    }
    elseif (strlen($title) < 5) {
        $error = 'Заголовок должен содержать минимум 5 символов';
    }
    elseif (strlen($content) < 10) {
        $error = 'Содержимое должно содержать минимум 10 символов';
    }

    elseif (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image'];

        // Проверка размера (макс. 2Mb)
        $maxSize = 2 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            $error = 'Размер файла не должен превышать 2Mb';
        }

        // Проверка MIME-типа и расширения
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $file['tmp_name']);
        finfo_close($fileInfo);
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($mimeType, $allowedTypes) || !in_array($ext, $allowedExts)) {
            $error = 'Разрешены только изображения JPG, PNG, GIF';
        }
            
        else {
            // Генерация уникального имени файла
            $filename = uniqid('img_', true) . '.' . $ext;
            $uploadPath = UPLOADS_DIR . '/' . $filename;

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $mediaPath = 'uploads/' . $filename;
            }
            else {
                $error = 'Ошибка при загрузке файла';
            }
        }
    }


    if (!$error) {
        $newPost = [
            'id' => generateId(),
            'title' => $title,
            'content' => $content,
            'author_id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'], // для удобного вывода
            'created_at' => date('Y-m-d H:i:s'),
            'media' => $mediaPath ? [$mediaPath] : []
        ];

        $posts = getPosts();
        $posts[] = $newPost;
        if (saveData('posts.json', $posts)) {
            header('Location: post.php?id=' . $newPost['id']); // Перенаправляем на страницу просмотра поста
            exit;
        }
        else {
            $error = 'Ошибка при сохранении записи';
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
                    <a href="logout.php">Выход (<?php echo $_SESSION["username"];?>)</a>
                </nav>
            </div>
        </header>

        <main class="container">
            <form action="/<?php echo basename(__FILE__); ?>" method="POST" enctype="multipart/form-data" class="form-container">

                <div class="form-group">
                    <label for="title">
                        Заголовок записи:
                        <input placeholder="Название" name="title" type="text" required />
                    </label>
                </div>

                <div class="form-group">
                    <label for="content">
                        Содержимое:
                        <textarea placeholder="Введите содержимое поста" name="content" required></textarea>
                    </label>
                </div>
                
                <div class="form-group">
                    <label for="image">
                        Изображение<span class="span-important">*</span>:
                        <input name="image" type="file" />
                    </label>
                </div>

                <button class="btn btn-primary">Опубликовать запись</button>

            </form>

        </main>
        
        <footer class="footer">
            <div class="container">
                <p>Мой блог © 2025 - Практический проект на PHP</p>
            </div>
        </footer>
    </body>
</html>