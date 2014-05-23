<?php	
	$root=realpath($_SERVER['DOCUMENT_ROOT']);
	include("$root/global/connectDb.php");
	include("$root/global/error.php");
	include("$root/global/calcPoints.php");
	
	$language=$_POST['language'];
	$tablename="kurukshe_main.scoresTable_".$language;
	 $query = "SELECT * FROM $tablename order by totalScore desc,lastSubmissionTime asc;";//'$kid'";
	 $result = mysql_query($query);
	 if(!$result)
	 	die("displayScores.php: Error retrieving Scores.".mysql_error());
	 	$table = "<table cell-spacing='0' cell-padding='0'>";
	 	$tableHeading = "<tr>
	 			  <th>Rank</th>
	 			  <th>User Name</th>
	 			  <th>Total Score</th>
	 			  <th>Last Submission Category</th>
	 			  <th>Last Submission Score</th>
	 			  <th>Last Submission Time</th>
	 			 </tr>";
	 $tablerows="";
	 $rank=1;
	while($row = mysql_fetch_array($result))
	 {

	 	$kid=$row['kid'];
	 	/* Change this whenn integrating */
	 	$username = base64_url_decode($kid);
	 	//$username="sanath";
	 	/* 
	 	$query = "select username from kurukshe_kpp.members where username='$username'";
	 	$reslut = mysql_query($query);
	 	if(!$result)
	 		die("displayAllScores.php: Error seleccting username ".mysql_error());
	 	*/
	 	$marks = $row['totalScore'];
	 	$lastSubmissionCategory = $row['lastSubmissionCategory'];
	 	$lastSubmissionScore = $row['lastSubmissionMarks'];
	 	$lastSubmissionTime = $row['lastSubmissionTime'];
	 	$tablerows = $tablerows . "<tr>
	 			<td>$rank</td>
	 			<td>$username</td>
	 			<td>$marks</td>
	 			<td>$lastSubmissionCategory</td>
	 			<td>$lastSubmissionScore</td>
	 			<td>$lastSubmissionTime</td>
	 			</tr>";	
	 	$rank++;
	 }
	 
	$html = $table . $tableHeading. $tablerows . "</table>";
	
echo $html;


?>
