<?php	 $root=realpath($_SERVER['DOCUMENT_ROOT']);
	include("$root/global/headers.php");
	
$sno=$_POST['sno'];

$query="update kurukshe_main.downloadedWhom set hasUploadedRecentlyFilename=null where sno=$sno";
$result=mysql_query($query);
if(!$result)
	die("monitorOnceMore.php: Error setting preferences to monitor again ".mysql_error());
if(mysql_affected_rows()==0)
 	die("There was error setting the preferences. Please retry or contact administrator");
echo "The preferences have been set to monitor the user again";
?>
