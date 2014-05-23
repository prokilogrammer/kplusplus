<?php 	
$root=realpath($_SERVER['DOCUMENT_ROOT']);
include("$root/global/headers.php");


/* HERE I WRITE THE ROUTINE TO INITIATE DOWNLOAD THRO PHP ----NO NEED*/
$kid=$_SESSION['SESS_MEMBER_ID'];
//$kid=1;
$sourceKid=$_POST['kid'];
$destnKid=$kid;
$hasUsed=0;
$sourceFilename=$_POST['filename'];
$query = "insert into kurukshe_main.downloadedWhom(sourceKid,destnKid,sourceFilename,hasUsed) values('$sourceKid','$destnKid','$sourceFilename',$hasUsed);";
$result=mysql_query($query);
if(!$result)
	die("downloadCode.php: Error inserting into downloadedWhom table ".mysql_error());
if(mysql_affected_rows()==0)
	die("There was error processing your download request. Please reclick on the link");
echo "<a href='#' onClick='downloadFile(\"$sourceFilename\");'>Click</a>";

?>
