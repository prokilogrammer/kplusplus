<?php 	$root=realpath($_SERVER['DOCUMENT_ROOT']);
	include("$root/global/connectDb.php");
	include("$root/global/error.php");
	require('loginCheck.php');	
	include("$root/global/calcPoints.php");
//die('adfa');

$filename=$_POST['filename'];
$marks = $_POST['marks'];
$comments = escapeString($_POST['comments']);

$eid = $_SESSION['SESS_ID'];

//$eid=1;
$etablename="`eid_".$eid."`";
$query="select submissionId,kid,points,category,language from kurukshe_main.userUploadFileTable where filename='$filename' and evaluated=0 and evalInProgress IS NOT NULL;";
$result=mysql_query($query);
if(!$result)
	die("submitMarks.php: Error getting from UserUploadFileTable.  " . mysql_error());
$row=mysql_fetch_assoc($result);
if($row==NULL ||$row==false)
	die('Marks have already been submitted.');
$kid = $row['kid'];
$points = $row['points'];
$category = $row['category'];
$sid=$row['submissionId'];
$language=$row['language'];

$total = calcMarks($marks,$points,$category);

$query= "update kurukshe_evaluator.$etablename set marks=$total,comments='$comments' where filename='$filename' and marks is NULL ";
$result=mysql_query($query);
if(!$result)
	die("submitMarks.php: Error updating marks in kurukshe_evaluator table.  ".mysql_error());
if(mysql_affected_rows()==0)
	// He didnt select this file 
	die("You didnt download this file or file name is wrong or file has already been evaluated. Please resubmit.");



$query="update kurukshe_main.userUploadFileTable set evaluated=1 where filename='$filename'and evaluated=0 and evalInProgress is not null;";
$result=mysql_query($query);
if(!$result)
{
	
	$query= "update kurukshe_evaluator.$etablename set marks=NULL,comments='' where filename='$filename' and marks is NOT NULL ";
	$result=mysql_query($query);
	if(!$result||(mysql_affected_rows()==0))
		die("Error undo entry in evaluator table".mysql_error()); 
	die("submitMarksEval.php: Error setting evaluated to true.  ".mysql_error());
}
if(mysql_affected_rows()==0)
{
	$query= "update kurukshe_evaluator.$etablename set marks=NULL,comments='' where filename='$filename' and marks is NOT NULL ";
	$result=mysql_query($query);
	if(!$result||mysql_affected_rows()==0)
		die("Error undo entry in evaluator table".mysql_error()); 
	die("userUploadFileTable: File had already been evaluated");
}

$ktablename="`kid_".$kid."`";
$sidbid = "s_".$sid;
$query="update kurukshe_userStatsDatabase.$ktablename set marks=$total where sidbid='$sidbid' and marks is NULL ;";
$result=mysql_query($query);
if(!$result)
{
	$query= "update kurukshe_evaluator.$etablename set marks=NULL,comments='' where filename='$filename' and marks is NOT NULL ";
	$result=mysql_query($query);
	if(!$result||mysql_affected_rows()==0)
		die("Error undo entry in evaluator table".mysql_error()); 
		
	$query = "update kurukshe_main.userUploadFileTable set evaluated=false where filename='$filename';";	
	if(!mysql_query($query)||mysql_affected_rows()==0)
		die('unable to undo entry in kid table'.mysql_error());	
	die("submitMarksEval.php: Error updating marks in kid table.  ".mysql_error());
}
if(mysql_affected_rows()==0)
{	
	$query= "update kurukshe_evaluator.$etablename set marks=NULL,comments='' where filename='$filename' and marks is NOT NULL ";
	$result=mysql_query($query);
	if(!$result||mysql_affected_rows()==0)
		die("Error undo entry in evaluator table".mysql_error()); 
			
	$query = "update kurukshe_main.userUploadFileTable set evaluated=false where filename='$filename';";	
	if(!mysql_query($query)||mysql_affected_rows()==0)
		die('unable to undo entry in kid table'.mysql_error());
	die("userStatsDb: File has already been evaluated");
}

$query="select timeOfUpload from kurukshe_userStatsDatabase.$ktablename where sidbid='$sidbid';";
$result=mysql_query($query);
if(!$result)
	die('Error fetching timeOfUpload '.mysql_error());
$row=mysql_fetch_array($result);
$lastSubmissionTime=$row['timeOfUpload'];
	
$tablename= "kurukshe_main.scoresTable_".$language;

$query="select totalScore from $tablename  where kid='$kid';";
$result=mysql_query($query);
if(!$result)
	die("submitMarksEval.php: Error getting totalscore.  " . mysql_error());
$row=mysql_fetch_assoc($result);
$prevTotal = $row['totalScore'];

$sum=$total+$prevTotal;
$categoryName=getCatName($category,$kid,$filename);
echo $lastSubmissionTime;
$query="update $tablename set totalScore=$sum,lastSubmissionCategory='$categoryName',lastSubmissionMarks=$total,lastSubmissionTime='$lastSubmissionTime' where kid='$kid' ;";
$result=mysql_query($query);

if(!$result)
{
	$error=mysql_error();
	$query= "update kurukshe_evaluator.$etablename set marks=NULL,comments='' where filename='$filename' and marks is NOT NULL ";
	$result=mysql_query($query);
	if(!$result||mysql_affected_rows()==0)
		die("Error undo entry in evaluator table ".mysql_error()); 
		
	$query = "update kurukshe_main.userUploadFileTable set evaluated=false where filename='$filename';";	
	if(!mysql_query($query)||mysql_affected_rows()==0)
		die('unable to undo entry in kid table '.mysql_error());
	
	$query="update kurukshe_userStatsDatabase.$ktablename set marks=NULL where sidbid='$sidbid' and marks is NOT NULL ;";
	if(!mysql_query($query)||mysql_affected_rows()==0)
		die('Error reseting values in userStatsDatabase kid table '.mysql_error());
	die("submitMarksEval.php: Error updating total in score table.  ".$error);		
}

if(mysql_affected_rows()==0)
{
	$query= "update kurukshe_evaluator.$etablename set marks=NULL,comments='' where filename='$filename' and marks is NOT NULL ";
	$result=mysql_query($query);
	if(!$result||mysql_affected_rows()==0)
		die("Error undo entry in evaluator table ".mysql_error()); 
		
	$query = "update kurukshe_main.userUploadFileTable set evaluated=false where filename='$filename';";	
	if(!mysql_query($query)||mysql_affected_rows()==0)
		die('unable to undo entry in kid table '.mysql_error());
	
	$query="update kurukshe_userStatsDatabase.$ktablename set marks=NULL where sidbid='$sidbid' and marks is NOT NULL ;";
	if(!mysql_query($query)||mysql_affected_rows()==0)
		die('Error reseting values in userStatsDatabase kid table '.mysql_error());
		
	
	die("Entry corresponding to your login does not exists in the scores table.Plz contact admin immediately.");
}



if($category >20)
{	
$query = "select sno,sourceKid,sourceFilename from kurukshe_main.downloadedWhom where destnKid='$kid' and hasUsed=1 and destnFilename='$filename' and hasUsed=1;";
	$result=mysql_query($query);
	if(!$result)
		die("submitMarksEval.php: Error finding sourceKid ".mysql_error());
	$row=mysql_fetch_array($result);
	if($row==NULL || $row==false)
		die("The user had not uploaded the corresponding code. Plz contact admin. Problem is there.".mysql_error());
	$sourcekid=$row['sourceKid'];
// NOW STARTING TO UPDATE THE TABLE OF THE PERSON WHO'S CREDIT THIS FELLOW HAS USED 

$tablename="`kid_".$sourcekid."`";
$sourceFilename=$row['sourceFilename'];
$sno=$row['sno'];
$did="d_".$sno; //DownloadWhom ID
$timeOfUp='now()';
$marks=$total*0.2;
$who = base64_url_decode($kid);
$activity="User $who had used your code and submitted";
$query="insert into kurukshe_userStatsDatabase.$tablename values('$did','$activity',$category,$marks,$timeOfUp,0,'$language');";
$result=mysql_query($query);

if(!$result)
{
	$error=mysql_error();
	$query= "update kurukshe_evaluator.$etablename set marks=NULL,comments='' where filename='$filename' and marks is NOT NULL ";
	$result=mysql_query($query);
	if(!$result||mysql_affected_rows()==0)
		die("Error undo entry in evaluator table ".mysql_error()); 
		
	$query = "update kurukshe_main.userUploadFileTable set evaluated=false where filename='$filename';";	
	if(!mysql_query($query)||mysql_affected_rows()==0)
		die('unable to undo entry in kid table '.mysql_error());
	
	$query="update kurukshe_userStatsDatabase.$ktablename set marks=NULL where sidbid='$sidbid' and marks is NOT NULL ;";
	if(!mysql_query($query)||mysql_affected_rows()==0)
		die('Error reseting values in userStatsDatabase kid table '.mysql_error());
	
	$tablename= "kurukshe_main.scoresTable_".$language;
	$query="update $tablename set totalScore=$prevTotal,lastSubmissionCategory='N/A',lastSubmissionMarks=0,lastSubmissionTime=NULL where kid='$kid' ;";
	$result=mysql_query($query);
	
	die("submitMarksEval.php: Error updating marks for sourceKid in kid table.  ".$error);
}

$tablename= "kurukshe_main.scoresTable_".$language;
$query="select totalScore from $tablename  where kid='$sourcekid';";
$result=mysql_query($query);
if(!$result)
	die("submitMarksEval.php: Error getting totalscore sourceKid.  " . mysql_error());
$row=mysql_fetch_assoc($result);
$sprevTotal = $row['totalScore'];

$sum=$marks+$sprevTotal;
$categoryName="User $who had used your code and submitted";
$query="update $tablename set totalScore=$sum,lastSubmissionCategory='$categoryName',lastSubmissionMarks=$marks where kid='$sourcekid' ;";
$result=mysql_query($query);
if(!$result)
{
	$query= "update kurukshe_evaluator.$etablename set marks=NULL,comments='' where filename='$filename' and marks is NOT NULL ";
	$result=mysql_query($query);
	if(!$result||mysql_affected_rows()==0)
		die("Error undo entry in evaluator table ".mysql_error()); 
		
	$query = "update kurukshe_main.userUploadFileTable set evaluated=false where filename='$filename';";	
	if(!mysql_query($query)||mysql_affected_rows()==0)
		die('unable to undo entry in kid table '.mysql_error());
	
	$query="update kurukshe_userStatsDatabase.$ktablename set marks=NULL where sidbid='$sidbid' and marks is NOT NULL ;";
	if(!mysql_query($query)||mysql_affected_rows()==0)
		die('Error reseting values in userStatsDatabase kid table '.mysql_error());
	
	$tablename= "kurukshe_main.scoresTable_".$language;
	$query="update $tablename set totalScore=$prevTotal,lastSubmissionCategory='N/A',lastSubmissionMarks=0,lastSubmissionTime=NULL where kid='$kid' ;";
	$result=mysql_query($query);
	
	die("submitMarksEval.php: Error updating total in score table sourceKid.  ".mysql_error());		
}
if(mysql_affected_rows()==0)
{
	$query= "update kurukshe_evaluator.$etablename set marks=NULL,comments='' where filename='$filename' and marks is NOT NULL ";
	$result=mysql_query($query);
	if(!$result||mysql_affected_rows()==0)
		die("Error undo entry in evaluator table ".mysql_error()); 
		
	$query = "update kurukshe_main.userUploadFileTable set evaluated=false where filename='$filename';";	
	if(!mysql_query($query)||mysql_affected_rows()==0)
		die('unable to undo entry in kid table '.mysql_error());
	
	$query="update kurukshe_userStatsDatabase.$ktablename set marks=NULL where sidbid='$sidbid' and marks is NOT NULL ;";
	if(!mysql_query($query)||mysql_affected_rows()==0)
		die('Error reseting values in userStatsDatabase kid table '.mysql_error());
	
	$tablename= "kurukshe_main.scoresTable_".$language;
	$query="update $tablename set totalScore=$prevTotal,lastSubmissionCategory='N/A',lastSubmissionMarks=0,lastSubmissionTime=NULL where kid='$kid' ;";
	$result=mysql_query($query);
	
	die("Entry corresponding to your login does not exists in the scores table.Plz contact admin immediately.SourceKid");
}




}

echo "done";

function calcMarks($marks,$points,$category)
{

if($category>20)
	$category-=20;

switch($category)
{
case '1':
	$credits=1;
	break;
case '2':
	$credits=1;
	break;
case '3':
	$credits=50;
	break;
case '4':
	$credits=50;
	break;
case '5':
	$credits=25;
	break;
case '6':
	$credits=25;
	break;
}
return $credits*$marks*$points;
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
	$string=" submitted using <b>$username</b>\'s code";
	//Without the escape character in the above line sql query will fail
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