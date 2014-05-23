<?php 	$root=realpath($_SERVER['DOCUMENT_ROOT']);
	include("$root/global/connectDb.php");
	include("$root/global/error.php");
	require('loginCheck.php');	
	include("$root/global/calcPoints.php");
$filename=$_POST['filename'];
$marks = $_POST['marks'];
$comments = escapeString($_POST['comments']);

$eid = $_SESSION['SESS_ID'];

//$eid=1;
$etablename="`eid_".$eid."`";
$query="select bugid,kid,points,language from kurukshe_main.bugReport where bugid=$filename and evaluated=0 and evalInProgress IS NOT NULL;";
$result=mysql_query($query);
if(!$result)
	die("submitMarks.php: Error getting kid.  " . mysql_error());
$row=mysql_fetch_assoc($result);
if($row==NULL ||$row==false)
	die('The bug report has already been evaluated and marks have been submitted');
$kid = $row['kid'];
$points = $row['points'];
$bugid=$row['bugid'];
$language=$row['language'];

$total = calcMarks($points,$marks);

$query= "update kurukshe_evaluator.$etablename set marks=$total,comments='$comments' where filename='$filename' and marks is NULL ";
$result=mysql_query($query);
if(!$result)
	die("submitMarks.php: Error updating marks in kurukshe_evaluator table.  ".mysql_error());
if(mysql_affected_rows()==0)
	/* He didnt select this file */
	die("You didnt download this file or file name is wrong or file has already been evaluated. Please resubmit.");



$query="update kurukshe_main.bugReport set evaluated=true where bugid=$filename and evaluated=false and evalInProgress is not null;";
$result=mysql_query($query);
if(!$result)
{
	$query= "update kurukshe_evaluator.$etablename set marks=NULL,comments='' where filename='$filename' and marks is NOT NULL ";
	$result=mysql_query($query);
	if(!$result||mysql_affected_rows()==0)
		die("Error undo entry in evaluator table"); 
	die("submitMarksEval.php: Error setting evaluated to true.  ".mysql_error());
}
if(mysql_affected_rows()==0)
{
	$query= "update kurukshe_evaluator.$etablename set marks=NULL,comments='' where filename='$filename' and marks is NOT NULL ";
	$result=mysql_query($query);
	if(!$result||mysql_affected_rows()==0)
		die("Error undo entry in evaluator table". mysql_error()); 
	die("submitMarksEvalReport.php: The File had already been evaluated");
}
$ktablename="`kid_".$kid."`";
$sidbid = "b_".$bugid;
$query="update kurukshe_userStatsDatabase.$ktablename set marks=$total where sidbid='$sidbid' and marks is NULL ;";
$result=mysql_query($query);
if(!$result)
{
	$query= "update kurukshe_evaluator.$etablename set marks=NULL,comments='' where filename='$filename' and marks is NOT NULL ";
	$result=mysql_query($query);
	if(!$result||mysql_affected_rows()==0)
		die("Error undo entry in evaluator table". mysql_error()); 
		
	$query = "update kurukshe_main.bugReport set evaluated=0 where bugid=$filename and evaluated=1 and evalInProgress is not null;";	
	if(!mysql_query($query)||mysql_affected_rows()==0)
		die('unable to undo entry in kid table'. mysql_error());	
	die("submitMarksEvalReport.php: Error updating marks in kid table.  ".mysql_error());
}
if(mysql_affected_rows()==0)
{
	$query= "update kurukshe_evaluator.$etablename set marks=NULL,comments='' where filename='$filename' and marks is NOT NULL ";
	$result=mysql_query($query);
	if(!$result||mysql_affected_rows()==0)
		die("Error undo entry in evaluator table". mysql_error()); 
		
	$query = "update kurukshe_main.bugReport set evaluated=0 where bugid=$filename and evaluated=1 and evalInProgress is not null;";	
	if(!mysql_query($query)||mysql_affected_rows()==0)
		die('unable to undo entry in kid table'. mysql_error());
	die("submitMarksEvalReport.php: File has already been evaluated");
}	
$tablename= "kurukshe_main.scoresTable_".$language;

$query="select timeOfUpload from kurukshe_userStatsDatabase.$ktablename where sidbid='$sidbid';";
$result=mysql_query($query);
if(!$result)
	die('Error fetching timeOfUpload '.mysql_error());
$row=mysql_fetch_array($result);
$lastSubmissionTime=$row['timeOfUpload'];

$query="select totalScore from $tablename  where kid='$kid';";
$result=mysql_query($query);
if(!$result)
{
	
	die("submitMarksEval.php: Error getting totalscore.  " . mysql_error());
}
$row=mysql_fetch_assoc($result);
$prevTotal = $row['totalScore'];
echo $kid;
$sum=$total+$prevTotal;
$categoryName="BugReport";
$query="update $tablename set totalScore=$sum,lastSubmissionCategory='$categoryName',lastSubmissionMarks=$total,lastSubmissionTime='$lastSubmissionTime' where kid='$kid' ;";
$result=mysql_query($query);
if(!$result)
{
	$query= "update kurukshe_evaluator.$etablename set marks=NULL,comments='' where filename='$filename' and marks is NOT NULL ";
	$result=mysql_query($query);
	if(!$result||mysql_affected_rows()==0)
		die("Error undo entry in evaluator table ".mysql_error()); 
		
	$query = "update kurukshe_main.bugReport set evaluated=0 where bugid=$filename and evaluated=1 and evalInProgress is not null;";	
	if(!mysql_query($query)||mysql_affected_rows()==0)
		die('unable to undo entry in bugReport table'. mysql_error());
	
	$query="update kurukshe_userStatsDatabase.$ktablename set marks=NULL where sidbid='$sidbid' and marks is NOT NULL ;";
	if(!mysql_query($query)||mysql_affected_rows()==0)
		die('Error reseting values in userStatsDatabase kid table '.mysql_error());
		
	die("submitMarksEval.php: Error updating total in score table.  ".mysql_error());
}
if(mysql_affected_rows()==0)
{
	$query= "update kurukshe_evaluator.$etablename set marks=NULL,comments='' where filename='$filename' and marks is NOT NULL ";
	$result=mysql_query($query);
	if(!$result||mysql_affected_rows()==0)
		die("Error undo entry in evaluator table ".mysql_error()); 
		
	$query = "update kurukshe_main.bugReport set evaluated=0 where bugid=$filename and evaluated=1 and evalInProgress is not null;";	
	if(!mysql_query($query)||mysql_affected_rows()==0)
		die('unable to undo entry in bugReport table'. mysql_error());
	
	$query="update kurukshe_userStatsDatabase.$ktablename set marks=NULL where sidbid='$sidbid' and marks is NOT NULL ;";
	if(!mysql_query($query)||mysql_affected_rows()==0)
		die('Error reseting values in userStatsDatabase kid table '.mysql_error());
		

	die("Entry corresponding to your login does not exists in the scores table.Plz contact admin immediately.");
}


echo "done";
function calcMarks($points,$marks)
{
/* This processing is correct for bug report where total=1*points*/

return 1*$points*$marks;
}
function getCatName($category,$kid,$filename)
{

if($category >20)
{	
	$query = "select sourceKid from kurukshe_main.downloadedWhom where destnKid='$kid' and hasUsed=1 and destnFilename='$filename';";
	$result=mysql_query($query);
	if(!$result)
		die("submitMarksEval.php: Error finding sourceKid in getCatName routine ".mysql_error());
	$row=mysql_fetch_array($result);
	$sourceKid=$row['sourceKid'];
	$username=base64_url_decode($sourceKid);
	$string=" submitted using <b>$username</b>'s code";
	$category-=20;
}
else $string="";

switch($category)
{
case '0':
	return "BugReport".$string;
case '1':	
	return "BugPatch".$string;
case '2':
	return "Optimization".$string;
case '3':
	return "Feature".$string;
case '4':
	return "GUI".$string;
case '5':
	return "Themes".$string;
case '6':
	return "Language Packs".$string;


}
}



?>