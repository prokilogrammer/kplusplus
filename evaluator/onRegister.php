<?php 	$root=realpath($_SERVER['DOCUMENT_ROOT']);
	include("$root/global/connectDb.php");
	include("$root/global/error.php");
	include("loginCheck.php");
	//This loginChech.php must be the local loginCheck.php not the one in global folder";
/* The sql queries dont work properly with only NUMBERS as its table name. So only I'm prefixing them with "kid_" */
$eid = "eid_" . $_SESSION['SESS_ID'];
//$eid="eid_" . "1";
echo $eid;
$query = "create table kurukshe_evaluator."."`$eid`(sidbid varchar(10) NOT NULL,
		 timeOfDownload datetime,
		 filename varchar(255),
		 marks float default null,
		 comments varchar(255)		
		 )ENGINE=innoDB;";
	
$result = mysql_query($query);
if( !$result) {echo mysql_error();echo "<br>On Register Evaluator. Report Error";echo "<br>";echo $query;return;}
echo "Success";
