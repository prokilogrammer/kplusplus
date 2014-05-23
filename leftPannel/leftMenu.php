<?php
	$root=realpath($_SERVER['DOCUMENT_ROOT']);
	require("$root/global/loginCheck.php");
echo '
<h1> What You Can Do </h1>
<ul id="leftmenu">
<li onClick="loadBugReport();">Report Bugs</li>
<li onClick="uploadCode();">Submit Code/Patches</li>
<li onClick="displayMyScores();">My Scores</li>
<li onClick="recentActivity();">Recent Acvitity</li>
<li onClick="pendingEvaluations();">Pending Evaluations</li>
<li onClick="monitorUsers();">Monitor Users</li>
</ul>

<h1> Quick Links </h1>
<ul id="leftmenu">
<li onClick="howToPlay()"> How To Play </li>
<li onClick="scoringSystem()"> Scoring System </li>
<li onClick="downloadBaseCode()"> Download Base Code </li>

</ul>

';


?>
