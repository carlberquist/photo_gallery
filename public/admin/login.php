<?php
require("../../includes/Initialise.php");

$credentials = new Photo_gallery_credentials();
$connection = new MySQLDatabase($credentials);
$session = new Session();
$user = new User();
$encryption = new PasswordHash();

if ($session->get_logged_in()) {
    header("Location: index.php");
    exit;
}
//if (isset($_POST['submit'])) {}
if (empty($_POST['username']) || empty($_POST['password'])) {
        //add message to session "fill in username or password";
    header("Location: login.php");
    exit;
}
$user->set_user_by_username($connection, $encryption, $_POST['username'], $_POST['password']);
if ($session->login($user)){
header("Location: index.php");
exit;
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
    <form action = "login.php" method = "post">
        <table>
        <tr>
        <td>Username:</td>
        <td>
        <input type = "text" name = "username" maxlength = "30" value = "" />
        </td>
        </tr>
        <tr>
        <td>Password:</td>
        <td>
        <input type = "password" name = "password" maxlength = "30" value = ""/>
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