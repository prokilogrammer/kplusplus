<?php
require('loginCheck.php');
	require('validate.php');
?>
<html>
<head>
<script type="text/javascript" src="mootools.js"></script>
<script type="text/javascript" src="ajaxScripts.js"></script>
</head>
<body>
Fill in the marks field as follows:<br>
ONLY FOR OPTIMIZATION provide the % of difference in runtime. For all others PLZ PROVIDE ONLY EITHER 0 OR 1 ACCORDING TO WHETHER HIS CODE IS NOT WORKING OR WORKING.<br>
<form action="submitMarksEval.php" method="post">
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
