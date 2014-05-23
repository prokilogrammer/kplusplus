<?php 	$root=realpath($_SERVER['DOCUMENT_ROOT']);
	include("$root/global/connectDb.php");
	include("$root/global/error.php");
	require('validate.php');
	include('loginCheck.php');
function wrapAnchorTag($location,$text,$submissionId)
{

return "<A href='#' onClick='doggie(\"$location\",\"$submissionId\")'>$text</A>";

}

function getTable()
{
/* This code will retrieve 5 uploaded files randomly and display in table to kurukshe_evaluator */
$eid = $_SESSION['SESS_ID'];
//$eid=1;

/* Getting 5 random unevaluated entries*/
$query = "select submissionId,filename,category from kurukshe_main.userUploadFileTable where evaluated=false and evalInProgress IS NULL order by rand() limit 5;";
$result = mysql_query($query);
	if( !$result) 
	{echo mysql_error();echo "display.php: Error retrieving filename, category from userUploadFileTable.";
	return;}
$count=0;
$html = "<table> <tr><th>FileName <th>Category</tr>";
$tablerow="";

while($row = mysql_fetch_array($result))
{
if($count == 5) break;
$filename = wrapAnchorTag($row['filename'],$row['filename'],$row['submissionId']);
$category = $row['category'];
$tablerow= $tablerow . "<tr> <td> $filename <td> $category </tr>  ";
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
