<?php
defined('DS') ? null : define('DS', 'DIRECTORY_SEPERATOR');
defined('SITE_ROOT') ? null : define('SITE_ROOT', 'C:' . DS . 'wamp' . DS . 'www' . DS . 'photo_gallery');
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT . DS . 'includes');

require_once("Functions.php");
/*
require("interface/Credentials.php");
require("interface/Connection.php");
require("interface/Encryption.php");

require("PhotoGalleryCredentials.php");
require("MySQLDatabase.php");
require("Hmac.php");
require("User.php");
require("Session.php");

require("PasswordHash.php");
*/
?>