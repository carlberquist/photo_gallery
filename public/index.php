<?php
require("../includes/Initialise.php");

$credentials = new Photo_gallery_credentials();
$database = new MySQLDatabase($credentials);
$user = new User();
$encryption = new PasswordHash();

$user->insert_user($database, $encryption, 'bob', 'Snowy123', 'bob', 'bob');
$user->set_user_by_username($database, 'bob', $encryption, 'Snowy1234');
$test = $user->get_user_var('password');
$database = null;
?>