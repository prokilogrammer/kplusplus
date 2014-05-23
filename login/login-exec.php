<?php	
	$root=realpath($_SERVER['DOCUMENT_ROOT']);
	include("$root/global/connectDb.php");
	include("$root/global/calcPoints.php");
	//Start session
	session_start();
	

//header("location: load-failed.php");
//exit();

	//Connect to mysql server
	/* HERE I GOTTA CONNECT TO KURUKS LOGIN */
	/*
	$link=mysql_connect("mysql.kurukshetra.org.in","ext_event_admin","extpass");
	if(!$link) {
		die('Failed to connect to server: ' . mysql_error());
	}
	//Select database
	$db=mysql_select_db("kuruk_09_reg");
	if(!$db) {
		die("Unable to select K! database");
	}
	
	$link=mysql_connect("localhost","root","root123");
	if(!$link) {
		die('Failed to connect to server: ' . mysql_error());
	}
	//Select database
	$db=mysql_select_db("kurukshe_kpp");
	if(!$db) {
		die("Unable to select K! database");
	}*/
	
	//$ktable="kuruk_09_reg.k_login";
	$ktable="kurukshe_kpp.login";
	//Sanitize the value received from login field
	//to prevent SQL Injection
	if(!get_magic_quotes_gpc()) {
		$login=mysql_real_escape_string($_POST['login']);
	}else {
		$login=$_POST['login'];
	}
	
	if(!get_magic_quotes_gpc()) {
		$pwd=mysql_real_escape_string($_POST['pwd']);
	}else {
		$pwd=$_POST['pwd'];
	}
	
	
	//Create query
	$qry="SELECT * FROM $ktable WHERE username='$login' and password='$pwd' and validate is null";
	$result=mysql_query($qry);
	//Check whether the query was successful or not
	if($result) {
		if(mysql_num_rows($result)>0) {
			//Login Successful
			session_regenerate_id();
			$member=mysql_fetch_assoc($result);
			$_SESSION['SESS_MEMBER_ID']=base64_url_encode($member['username']);
			session_write_close();
			$kid = base64_url_encode($member['username']);
			
			/* CHECKING IF HE'S THERE IN MY LOCAL CACHE */
			$query="select * from kurukshe_kpp.members where username='$login';";
			$result=mysql_query($query);
			if(mysql_num_rows($result)==0)
			{
			//Start of onRegister
/* The sql queries dont work properly with only NUMBERS as its table name. So only I'm prefixing them with "kid_" */
/* COPY THE EMAIL ADDRESS FORM K_LOGIN TABLE TO OUR LOCAL MEMBERS TABLE. THIS CAN BE USEFUL TO SEND MAIL TO ALL */
$query = "select email from $ktable where username='$login' and password='$pwd'";
$result=mysql_query($query);
if(!$result)
	die("Unable to select email from K! login ".mysql_error());
$row=mysql_fetch_array($result);
if($row==NULL || $row==false)
	die("There were errors processing login. Please Login again");
$email=$row['email'];
$query="insert into kurukshe_kpp.members(username,email) values('$login','$email')";
$result=mysql_query($query);
if(mysql_affected_rows()==0 || !$result)
	die('Error inserting into members table: '.mysql_error());
$tablename="kurukshe_userStatsDatabase.`kid_" . $kid."`";
$query = "create table " . $tablename . "(sidbid varchar(10) NOT NULL, 
		 activity varchar(255) NOT NULL,
		 category int NOT NULL,
		 marks float default NULL,
		 timeOfUpload  datetime NOT NULL,
		 points int NOT NULL,
		 language varchar(10) NOT NULL,
		 PRIMARY KEY(sidbid) 
		 )ENGINE=innoDB;";
	
$result = mysql_query($query);
if( !$result) {
echo mysql_error();echo "<br>On Register Bug. Report Error: ";
$query="delete from kurukshe_kpp.members where username='$login'";
$result=mysql_query($query);
if(mysql_affected_rows()==0 || !$result)
	die('unable too delete from members table '.mysql_error());
return;}

$query = "insert into kurukshe_main.scoresTable_c(kid) values('$kid')";
$result = mysql_query($query);
if( mysql_affected_rows() ==0 || (!$result)) 
{
	$query="delete from kurukshe_kpp.members where username='$login'";
	$result=mysql_query($query);
	if(mysql_affected_rows()==0 || !$result)
		die('unable too delete from members table '.mysql_error());
	$query = "drop table $tablename;";
	if(!mysql_query($query))
		die("Error dropping kid table. Please report this to admin immediately ".mysql_error());
	die("Error inserting scoresTable_c row: ".mysql_error());

}

$query = "insert into kurukshe_main.scoresTable_java(kid) values('$kid')";
$result = mysql_query($query);
if( mysql_affected_rows() ==0 || (!$result)) 
{
	$query="delete from kurukshe_kpp.members where username='$login'";
	$result=mysql_query($query);
	if(mysql_affected_rows()==0 || !$result)
		die('unable too delete from members table '.mysql_error());
		
	$query = "drop table $tablename;";
	if(!mysql_query($query))
		die("Error dropping kid table. Please report this to admin immediately ".mysql_error());
	$query = "delete from kurukshe_main.scoresTable_c where kid='$kid';";
	if(!mysql_query($query) || mysql_affected_rows()==0)
		die("Error removing entry from scores_c table ".mysql_error());
	die("Error inserting scoresTable_java row");
}
	
$query = "insert into kurukshe_userStatsDatabase.lastLogin(kid) values('$kid')";
$result = mysql_query($query);
if( mysql_affected_rows() ==0 || (!$result)) 
{
	$error=mysql_error();
	$query="delete from kurukshe_kpp.members where username='$login'";
	$result=mysql_query($query);
	if(mysql_affected_rows()==0 || !$result)
		die('unable too delete from members table '.mysql_error());
		
	$query = "drop table $tablename;";
	if(!mysql_query($query))
		die("Error dropping kid table. Please report this to admin immediately ".mysql_error());
	$query = "delete from kurukshe_main.scoresTable_c where kid='$kid';";
	if(!mysql_query($query) || mysql_affected_rows()==0)
		die("Error removing entry from scores_c table ".mysql_error());
		
	$query = "delete from kurukshe_main.scoresTable_java where kid='$kid';";
	if(!mysql_query($query) || mysql_affected_rows()==0)
		die("Error removing entry from scores_java table ".mysql_error());
	die("Error inserting lastLogin row: ".$error);
}
			
			
	
			
			//End of onRegister
			}
			
			$query = "select count from kurukshe_userStatsDatabase.lastLogin where kid='$kid'";
			$result = mysql_query($query);
			if(!$result)
			{
			echo mysql_error();
			echo "  login-exec.php: Unable to select count";
			return;
			}
			$row = mysql_fetch_assoc($result);
			$count=$row['count'];
			$count++;
			$query = "update kurukshe_userStatsDatabase.lastLogin set count=$count, time=now() where kid='$kid'";
			$result = mysql_query($query);
			if(!$result)
			{
			echo mysql_error();
			echo "  login-exec.php: Unable to update count";
			return;
			}
			
			header("location: member-index.php");
			
		}else {
			//Login failed
			header("location: login-failed.php");
		}
	}else {
		die("Query failed");
	}



?>
