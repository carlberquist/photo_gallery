<?php
require("../../includes/Initialise.php");
try {
    $credentials = new PhotoGalleryCredentials();
    $connection = new MySQLDatabase($credentials);
    $encryption = new PasswordHash();
    $user = new User($connection, $encryption);
    $file_upload = new Files($connection, $user);
    $user->set_user_by_id(1);
    if (isset($_FILES['picture'])) {
        $file_uploaded = $file_upload->upload_file($_FILES['picture']);
    }
    if (isset($_GET['delete_photo'])){
        $delete_array = explode(",", $_GET['delete']);
        $file_delete_msg = $file_upload->delete_file($delete_array[0], $delete_array[1], $delete_array[2]);
    }
} catch (Exception $e) {
    $error_msg = $e->getMessage();
}
$file_info_array = $file_upload->get_file_by_user_id();
?>
<?php include('../layouts/header.php'); ?>
<h2><?php if (isset($error_msg)) echo ($error_msg); ?></h2>
<div>Welcome: <?php echo $user->get_user_var('usr_first_last'); ?></div>
<div><?php if (isset($file_uploaded)) echo ("Files " . implode(", ", $file_uploaded) . " uploaded sucessfully."); ?></div>
<div><?php if (isset($file_delete_msg)) echo ($file_delete_msg); ?></div>
<h2>Photograps</h2>
<table class = "bordered">
<tr>
<th>Image</th>
<th>Filename</th>
<th>Caption</th>
<th>Size</th>
<th>Type</th>
<th>Delete</th>
</tr>
<?php
$photo_html="";
$photo_html .= "<tr>";
foreach ($file_info_array as $photo) {
    $photo_html .="<div>";
    $photo_html .= '<td><img src="../' . $photo->file_path . '" width = "100" /></td>';
    $photo_html .= '<td>' . $photo->file_path . '</td>';
    $photo_html .= '<td>' . $photo->caption . '</td>';
    $photo_html .= '<td>' . $photo->size . '</td>';
    $photo_html .= '<td>' . $photo->type . '</td>';
    $photo_html .= '<td><a href="{$_SERVER[\'REQUEST_URI\'}?delete_photo={$photo->id},{$photo->type},{$photo->file_path}"></td>';
    $photo_html .="</div>";
}
$photo_html .= "</tr>";
echo($photo_html);
?>
</table>
<!-- The data encoding type, enctype, MUST be specified as below -->
<form enctype="multipart/form-data" action="" method="POST">
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
    <!-- Name of input element determines name in $_FILES array -->
    Attach a file: <input name="picture" type="file" />
    <input type="submit" value="Upload File" />
</form>
<?php include('../layouts/footer.php'); ?>