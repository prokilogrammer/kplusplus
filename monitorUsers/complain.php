<?php
$root=realpath($_SERVER['DOCUMENT_ROOT']);
include("$root/global/headers.php");

/* Send Mail to admin */
$time=time();
$ip=$_SERVER['REMOTE_ADDR'];
$browser=$_SERVER['HTTP_USER_AGENT'];
$sno=$_POST['sno'];
$username=$_POST['username'];
$myUsername=base64_url_decode($_SESSION['SESS_MEMBER_ID']);
$to="complaint@kplusplus.kurukshetra.org.in";
$subject="You've got a complaint from -- $myUsername --";
$message="User -- $myUsername -- has complained that user -- $username -- has used his code and not given him.
           \n Serial number of the entry in downloadedWhom table is 'Sno=$sno' 
           \n---------------------\n
           Complaint filed at $time from IP $ip through browser $browser";
sendMail($to,$subject,$message);

echo "The particular submission has been brought to notice of administrator. He'll get back to you soon";
?>
