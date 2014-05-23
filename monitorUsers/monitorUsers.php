<?php 	

$root=realpath($_SERVER['DOCUMENT_ROOT']);
include("$root/global/headers.php");

$kid=$_SESSION['SESS_MEMBER_ID'];
//$kid=1;

$iframe='<iframe id="myIFrm" src="" style="visibility:hidden"></iframe>
<script type="text/javascript">
	 function tryToDownload(url)
	{

	oIFrm = document.getElementById(\'myIFrm\');
	oIFrm.src = url;
	}
	</script>';

echo "<h1>Users who have downloaded your code and uploaded some code recently</h1>";
$query="select * from kurukshe_main.downloadedWhom where hasUploadedRecentlyFilename IS NOT NULL and hasUsed=0 and sourceKid='$kid'; ";
$result=mysql_query($query);
if(!$result)
	die("monitorUsers.php: Error retrieving downloadedWhom table ".mysql_error());

$table = "<table cell-spacing='0' cell-padding='0'>";
	 	$tableHeading = "<tr>
	 			  <th>Who Downloaded Your Code</th>
	 			  <th>Code They've Uploaded</th>
	 			  <th>Complain Suspected Usage Of Your Code</th>
	 			  <th>Monitor The User Again</th>
	 			 </tr>";
	 $tablerows="";

	while($row = mysql_fetch_array($result))
	 {

	 	$destnKid=$row['destnKid'];
	 	$username=base64_url_decode($destnKid);
	 	//$username="sanath";
		$filename = base64_url_encode(strtok($row['hasUploadedRecentlyFilename'],".")).".tar";
		$sno=$row['sno'];
	 	$tablerows = $tablerows . "<tr>
	 			<td ><a href='#' onClick='getHisPage(\"$username\");'>$username</a></td>
	 			<td ><a href='#'onClick='downloadFile(\"$filename\");'> $filename</a></td>
	 			<td ><a href='#' onClick='complain(\"$sno\",\"$username\")'>Complain</a></td>
	 			<td ><a href='#' onClick='monitorOnceMore(\"$sno\")'>Monitor Once More</a></td>
	 			</tr>";	
	 }
	 
	$html = $table . $tableHeading. $tablerows . "</table>";
echo $html;

echo "<h1>Users who have used your code and submitted a code with credits to you</h1>";
$query="select * from kurukshe_main.downloadedWhom where hasUsed=1 and sourceKid='$kid'; ";
$result=mysql_query($query);
if(!$result)
	die("monitorUsers.php: Error retrieving downloadedWhom table ".mysql_error());

$table = "<table cell-spacing='0' cell-padding='0'>";
	 	$tableHeading = "<tr>
	 			  <th>Who Downloaded Your Code</th>
	 			  <th>Code They've Uploaded</th>
	 			  </tr>";
	 $tablerows="";

	while($row = mysql_fetch_array($result))
	 {

	 	$destnKid=$row['destnKid'];
	 	$username=base64_url_decode($destnKid);
	 	//$username="sanath";
		$filename = base64_url_encode(strtok($row['destnFilename'],".")).".tar";
	 	$tablerows = $tablerows . "<tr>
	 			<td ><a href='#' onClick='getHisPage(\"$username\");'>$username</a></td>
	 			<td ><a href='#'onClick='downloadFile(\"$filename\");'> $filename</a></td>
	 			</tr>";	
	 }
	 
	$html = $table . $tableHeading. $tablerows . "</table>";
echo $html;
echo "<br />";
echo "<h1>Users who have used your code and not yet made any submissions</h1>";
$query="select * from kurukshe_main.downloadedWhom where hasUploadedRecentlyFilename IS NULL and hasUsed=0 and sourceKid='$kid'; ";
$result=mysql_query($query);
if(!$result)
	die("monitorUsers.php: Error retrieving downloadedWhom table ".mysql_error());

$table = "<table cell-spacing='0' cell-padding='0'>";
	 	$tableHeading = "<tr>
	 			  <th>Who Downloaded Your Code</th>
	 			  </tr>";
	 $tablerows="";

	while($row = mysql_fetch_array($result))
	 {

	 	$destnKid=$row['destnKid'];
	 	$username=base64_url_decode($destnKid);
	 	//$username="sanath";
	 	$tablerows = $tablerows . "<tr >
	 			<td ><a href='#' onClick='getHisPage(\"$username\");'>$username</a></td>
	 			</tr>";	
	 }
	 
	$html = $table . $tableHeading. $tablerows . "</table>";
echo $html.$iframe;

?>
