<?php	$root=realpath($_SERVER['DOCUMENT_ROOT']);
include("$root/global/headers.php");

$dir=realpath($_SERVER['DOCUMENT_ROOT'])."/uploads/";

if( ($validate=valid_upload($_POST['md5'],$_POST['language'],$_POST['category'],'',$_POST['who'],$_POST['whoFilename']))!="")
{
unlink($dir. $_POST['filename']);
die("The following fields did not have proper values. Please correct them and resubmit the code.".$validate);
}
if($_POST['language']==1)
	$language='java';
else
	$language='c';

    $md5name = $_POST['filename'];

    //$dir='/home/dayanandasaraswati/K++/uploads/';
    if (!file_exists($dir . $md5name))
      {
      echo $dir . $md5name . " does not exists. ";
      return;
      }
      
	$md5=md5_file($dir . $md5name);
	if($md5 != $_POST['md5']) 
	{
		if( unlink($dir . $md5name)==false)
		   echo "The uploaded file not deleted";
		die("MD5 not matching. Plz regenerate your md5 and submit the code again");
		header('location: uploadCode/index.php');
	}
	
	/* FILE HAS BEEN UPLOADED AND SUCCESSFULLY TESTED FOR INTEGRITY */
	
	/*Now doing required database changes */
	$category = $_POST['category'];
	if($category == 0)
		die("Wrong Category");
	/* Checking if he's used other's code */
	$who = $_POST['who'];
	
	if( $who!=null && $who!='')
        {
        	/* He has used some one else's code */
        	/* Changing the category to imply usage of other's code */
        	$category +=20; 
        	$whoFilename = $_POST['whoFilename'];
        	
        }
	$kid = $_SESSION['SESS_MEMBER_ID'];
	//$kid=1;
	$time = time();
	$points = calcPoints();
	$query = "insert into kurukshe_main.userUploadFileTable(category,kid,points,timeOfUpload,language) values($category,'$kid',$points,'$time','$language')"; 
	$result = mysql_query($query);
	if( !$result) 
	{echo mysql_error();echo "Upload.php: Error inserting entry into table - userUploadFileTable.";
	if( unlink($dir . $md5name)==false)
		   echo "The uploaded file not deleted";
	return;}

	/*Getting the submissionId */
	$query = "select submissionId from kurukshe_main.userUploadFileTable where timeOfUpload='$time'; ";
	$result = mysql_query($query);
	if( !$result) 
	{echo mysql_error();echo "upload.php: Error selecting submissionId";
	if( unlink($dir . $md5name)==false)
		   echo "The uploaded file not deleted";
		   
	$query="delete from kurukshe_main.userUploadFileTable where timeOfUpload='$time';";
	$result=mysql_query($query);
	if(!$result || mysql_affected_rows()==0)
		die("Error removing entries from userUploadFileTable: ".mysql_error());
	
	return;
	
	
	}
	$row = mysql_fetch_array($result);
	$submissionId = $row['submissionId'];
	
	/* Stripping off the file extension */
	$temp = $md5name;
	$filetype = substr($md5name,strlen(strtok($temp,".")));
	
	$sidbid="s_".$submissionId;
	$filename = $kid . "_" . base64_url_encode($sidbid) .$filetype;
	$query = "update kurukshe_main.userUploadFileTable set filename='$filename' where timeOfUpload='$time'; "; 
	$result = mysql_query($query);
	if( !$result)
	 {echo mysql_error();echo "Upload.php: Error updating entry into table - userUploadFileTable.";
	if( unlink($dir . $md5name)==false)
		   echo "The uploaded file not deleted";
	$query="delete from kurukshe_main.userUploadFileTable where timeOfUpload='$time';";
	$result=mysql_query($query);
	if(!$result || mysql_affected_rows()==0)
		die("Error removing entries from userUploadFileTable: ".mysql_error());
	
	return;
	}

	
	/* Renaming the file */
	if( rename($dir . $md5name,$dir.$filename) == false)
	{
		echo "Error renaming to the new filename. Using the md5 as the filename in kid table";
		$filename=$md5name;
	}

	/* Now adding the entry in the kurukshe_userStatsDatabase kid table */
	$tablename = "kurukshe_userStatsDatabase.`kid_".$kid."`";
	$time="now()";
	$activity=$filename;
	$query = "insert into $tablename(sidbid,activity,category,timeOfUpload,points,language) values ('$sidbid','$activity',$category,$time,$points,'$language');"; 
	$result = mysql_query($query);
	if( !$result) 
	{echo mysql_error();echo "Upload.php: Error updating entry into table - userUploadFileTable.";
	 if( unlink($dir .$filename)==false)
		   echo "The uploaded file not deleted";
	$query="delete from kurukshe_main.userUploadFileTable where submissionId=$submissionId;";
	$result=mysql_query($query);
	if(!$result || mysql_affected_rows()==0)
		die("Error removing entries from userUploadFileTable: ".mysql_error());
		   
		   
	return;}
	
	
	/*He might have downloaded someother's code and might be uploading first time after that. So check it and update the downloadedWhom table*/
	$query = "update kurukshe_main.downloadedWhom set hasUploadedRecentlyFilename='$filename' where destnKid='$kid' and hasUploadedRecentlyFilename IS NULL;";
	$result=mysql_query($query);
	if(!$result)
	{
	echo mysql_error();echo "Upload.php: Error updating entry into table - downloadedWhom - hasUploadedRecentlyFilename.";
	
	$query="delete from $tablename where sidbid='$sidbid';";
	$result=mysql_query($query);
	if(!$result || mysql_affected_rows()==0)
		die("Error removing entries from kid table: ".mysql_error());
	
	return;
	}
	if(mysql_affected_rows()>0)
	{
		/* Send a mail to all the sourceKid of the above table informing recent upload*/
		$query="select sourceKid from kurukshe_main.downloadedWhom where destnKid='$kid' and hasUploadedRecentlyFilename IS NOT NULL;";
		$result=mysql_query($query);
		$row=mysql_fetch_array($result);
		$uname=base64_url_decode($row['sourceKid']);
		$query = "select email from kurukshe_kpp.members where username='$uname'";
		$result=mysql_query($query);
		//$row=mysql_fetch_array($result);
		/* Send Mail to admin*/ 
while( ($row=mysql_fetch_array($result)) )
{
$myUsername=base64_url_decode($_SESSION['SESS_MEMBER_ID']);
$to=$row['email'];
$subject="KPlusPlus: User -- $myUsername -- has uploaded a source code.";
$message="User -- $myUsername -- had uploaded a code. He has recently downloaded your source code and has now uploaded a new one without any credits. There's probability that he might have used your original code to develop this version which he's recently uploaded. Please visit the \"MONITOR USERS\" page at kplusplus.kurukshetra.org.in to monitor the activity of the user. You can also download the file which the he has uploaded and check whether your code has been used in it. If found so, then you have all the liberty to \"Complain\" about the user to us. To do so, click on the \"Complain\" button in the table in the \"Monitor Users\" section. 
           \n---------------------\n
           You are receiving this mail because you're playing kplusplus at Kurukshetra '09. For more information 
           about kplusplus log on to kplusplus.kurukshetra.org.in. \n
           To participate in other enthralling events visit www.kurukshetra.org.in
           \n---------------------\n";
sendMail($to,$subject,$message);
   echo "Mail Sent to $to with subject $subject and message $message <br>";
}


	
	}
	/* Updating entry to downloadedWhom table*/
	if($who!=null && $who!='')
	{
	/* sourceKid is the kid of the person who furnished the download code. I mean destnKid is the person who downloaded sourceKid's code*/
	$sourceKid=base64_url_encode($who);
	//$sourceKid=2;
	$destnKid =$kid;
	$sourceFilename = $whoFilename;
	$hasUsed=true;
	$destnFilename=$filename;
	$query = "update kurukshe_main.downloadedWhom set hasUsed=true,destnFilename='$destnFilename' 
			where sourceKid='$sourceKid' and destnKid='$destnKid' and sourceFilename='$sourceFilename' and hasUsed=false; ";
	$result=mysql_query($query);
	if(!$result)
	{
	echo mysql_error();echo "Upload.php: Error updating entry into table - downloadedWhom.";
	 if( unlink($dir .$filename)==false)
		   echo "The uploaded file not deleted";
		   
	$query="delete from kurukshe_main.userUploadFileTable where submissionId=$submissionId;";
	$result=mysql_query($query);
	if(!$result || mysql_affected_rows()==0)
		die("Error removing entries from userUploadFileTable: ".mysql_error());
	
	$query="delete from $tablename where sidbid='$sidbid';";
	$result=mysql_query($query);
	if(!$result || mysql_affected_rows()==0)
		die("Error removing entries from kid table: ".mysql_error());
	
	$query = "update kurukshe_main.downloadedWhom set hasUploadedRecentlyFilename=NULL where destnKid='$kid' and hasUploadedRecentlyFilename IS NOT NULL;";
	$result=mysql_query($query);
	if(!$result || mysql_affected_rows()==0)
		die("Error removing entries from downloadedWhom table: ".mysql_error());
	
	return;
	
	}
	if(mysql_affected_rows()==0)
	{
	
	/*Deleting all the table entries and  unlinking the file too IMPORTANT*/
	if( unlink($dir .$filename)==false)
		   echo "The uploaded file not deleted";
	$query = "delete from kurukshe_main.userUploadFileTable where filename='$filename'";
	$result=mysql_query($query);
	if(!$result || (mysql_affected_rows()==0))
		die("upload.php: Error deleting from main table ".mysql_error());
	
	$query = "delete from $tablename where sidbid='$sidbid'";
	$result=mysql_query($query);
	if(!$result || (mysql_affected_rows()==0))
		die("upload.php: Error deleting from kid table ".mysql_error());
	
	$query = "update kurukshe_main.downloadedWhom set hasUploadedRecentlyFilename=NULL where destnKid='$kid' and hasUploadedRecentlyFilename IS NOT NULL;";
	$result=mysql_query($query);
	if(!$result || mysql_affected_rows()==0)
		die("Error removing entries from downloadedWhom table: ".mysql_error());
	
	
	die("<h1>Your Submission did not complete as expected. This may be because.You've furnished the wrong username or wrong filename of the uploader. Please resubmit.</h1>");
		
	}
	
	}
	
	//chmod($dir.$filename,1274);
	echo "<table><tr><th>Your submission was successful. Check the <a href=# onClick='pendingEvaluations();'>Pending Evaluations</a> link 
	and make sure your submission completed successfully. On any error please mail to kplusplus@kurukshetra.org.in</th></tr></table>";
	


function valid_upload($md5,$lang,$cat,$bugid,$who,$whoFile)
{
$tmp="";
if($md5==NULL)
	$tmp=$tmp . "<br />* The MD5 field should not be left Blank!! * ";
if($lang!='0' && $lang!='1')
	$tmp=$tmp . "<br />* Wrong Language Selected!! * ";
if($cat=='0' || $cat>'6')
{
	$tmp=$tmp . "<br />* Category Invalid";
	//return $tmp;
}
if($cat==1 && $bugid==NULL)
	$tmp=$tmp . "<br />* The Bug ID field should not be left Blank!! * ";	
if($who==NULL && $whoFile!=NULL)
	$tmp= $tmp . "<br />* Username of person whose code you've downloaded MUST be specified!! * ";
if($who==NULL)
	$tmp= $tmp;
else
{
	if($whoFile==NULL)
		$tmp= $tmp . "<br />* The File Which you used from the user must be specified!! * ";

$flext=strtok($whoFile,".");
$flext=strtok("");
if( $flext=="tar" )
	$tmp= $tmp;
else
	$tmp=$tmp . "<br />* Only .tar files are allowed!! * ";
}
return $tmp;
}

	
?>

