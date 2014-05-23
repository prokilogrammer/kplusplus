<?php	$root=realpath($_SERVER['DOCUMENT_ROOT']);
	include("$root/global/connectDb.php");
	include("$root/global/error.php");
	require('loginCheck.php');
$sidbid= "b_".$_POST['submissionId'];
$eid = $_SESSION['SESS_ID'];
//$eid = 1;
$tablename = "kurukshe_evaluator.eid_".$eid;
$filename = $_POST['filename'];
$timeOfDownload="now()";


$sid=$_POST['submissionId'];
$query = "update kurukshe_main.bugReport set evalInProgress='$eid' where bugid=$sid and evaluated=false and evalInProgress is null";
$result = mysql_query($query);
	if( !$result) 
	{echo mysql_error();echo "evalBugReport.php: Error updating into userUploadFileTable.";
	return;}
if(mysql_affected_rows()==0)
	die("Some one else has already taken this file.Plz select another file");


$query = "insert into $tablename(sidbid,timeOfDownload,filename) values('$sidbid',$timeOfDownload,'$filename');";
$result = mysql_query($query);
	if( !$result) 
	{
	echo mysql_error();echo "evalBugReport.php: Error inserting into eid table.";
	
	$query = "update kurukshe_main.bugReport set evalInProgress=NULL where bugid=$sid;";	
	if(!mysql_query($query)||mysql_affected_rows()==0)
		die('unable to undo entry in kid table');
	return;
	}
if(mysql_affected_rows()==0)
	die("Some one else has already taken this file.Plz select another file");
$file=/*realpath($_SERVER['DOCUMENT_ROOT']).*/'/bugReport/bugReports/'.$filename;
echo "Here's Your File. Click Here: <a href='$file'>$filename</a>";
?>
