<?php
require("../includes/interface/Credentials.php");
require("../includes/interface/Connection.php");
require("../includes/PhotoGalleryCredentials.php");
require("../includes/MySQLDatabase.php");
require("../includes/User.php");

$credentials = new Photo_gallery_credentials();
$database = new MySQLDatabase($credentials, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));
$user = new User();

$user->set_user_by_id($database, 1);
$var = $user->get_user_var('id');
$var2 = $user->get_user_var('username');
$var1 = $user->get_user_var('password');
$var3 = $user->get_all_user_vars();
$database = null;
?>