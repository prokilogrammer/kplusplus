<?php
	
	//Start session
	session_start();
	//Check whether the session variable
	//SESS_MEMBER_ID is present or not
		$root=realpath($_SERVER['DOCUMENT_ROOT']);
	if(!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_MEMBER_ID'])=='')) {
		header("location: /global/youMustLogin.php");
		exit();
	}

?>
