<?php
require("../../includes/Initialise.php");

try {
    $credentials = new PhotoGalleryCredentials();
    $connection = new MySQLDatabase($credentials);
    $encryption = new PasswordHash();
    $user = new User();
    $session = new Session();

    if ($session->get_logged_in()) {
        header("Location: index.php");
        exit;
    }
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        if (!$user->authenticate_user($encryption, $session, $_POST['password'], $connection, $_POST['username'])) {
            header("Location: index.php");
            exit;
        }
    } else {
        throw new Exception("Please enter a username or password", 1);
    }
} catch (Exception $e) {
    $error_msg = $e->getMessage();
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
    <?php if (isset($error_msg)) echo $error_msg; ?>
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