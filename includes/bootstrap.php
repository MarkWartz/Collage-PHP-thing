<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define("ROOT_DIR", __DIR__ . "/..");
define("DATA_DIR", ROOT_DIR . "/data");
define("UPLOADS_DIR", ROOT_DIR . "/uploads");

?>