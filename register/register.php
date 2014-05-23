<?php

$root=realpath($_SERVER['DOCUMENT_ROOT']);
include("$root/global/connectDb.php");
include("$root/global/calcPoints.php");

include("$root/global/kmail.php");

if($_POST['username']=='' || $_POST['password']=='' || $_POST['email']==''||$_POST['repassword']=='')
	die("Sorry all the fields are mandatory. Please fill them and resubmit. Thank you");

if( $_POST['password'] != $_POST['repassword'])
	die('Your two passwords did not match. Please re-register');

//Sanitize the value received from login field

	//to prevent SQL Injection

	if(!get_magic_quotes_gpc()) {

		$login=mysql_real_escape_string($_POST['username']);

	}else {

		$login=$_POST['username'];

	}

	
	if(!get_magic_quotes_gpc()) {

		$pwd=mysql_real_escape_string($_POST['password']);

	}else {

		$pwd=$_POST['password'];

	}
	
	if(!get_magic_quotes_gpc()) {

		$email=mysql_real_escape_string($_POST['email']);

	}else {

		$email=$_POST['email'];

	}

$rand1=rand();
$rand2=rand();
$validate=md5("$rand1" . "$rand2");

$query = "insert into kurukshe_kpp.login values('$login','$pwd','$email','$validate')";
$result = mysql_query($query);
if(!$result)
 	die("Error registering.Plz retry ".mysql_error());
if(mysql_affected_rows()==0)
	die("Username already exists");

$validationUrl = "http://kplusplus.kurukshetra.org.in/register/validate.php?id=$validate";

/* Sending mail */
$to = $email;
$subject = "K++ - Validate your registration";
$message = "Thank you for registering at K++. Your registration will not get activated untill you validate your email address. To do so click on the copy and paste the url given below in your browser and validate your account
	     \n
	     \n
	     " . $validationUrl . "
	     \n----------------\n
	     Have a pleasant day
	     \n----------------\n";
sendMail($to,$subject,$message);

echo "<p><span class='blue'>Your registration completed successfully. Please validate yourself by following the link provided in a mail sent to you. Have a pleasant day</span></p> ";
?>