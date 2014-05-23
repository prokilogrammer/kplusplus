<?php
	$root=realpath($_SERVER['DOCUMENT_ROOT']);
	include("$root/global/connectDb.php");
	//include("$root/global/error.php");
	include("$root/global/calcPoints.php");
	require("$root/global/loginCheck.php");
	include("$root/global/kmail.php");
?>
