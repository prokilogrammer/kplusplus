<?php
$root=realpath($_SERVER['DOCUMENT_ROOT']);
include("$root/global/headers.php");

echo "<iframe id='iframe' name='iframe' frameborder=\"0\" style='position: relative; margin-left: 5px; margin-right: 5px; height: 600px; width: 500px;' scrolling=\"auto\" src='ubr_file_upload.php'> </iframe>";
?>
