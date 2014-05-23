<?php	
$root=realpath($_SERVER['DOCUMENT_ROOT']);
include("$root/global/headers.php");

	
$username = base64_url_decode($_SESSION['SESS_MEMBER_ID']);

/* This should be ideally the name and not the username */
$query = "select username from kurukshe_kpp.members where username='$username';";
$result = mysql_query($query);
		if(!$result)
			{
			echo mysql_error();
			echo "  member-index.php: Unable to select username";
			return;
			}
$row = mysql_fetch_array($result);
if( $row==NULL || $row=='')
	die("Unable to locate the username in our database. Plz retry");
$name = $row['username'];

$kid = $_SESSION['SESS_MEMBER_ID'];

$query="select count from kurukshe_userStatsDatabase.lastLogin where kid='$kid'";
$result = mysql_query($query);
		if(!$result)
			{
			echo mysql_error();
			echo "  member-index.php: Unable to select count";
			return;
			}
$row = mysql_fetch_array($result);
if($row==NULL)
	$display="Welcome $name.| <a onClick='logout();'>Logout</a>";
else
{
$count =  $row['count'];
/* I NEED TO DISPLAY THE RANK OF THAT PERSON. I'M YET TO WRITE THAT CODE */
$display = "Welcome $name. You've logged in $count times | <a href='#' id='newregister' onClick='logout();'>Logout";
}
echo wrapText($display);


function wrapText($display)
{
$tags = '<div id="top">
<ul class="login">
<li class="left">&nbsp;</li>';
$html = $tags . "<li> $display </li>" . "</div></ul>";
return $html;
}
?>
