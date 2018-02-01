<?php
defined('DS') ? null : define('DS, DIRECTORY_SEPERATOR');
defined('SITE_ROOT') ? null : define('SITE_ROOT', DS . 'wamp' . DS . 'www' . DS . 'photo_gallery');
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT . DS . 'includes');

require(LIB_PATH . "interface/Credentials.php");
require(LIB_PATH . "interface/Connection.php");
require(LIB_PATH . "interface/Encryption.php");

require(LIB_PATH . "PhotoGalleryCredentials.php");
require(LIB_PATH . "MySQLDatabase.php");
require(LIB_PATH . "Hmac.php");
require(LIB_PATH . "User.php");
require(LIB_PATH . "Session.php");
require(LIB_PATH . "Functions.php");
?>