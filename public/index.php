<?php
require("../includes/Initialise.php");
try {
    $credentials = new PhotoGalleryCredentials();
    $connection = new MySQLDatabase($credentials);
    $encryption = new PasswordHash();
    $user = new User();

    $user->set_user_by_id($connection, 1);
} catch (Exception $e) {
    $error_msg = $e->getMessage();
}
?>
<?php include('layouts/header.php'); ?>
<h2><?php if (isset($error_msg)) echo($error_msg); ?></h2>
<div>Welcome: <?php echo $user->get_user_var('usr_first_last'); ?></div>
<h2>Menu</h2>
<?php include('layouts/footer.php'); ?>