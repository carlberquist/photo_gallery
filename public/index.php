<?php
require("../includes/Initialise.php");

$credentials = new Photo_gallery_credentials();
$database = new MySQLDatabase($credentials);
$user = new User();

$user->set_user_by_id($database, 1);
$var = $user->get_user_var('id');
$var2 = $user->get_user_var('username');
$var1 = $user->get_user_var('password');
$var3 = $user->get_all_user_vars();
$database = null;
?>