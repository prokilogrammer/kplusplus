<?php

$root=realpath($_SERVER['DOCUMENT_ROOT']);
include("$root/global/headers.php");
$name = base64_url_decode(strtok($_GET['filename'],".")).".tar";
$path="$root/uploads/".$name;

if(file_exists($path))
{

// set the header values
header("Content-Type: application/force-download\n");
header("Content-Disposition: attachment; filename=".$name);

//set the value of the fields in Opened dailog box
header('Content-Disposition: attachment; filename="'.$name.'"');


//echo "<script type='text/javascript'>alert('$filename');</script>";
// echo the content to the client browser
$fp = fopen($path, 'rb');
fpassthru($fp);
}
else
{
echo "alert('File Not Found. Report to admin')";
}

?>
