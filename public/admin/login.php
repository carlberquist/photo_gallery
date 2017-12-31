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

if ($session->get_logged_in()) {
    header("Location: index.php");
    exit;
}
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($user->set_user_authenticate($connection, $username, $password)) {
        $session->login($user);
        header("Location: index.php");
        exit;
    } else {
        $message = "Username: {$username} or password: {$password} is incorrect";
    }
} else {
    $username = "";
    $password = "";
}
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
    <div id = "main">
    <h2>Staff Login</h2>
    <?php if (isset($message)) echo $message; ?>
    <!--change to redirect to self-->
    <form action = "login.php" method = "post">
<table>
<tr>
<td>Username:</td>
<td>
<input type = "text" name = "username" maxlength = "30" value = "<?php echo htmlentities($username); ?>" />

</td>
</tr>
<tr>
<td>Password:</td>
<td>
<input type = "password" name = "password" maxlength = "30" value = "<?php echo htmlentities($password); ?>"/>
</td>
</tr>
<tr>
<td coslpan = "2">
<input type = "submit" name = "submit" value = "Login" />
</td>
</tr>
</table>
    </form>
    </div>
    <div id = "footer">Copyright <?php echo date("Y", time()) ?>, Carl Berquist</div>
</body>
</html>