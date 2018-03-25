<?php
require("../../includes/Initialise.php");

$credentials = new PhotoGalleryCredentials();
$connection = new MySQLDatabase($credentials);
$encryption = new PasswordHash();
$session = new Session();
$user = new User($connection, $encryption);
$logger = new Logger();

if (!$session->check_login() || isset($_GET['logout'])) {
    $session->logout();
    header("Location: login.php");
    exit;
}

$user->set_user_by_id($connection, $_SESSION['user_uid']);

if (isset($_GET['clear'])) {
    $logger->clear_logfile($user);
    header("Location: index.php");
    exit;
}

$logger->create_log_file($user->usr_first_last, 'Successfully logged in');
?>
<?php include('../layouts/admin_header.php'); ?>
<div>Welcome: <?php echo $user->get_user_var('usr_first_last'); ?></div>
<p><a href="index.php?logout=true" class="button">Log Out</a></p>
<h2>Menu</h2>
<h3>Log File</h3>
<p><a href="index.php?clear=true">Clear Log file</a></p>
<?php echo ($logger->read_log_file()); ?>
<?php include('../layouts/admin_footer.php'); ?>