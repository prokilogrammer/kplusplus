<?php
	//Start session
	session_start();
	//Unset the variable SESS_MEMBER_ID stored in session
	unset($_SESSION['SESS_MEMBER_ID']);
/*	if(isset($_SESSION['whoAmI'])
		unset($_SESSION['whoAmI']);*/
	header('location: load-login.php');
?>

