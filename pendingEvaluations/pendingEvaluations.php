<?php	 $root=realpath($_SERVER['DOCUMENT_ROOT']);
include("$root/global/headers.php");

	$kid=$_SESSION['SESS_MEMBER_ID'];
	//$kid=1;
	$tabkid="kurukshe_userStatsDatabase.`kid_".$kid."`";
	 $query = "select * from $tabkid where marks is NULL order by timeOfUpload desc;";//'$kid'";
	 $result = mysql_query($query);
	 if(!$result)
	 	die("displayScores.php: Error retrieving Scores.".mysql_error());
	 	$table = "<table cell-spacing='0' cell-padding='0'>";
	 	$tableHeading = "<tr>
	 			  <th>Category Of Submission</th>
	 			  <th>Language</th>
	 			  <th>Time of Submission</th>
	 			 </tr>";
	 $tablerows="";
	 
	while($row = mysql_fetch_array($result))
	 {

	 	$activity=$row['activity'];
	 	if($activity=="Bug Report")
		 	$filename = "N/A";
		else 
			$filename = $activity;
		
	 	$categoryName = getCatName($row['category'],$kid,$filename);
	 	$timeOfUpload = $row['timeOfUpload'];
	 	$language=$row['language'];
	 	$tablerows = $tablerows . "<tr>
	 			<td>$categoryName</td>
	 			<td>$language</td>
	 			<td>$timeOfUpload</td>
	 			</tr>";	
	 }
	 
	$html = $table . $tableHeading. $tablerows . "</table>";
echo $html;


function getCatName($category,$kid,$filename)
{

if($category >20)
{	
	$query = "select sourceKid from kurukshe_main.downloadedWhom where destnKid='$kid' and hasUsed=1 and destnFilename='$filename';";
	$result=mysql_query($query);
	if(!$result)
		die("PendingEvaluations.php: Error finding sourceKid in getCatName routine ".mysql_error());
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
