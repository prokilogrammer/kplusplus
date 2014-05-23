<?php	include("global/headers.php");
    $md5name = $_POST['filename'];
    $language=$_POST['language'];
    if (!file_exists("uploads/" . $md5name))
      {
      echo "uploads/" . $md5name . " does not exists. ";
      return;
      }
      
	$md5=md5_file("uploads/" . $md5name);
	if($md5 != $_POST['md5']) 
	{
		if( unlink("uploads/" . $md5name)==false)
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
	if( unlink("uploads/" . $md5name)==false)
		   echo "The uploaded file not deleted";
	return;}

	/*Getting the submissionId */
	$query = "select submissionId from kurukshe_main.userUploadFileTable where timeOfUpload='$time'; ";
	$result = mysql_query($query);
	if( !$result) 
	{echo mysql_error();echo "upload.php: Error selecting submissionId";
	if( unlink("uploads/" . $md5name)==false)
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
	if( unlink("uploads/" . $md5name)==false)
		   echo "The uploaded file not deleted";
	$query="delete from kurukshe_main.userUploadFileTable where timeOfUpload='$time';";
	$result=mysql_query($query);
	if(!$result || mysql_affected_rows()==0)
		die("Error removing entries from userUploadFileTable: ".mysql_error());
	
	return;
	}

	
	/* Renaming the file */
	if( rename("uploads/" . $md5name,"uploads/".$filename) == false)
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
	 if( unlink("uploads/" .$filename)==false)
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
		echo "Mail Sent";
	
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
	 if( unlink("uploads/" .$filename)==false)
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
	if( unlink("uploads/" .$filename)==false)
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
	
	echo "<table><tr><th>Your submission was successful. Check the <a href=# onClick='pendingEvaluations();'>Pending Evaluations</a> link 
	and make sure your submission completed successfully. On any error please mail to kplusplus@kurukshetra.org.in</th></tr></table>";
	
	
?>


