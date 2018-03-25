<?php
require("../../includes/Initialise.php");
try {
    $credentials = new PhotoGalleryCredentials();
    $connection = new MySQLDatabase($credentials);
    $file_upload = new Files($connection);
    $comments = new Comments($connection ,$file_upload);
    $file_upload->set_file_by_id('5aa3a1340ecb2');
    $comments->find_comments_photo_id();
} catch (Exception $e) {
    $error_msg = $e->getMessage();
}
?>