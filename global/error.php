<?php

function customError($errno, $errstr,$errFile,$errLine,$errContext)
 { 
 echo "<b>Error:</b> [$errno] $errstr in file $errFile at Line $errLine <br />";
 echo "Ending Script";

 }
/* Since I'm not having die statement here, I must include my custom errors in die statement in code */ 
set_error_handler("customError");
?>
