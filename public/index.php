<?php
require("../includes/Initialise.php");

$credentials = new Photo_gallery_credentials();
$database = new MySQLDatabase($credentials);
$user = new User();
$encryption = new Encryption();

$user->insert_user($database,$encryption, 'fred', 'Snowy123', 'fred', 'fred');
$database = null;
?>