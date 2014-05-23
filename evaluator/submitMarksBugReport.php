<?php require('loginCheck.php');
	require('validate.php');
?>
<html>
<head>
<script type="text/javascript" src="mootools.js"></script>
<script type="text/javascript" src="ajaxScripts.js"></script>
</head>
<body>
Fill in the marks field as follows:<br>PLEASE DO NOT GIVE ANY VALUE IN MARKS FIELD<br>
<form action="submitMarksEvalBugReport.php" method="post">
	<label for="filename">FileName</label>
	<input type="text" id="filename" name="filename" />
	<br />
	<br />
	<label for="marks">Marks</label>
	<input type="text" id="marks" name="marks" />
	<br />
	<br />
	<label for="comments">Comments</label>
	<textarea id="comments" name="comments" /></textarea>
	<br />
	<br />
	<input type="submit" id="submit" value="Submit" />
</form>

<div id="container"></div>
</body>
</html>
