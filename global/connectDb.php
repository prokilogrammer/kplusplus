<?php
$uname="kurukshe_root";
$passwd="root123";
if( !mysql_connect("localhost",$uname,$passwd)) 
	die("Failed to connect to database: " . mysql_error());

$db="kurukshe_main";
if( !mysql_select_db($db))
	die("Failed to select database: $db. " . mysql_error());

$db="kurukshe_userStatsDatabase";
if( !mysql_select_db($db))
	die("Failed to select database: $db. " . mysql_error());

$db="kurukshe_evaluator";
if( !mysql_select_db($db))
	die("Failed to select database: $db. " . mysql_error());


$db=mysql_select_db("kurukshe_kpp");
	if(!$db) {
		die("Unable to select database");
	}
	
function escapeString($string)
{
if(!get_magic_quotes_gpc()) {
		return mysql_real_escape_string($string);
	}else {
		return $string;
	}
}

?>
