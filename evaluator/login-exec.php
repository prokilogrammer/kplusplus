<?php
	$root=realpath($_SERVER['DOCUMENT_ROOT']);
	include("$root/global/connectDb.php");
	include("$root/global/error.php");
	include("$root/global/calcPoints.php");
	//Start session
	session_start();
	
	//Connect to mysql server
	
	//Select database
	$db=mysql_select_db("kurukshe_evaluator");
	if(!$db) {
		die("Unable to select database");
	}

	//Sanitize the value received from login field
	//to prevent SQL Injection
	if(!get_magic_quotes_gpc()) {
		$login=mysql_real_escape_string($_POST['log']);
	}else {
		$login=$_POST['log'];
	}
	
	if(!get_magic_quotes_gpc()) {
		$pwd=mysql_real_escape_string($_POST['pwd']);
	}else {
		$pwd=$_POST['pwd'];
	}
	
	//Create query
	$qry="SELECT * FROM kurukshe_evaluator.login WHERE username='$login' and password='$pwd'";
	$result=mysql_query($qry);
	//Check whether the query was successful or not
	if($result) {
		if(mysql_num_rows($result)>0) {
			//Login Successful
			session_regenerate_id();
			$member=mysql_fetch_assoc($result);
			$_SESSION['SESS_ID']=base64_url_encode($member['username']);
			//echo $_SESSION['SESS_ID'];
			session_write_close();
			echo 'You\'ve successfully logged in. Do your work and click the following link to logout <br><a href="logout.php">LOGOUT</a>';
			
		}else {
			//Login failed
			die("Login Failed. Plz retry");
		}
	}else {
		die("Query failed ".mysql_error());
	}


?>
