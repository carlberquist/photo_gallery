<?php
require("../../includes/Initialise.php");

$credentials = new Photo_gallery_credentials();
$connection = new MySQLDatabase($credentials);
$session = new Session();
$user = new User();

if (!$session->get_logged_in()) {
    header("Location: login.php");
    exit;
}

$user->set_user_by_id($connection, $_SESSION['user_uid']);
$user->set_user_first_last();

?>
<?php include('../layouts/admin_header.php');?>
    <div>Welcome: <?php echo $user->get_user_var('usr_first_last');?></div>
<h2>Menu</h2>
    <?php include('../layouts/admin_footer.php');?>