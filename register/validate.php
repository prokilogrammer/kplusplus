<?php

$root=realpath($_SERVER['DOCUMENT_ROOT']);
include("$root/global/connectDb.php");
include("$root/global/calcPoints.php");
include("$root/global/error.php");

$validate = $_GET['id'];
if($validate==null || $validate=='')
	die("Invalid validation code");


$query = "select * from kurukshe_kpp.login where validate='$validate'";
$result = mysql_query($query);
if(!$result)
	die("Error validating your request. Please resubmit. " .mysql_error());
if(($row=mysql_fetch_array($result)))
{

//validation correct 

$query = "update kurukshe_kpp.login set validate=null where validate='$validate';";
$result = mysql_query($query);
if(!$result)
	die("Error validating your request. Please resubmit. " .mysql_error());
//success
echo "<p><span class='blue'>You've successfully validated yourself. Please login to proceed playing the game</span></p>";
return;
}
else
	die("Invalid validation code.");

?>
