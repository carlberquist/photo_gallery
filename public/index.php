<?php
require("../includes/Initialise.php");
try {
    $credentials = new PhotoGalleryCredentials();
    $connection = new MySQLDatabase($credentials);
    $pagination = new Pagination($connection);
    $file_upload = new Files($connection, null, $pagination);
    $file_upload->get_all_files();
} catch (Exception $e) {
    $error_msg = $e->getMessage();
}
?>
<?php include('layouts/header.php'); ?>
<h2><?php if (isset($error_msg)) echo ($error_msg); ?></h2>
<h2>Menu</h2>
<h2>Photographs</h2>
<table class = "bordered">
<?php
$photo_html = "";
foreach ($file_upload->files as $photo) {
    $photo_html .= '<div style="float: left; margin-left: 20px;">';
    $photo_html .= '<img src="../' . $photo->file_path . '" width = "200" />';
    $photo_html .= '<p>' . $photo->caption . '<p>';
    $photo_html .= '</div>';
}
echo ($photo_html);
$page = "";
if ($pagination->total_pages > 1) {
    $previous_page = $pagination->get_previous_page();
    if ($previous_page) {
        $page .= "<a href=\"" . basename($_SERVER['PHP_SELF']) . "page=" . $previous_page . "\">&laquo; Previous</a>";
    }
    for ($i = 1; $i <= $pagination->total_pages; $i++) {
        $page .= "<a href=\"" . basename($_SERVER['PHP_SELF']) . "?page={$i}\">{$i}</a>";
    }
    $next_page = $pagination->get_previous_page();
    if ($next_page) {
        $page .= "<a href=\"" . basename($_SERVER['PHP_SELF']) . "page=" . $$next_page . "\"> Next &raquo;</a>";
    }
}
echo($page);
?>
</table>
<div id = "test"></div>
<script>
$.get("test.php", function(data){
    var mydata = $.parseJSON(data);
    var art1 = mydata.key1;
    $("#test").text(art1);
});
</script>
<?php include('layouts/footer.php'); ?>