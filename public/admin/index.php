<?php
require("../../includes/Session.php");
require("../../includes/interface/Connection.php");
require("../../includes/interface/Credentials.php");
require("../../includes/PhotoGalleryCredentials.php");
require("../../includes/MySQLDatabase.php");
require("../../includes/User.php");

$credentials = new Photo_gallery_credentials();
$connection = new MySQLDatabase($credentials, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));
$session = new Session();
$user = new User();

if (!$session->get_logged_in()) {
    header("Location: login.php");
    exit;
}

$user->set_user_by_id($connection, $_SESSION['user_uid']);
$user->set_user_first_last();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Photo Gallery</title>
    <link rel="stylesheet" type="text/css" media="all" href="../../stylesheets/main.css" />
</head>
<body>
    <div id = "header">
<h1>Photo Gallery</h1>
    </div>
    <div>Welcome: <?php echo $user->get_user_var('usr_first_last');?></div>
    <div id = "main">
<h2>Menu</h2>
    </div>
    <div id = "footer">
    Copyright <?php echo date("Y", time()); ?>, Carl Berquist
    </div>
</body>
</html>