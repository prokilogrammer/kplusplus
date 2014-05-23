<?php

$uname="kurukshe_root";
$passwd="root123";
if( !mysql_connect("localhost",$uname,$passwd)) 
	die("Failed to connect to database: " . mysql_error());

$query="create database kurukshe_main;";
if( !mysql_query($query))
	echo $query."<br>";
$query="create table kurukshe_main.userUploadFileTable
		(
		submissionId int auto_increment,
		category int NOT NULL,
		bugid varchar(10),
		filename varchar(255),
		kid varchar(255) NOT NULL,
		points int NOT NULL,
		evaluated boolean default false NOT NULL,
		evalInProgress varchar(255) default NULL,
		timeOfUpload varchar(255) NOT NULL,
		language varchar(10) NOT NULL,
		PRIMARY KEY (submissionId)
		)ENGINE=innoDB;";
if( !mysql_query($query))
	echo $query."<br>";
		
$query="create table kurukshe_main.bugReport
		(bugid int auto_increment,
		 kid varchar(255) NOT NULL,
		 evaluated boolean default false,
		 evalInProgress varchar(255) default NULL,
		 points int NOT NULL,
		 timeOfSubmission varchar(30) NOT NULL,
		 language varchar(10) NOT NULL,
		 PRIMARY KEY(bugid)
		 )ENGINE=innoDB;";
if( !mysql_query($query))
	echo $query."<br>";
		 

$query="create table kurukshe_main.scoresTable_java
 		(sno int auto_increment,
 		 kid varchar(255),
 		 totalScore float default 0,
 		 lastSubmissionCategory varchar(255) default 'N/A',
 		 lastSubmissionMarks float default 0,
 		 lastSubmissionTime datetime,
 		 PRIMARY KEY(sno)
 		 )ENGINE=innoDB;";
if( !mysql_query($query))
	echo $query."<br>";		

	$query="create table kurukshe_main.scoresTable_c
 		(sno int auto_increment,
 		 kid varchar(255),
 		 totalScore float default 0,
 		 lastSubmissionCategory varchar(255) default 'N/A',
 		 lastSubmissionMarks float default 0,
 		 lastSubmissionTime datetime,
 		 PRIMARY KEY(sno)
 		 )ENGINE=innoDB;";
if( !mysql_query($query))
	echo $query."<br>";	
 		 

$query="create table kurukshe_main.downloadedWhom
		(sno int auto_increment,
		 sourceKid varchar(255) NOT NULL,
		 destnKid varchar(255) NOT NULL,
		 sourceFilename varchar(255) not null ,
		 hasUsed boolean default false,
		 destnFilename varchar(255) default null,
		 hasUploadedRecentlyFilename varchar(255) default NULL,
		 PRIMARY KEY(sno)
		)ENGINE=innoDB;";
if( !mysql_query($query))
	echo $query."<br>";
		
$query="create database kurukshe_userStatsDatabase;";
if( !mysql_query($query))
	echo $query."<br>";

$query="create table kurukshe_userStatsDatabase.lastLogin
		(kid varchar(255) NOT NULL,
		 time datetime,
		 count int default 0,
		 PRIMARY KEY(kid)
		)ENGINE=innoDB;";
if( !mysql_query($query))
	echo $query."<br>";
		
$query="create database kurukshe_kpp;";
if( !mysql_query($query))
	echo $query."<br>";
	
	$query="create table kurukshe_kpp.members
		(username varchar(255) NOT NULL,
		 email varchar(255) NOT NULL
		 );	";
if( !mysql_query($query))
	echo $query."<br>";

$query = "create table kurukshe_kpp.login
		(username varchar(255) NOT NULL,
		 password varchar(255) NOT NULL,
		 email varchar(255) NOT NULL,
		 validate varchar(255) default NULL,
		 PRIMARY KEY(username)
		 );";
if( !mysql_query($query))
	echo $query."<br>";
	
$query="create table kurukshe_evaluator.login
		(username varchar(30),
		 password varchar(255),
		 PRIMARY KEY(username)
		 )ENGINE=innoDB;";
if(!mysql_query($query))
       echo $query;
	 		

?>

