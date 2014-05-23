<?php	 $root=realpath($_SERVER['DOCUMENT_ROOT']);
	include("$root/global/headers.php");
	$username=$_POST['username'];
	 if( $username=='null')
	 {
		 $kid=$_SESSION['SESS_MEMBER_ID'];
		//echo "if";
		$flag=0;
		/* When flag=0 it means that i'm display the currently logged in user's page. This does not need to have <a> tags */
	}
	else
	{	
	//echo "else";
	$db=mysql_select_db("kurukshe_kpp");
	if(!$db) {
		die("Unable to select database");
	}
	$query = "select username from kurukshe_kpp.members where username='$username'";
	$result = mysql_query($query);
	 if(!$result)
	 	die("displayScores.php: Error retrieving kid.".mysql_error());
	$kid=base64_url_encode($username);
	$flag=1;
	}
	//$kid=1;
	$tabkid="kurukshe_userStatsDatabase.`kid_".$kid."`";
	 $query = "select * from $tabkid where marks is NOT NULL order by timeOfUpload;";//'$kid'";
	 $result = mysql_query($query);
	 if(!$result)
	 	die("displayScores.php: Error retrieving Scores.".mysql_error());
	 	$table = "<table cell-spacing='0' cell-padding='0'>";
	 	$tableHeading = "<tr>
	 			  <th>Category Of Submission</th>
	 			  <th>Marks</th>
	 			  <th>Language</th>
	 			  <th>Time of Submission</th>
	 			 </tr>";
	 $tablerows="";
	 
	while($row = mysql_fetch_array($result))
	 {

	 	$activity=$row['activity'];
	 	if($activity=="Bug Report" || $row['points']==0 || $row['category']==1)
		 	$filename = "";
		else 
			$filename = base64_url_encode(strtok($activity,".")).".tar";
		if(strtok($row['sidbid'],"_") == 'd')
			$categoryName=$activity;
		else
		 	$categoryName = getCatName($row['category'],$kid,$filename);
	 	$marks = $row['marks'];
	 	if($marks==NULL)
		 	$marks="Evaluation In Progress";
	 	$timeOfUpload = $row['timeOfUpload'];
	 	$language=$row['language'];
	 	if($flag==0)
	 	{
	 	$tablerows = $tablerows . "<tr>
	 			<td>$categoryName</td>
	 			<td>$marks</td>
	 			<td>$language</td>
	 			<td>$timeOfUpload</td>
	 			</tr>";	
	 	}
	 	else
	 	{
	 	$tablerows = $tablerows . "<tr>
	 			<td><a href='#' onClick=\"downloadCode('$filename','$kid')\">$categoryName</a></td>
	 			<td><a href='#' onClick=\"downloadCode('$filename','$kid')\">$marks</a></td>
	 			<td><a href='#' onClick=\"downloadCode('$filename','$kid')\">$language</a></td>
	 			<td><a href='#' onClick=\"downloadCode('$filename','$kid')\">$timeOfUpload</a></td>
	 			</tr>";	
	 	}
	 }
	 $iframe='';
	 if($flag==1)
		$iframe='<iframe id="myIFrm" src="" style="visibility:hidden"></iframe>
			 <script type="text/javascript">
			 function tryToDownload(url)
			{

			oIFrm = document.getElementById(\'myIFrm\');
			oIFrm.src = url;
			alert(url);
	
			}
			</script>
		 	';
	$html = $table . $tableHeading. $tablerows . "</table>".$iframe;
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
