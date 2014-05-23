<?php 	$root=realpath($_SERVER['DOCUMENT_ROOT']);
	include("$root/global/connectDb.php");
	include("$root/global/error.php");
	require('validate.php');
	include('loginCheck.php');
	
function wrapAnchorTag($location,$text,$submissionId)
{

return "<A href='#' onClick='doggieBugReport(\"$location\",\"$submissionId\");'>$text</A>";

}

function getTable()
{
/* This code will retrieve 5 uploaded files randomly and display in table to kurukshe_evaluator */
$eid = $_SESSION['SESS_ID'];
//$eid=1;

/* Getting 5 random unevaluated entries*/
$query = "select bugid from kurukshe_main.bugReport where evaluated=false and evalInProgress IS NULL order by rand() limit 5;";
$result = mysql_query($query);
	if( !$result) 
	{echo mysql_error();echo "downloadBugReport.php: Error retrieving filename, category from userUploadFileTable.";
	return;}
$count=0;
$html = "<table> <tr><th>FileName</tr>";
$tablerow="";

while($row = mysql_fetch_array($result))
{
if($count == 5) break;
$filename = wrapAnchorTag($row['bugid'],$row['bugid'],$row['bugid']);
//$category = $row['category'];
$tablerow= $tablerow . "<tr> <td> $filename </tr>  ";
}

$html = $html . $tablerow . "</table>";
echo "hi";
return $html;
}
?>


<html>
<head>
<script type="text/javascript" src="ajaxScripts.js"></script>
<script type="text/javascript" src="prototype.js"></script>
</head>
<body>
hi
<div id="tableDiv">
<?php echo getTable(); ?>
</div>
<div id="resultDiv">
</div>
</body>
</html>
