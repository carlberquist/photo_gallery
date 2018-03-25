<?php
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', 'C:' . DS . 'wamp' . DS . 'www' . DS . 'photo_gallery' . DS);
defined('PUBLIC_FOLDER') ? null : define('PUBLIC_FOLDER', SITE_ROOT . "public" . DS);
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT . 'includes' . DS);
defined('IMG_PATH') ? null : define('IMG_PATH', SITE_ROOT . 'public' . DS . 'images');
require_once("Functions.php");
?>