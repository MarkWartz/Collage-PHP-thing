<?php

require __DIR__ . "/bootstrap.php";

function loadData($filename) {
    $filepath = DATA_DIR . "/" . $filename;
    $key = str_replace(".json", "", $filename);
    if (!file_exists($filepath)) {
        $emptyData = [$key => []];
        file_put_contents(
            $filepath,
            json_encode($emptyData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
        return [];
    }
    $content = file_get_contents($filepath);
    $data = json_decode($content, true);
    return $data[$key] ?? [];
}


function saveData($filename, $data) {
    $filepath = DATA_DIR . "/" . $filename;
    $key = str_replace(".json", "", $filename);
    $jsonData = [$key => $data];
    $jsonString = json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    return file_put_contents($filepath, $jsonString) !== false;
}

function generateId() {
    return uniqid('post_', true);
}

function getPosts() {
    return loadData('posts.json');
}

function getPostById($id) {
    $posts = getPosts();
    foreach ($posts as $post) {
        if (isset($post['id']) && $post['id'] === $id) {
            return $post;
        }
    }
    return null;
}

?>