<?php
$root=realpath($_SERVER['DOCUMENT_ROOT']);
include("$root/global/calcPoints.php");
$t=time();
$time=date("H:i:s",$t);
echo $time . ":" . calcPoints();

?>

