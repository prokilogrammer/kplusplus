<?php 
	$root=realpath($_SERVER['DOCUMENT_ROOT']);
	include("$root/global/headers.php");

if( ($validity = valid_bug($_POST['language'],$_POST['description'],$_POST['summary']))!="" )
	die("Following fields did not have proper values: ".$validity."<br />Please Resubmit Properly");
	
if($_POST['language']==1)
	$language='java';
else
	$language='c';

$postedData = "Language: ".$language."\nSummary: " .  $_POST['summary'] . "\nPackages: " . $_POST['packages'] . "\nDescription: " . $_POST['description'];
/*The text will be of format 
 *Language: ____
 *Summary: _____
 *Packages: ____
 *Description: _____
 */

/* Getting the kid of the person who has logged in. Getting it from the session thats set */
$kid = $_SESSION['SESS_MEMBER_ID'];
//$kid = 1;
/*Now creating a new entry for the bug submission */
$currentPoints = calcPoints();

$time = time();
//$language=escapeString($_POST['language']);
$query = "insert into kurukshe_main.bugReport(kid,points,timeOfSubmission,language) values('$kid',$currentPoints,'$time','$language'); ";
$result = mysql_query($query);
if( !$result) {echo mysql_error();echo "insert bugReport Error";return;}

/*Getting the bugid */
$query = "select bugid from kurukshe_main.bugReport where timeOfSubmission='$time'; ";
$result = mysql_query($query);
if( !$result) {
echo mysql_error();echo "select bugReport Error";
$query="delete from kurukshe_main.bugReport where timeOfSubmission='$time';";
$result=mysql_query($query);
if(!$result || mysql_affected_rows()==0)
	die('Unable to delete entry from bug report table: '.mysql_error());
return;}

$row = mysql_fetch_array($result);
$bugid = $row['bugid'];
$filePath = "bugReports/".$bugid;
$file = fopen($filePath,"x");
if($file == false) 
   {
   	$query="delete from kurukshe_main.bugReport where timeOfSubmission='$time';";
	$result=mysql_query($query);
	if(!$result || mysql_affected_rows()==0)
		die('Unable to delete entry from bug report table: '.mysql_error());

	die("Error in bugReport:File Cant Be Created. Please  retry again" );
    }
if( fwrite($file,$postedData) == false )
   {
  	$query="delete from kurukshe_main.bugReport where timeOfSubmission='$time';";
	$result=mysql_query($query);
	if(!$result || mysql_affected_rows()==0)
		die('Unable to delete entry from bug report table: '.mysql_error());
  	die("Error in bugReport:Unable to Write to File.Please retry again." );
   }

fclose($file);

/* Adding an entry in the user's kid table informing the reporting of the bug */
$tablename = "kurukshe_userStatsDatabase.`kid_".$kid."`";
$sidbid = "b_" . $bugid;
$activity = "Bug Report";
$category = 0;
$timeofupload = "now()"; /* Corresponds to mySql's now() function */

$query = "insert into $tablename(sidbid,activity,category,timeOfUpload,points,language) values ('$sidbid','$activity',$category,$timeofupload,$currentPoints,'$language');";
$result = mysql_query($query);
if( !$result) {
	echo mysql_error();echo "Error in bugReport.php. Error inserting into $tablename table.";
	
	$query="delete from kurukshe_main.bugReport where timeOfSubmission='$time';";
	$result=mysql_query($query);
	if(!$result || mysql_affected_rows()==0)
		die('Unable to delete entry from bug report table: '.mysql_error());
  	unlink($filePath);
	return;
	}

chmod($filePath,700);

echo "Your bug report successfully submitted. Your bug ID is ".$bugid .". Please note this bugID as you will need it while submitting your bug patch";



function valid_bug($lang,$desc,$summ)
{
$tmp="";
if($lang!='0' && $lang!='1')
	$tmp=$tmp . "<br />**Wrong Language Selected!!** ";
if($desc==NULL)
	$tmp=$tmp . "<br />**The Description field should not be left Blank!!** ";
if($summ==NULL)
	$tmp=$tmp . "<br />**The Summary field should not be left Blank!!** ";	
return $tmp;
}

?>