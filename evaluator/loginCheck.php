<?php
	
	//Start session
	session_start();
	//Check whether the session variable
	//SESS_MEMBER_ID is present or not
	if(!isset($_SESSION['SESS_ID']) || (trim($_SESSION['SESS_ID'])=='')) {
		die('You must login before you can proceed');
		exit();
	}
	
?>
