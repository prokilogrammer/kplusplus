<?php
require('global/loginCheck.php');
//******************************************************************************************************
//	Name: ubr_finished.php
//	Revision: 2.1
//	Date: 11:26 AM Saturday, September 20, 2008
//	Link: http://uber-uploader.sourceforge.net
//	Developer: Peter Schmandra
//	Description: Show successful file uploads.
//
//	BEGIN LICENSE BLOCK
//	The contents of this file are subject to the Mozilla Public License
//	Version 1.1 (the "License"); you may not use this file except in
//	compliance with the License. You may obtain a copy of the License
//	at http://www.mozilla.org/MPL/
//
//	Software distributed under the License is distributed on an "AS IS"
//	basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See
//	the License for the specific language governing rights and
//	limitations under the License.
//
//	Alternatively, the contents of this file may be used under the
//	terms of either the GNU General Public License Version 2 or later
//	(the "GPL"), or the GNU Lesser General Public License Version 2.1
//	or later (the "LGPL"), in which case the provisions of the GPL or
//	the LGPL are applicable instead of those above. If you wish to
//	allow use of your version of this file only under the terms of
//	either the GPL or the LGPL, and not to allow others to use your
//	version of this file under the terms of the MPL, indicate your
//	decision by deleting the provisions above and replace them with the
//	notice and other provisions required by the GPL or the LGPL. If you
//	do not delete the provisions above, a recipient may use your
//	version of this file under the terms of any one of the MPL, the GPL
//	or the LGPL.
//	END LICENSE BLOCK
//***************************************************************************************************************

//***************************************************************************************************************
// The following possible query string formats are assumed
//
// 1. ?upload_id=32_character_alpha_numeric_string
// 2. ?about
//****************************************************************************************************************

$THIS_VERSION = "2.1";                                // Version of this file
$UPLOAD_ID = '';                                      // Initialize upload id

require 'ubr_ini.php';
require 'ubr_lib.php';
require 'ubr_finished_lib.php';
//require 'ubr_image_lib.php';

if($PHP_ERROR_REPORTING){ error_reporting(E_ALL); }

header('Content-type: text/html; charset=UTF-8');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: '.date('r'));
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');

if(isset($_GET['upload_id']) && preg_match("/^[a-zA-Z0-9]{32}$/", $_GET['upload_id'])){ $UPLOAD_ID = $_GET['upload_id']; }
elseif(isset($_GET['about'])){ kak("<u><b>UBER UPLOADER FINISHED PAGE</b></u><br>UBER UPLOADER VERSION =  <b>" . $UBER_VERSION . "</b><br>UBR_FINISHED = <b>" . $THIS_VERSION . "<b><br>\n", 1 , __LINE__, $PATH_TO_CSS_FILE); }
else{ kak("<span class='ubrError'>ERROR</span'>: Invalid parameters passed<br>", 1, __LINE__, $PATH_TO_CSS_FILE); }

//Declare local values
$_XML_DATA = array();                                          // Array of xml data read from the upload_id.redirect file
$_CONFIG_DATA = array();                                       // Array of config data read from the $_XML_DATA array
$_POST_DATA = array();                                         // Array of posted data read from the $_XML_DATA array
$_FILE_DATA = array();                                         // Array of 'FileInfo' objects read from the $_XML_DATA array
$_FILE_DATA_TABLE = '';                                        // String used to store file info results nested between <tr> tags
$_FILE_DATA_EMAIL = '';                                        // String used to store file info results

$xml_parser = new XML_Parser;                                  // XML parser
$xml_parser->setXMLFile($TEMP_DIR, $_REQUEST['upload_id']);    // Set upload_id.redirect file
$xml_parser->setXMLFileDelete($DELETE_REDIRECT_FILE);          // Delete upload_id.redirect file when finished parsing
$xml_parser->parseFeed();                                      // Parse upload_id.redirect file

// Display message if the XML parser encountered an error
if($xml_parser->getError()){ kak($xml_parser->getErrorMsg(), 1, __LINE__, $PATH_TO_CSS_FILE); }

$_XML_DATA = $xml_parser->getXMLData();                        // Get xml data from the xml parser
$_CONFIG_DATA = getConfigData($_XML_DATA);                     // Get config data from the xml data
$_POST_DATA  = getPostData($_XML_DATA);                        // Get post data from the xml data
$_FILE_DATA = getFileData($_XML_DATA);                         // Get file data from the xml data


// Output XML DATA, CONFIG DATA, POST DATA, FILE DATA to screen and exit if DEBUG_ENABLED.
if($DEBUG_FINISHED){
	if($_CONFIG_DATA['embedded_upload_results'] == 1){ scriptParent(); }

	debug("<br><u>XML DATA</u>", $_XML_DATA);
	debug("<u>CONFIG DATA</u>", $_CONFIG_DATA);
	debug("<u>POST DATA</u>", $_POST_DATA);
	debug("<u>FILE DATA</u><br>", $_FILE_DATA);
	exit;
}


/////////////////////////////////////////////////////////////////////////////////////////////////
//
//           *** ATTENTION: ENTER YOUR CODE HERE !!! ***
//
//	This is a good place to put your post upload code. Like saving the
//	uploaded file information to your DB or doing some image
//	manipulation. etc. Everything you need is in the
//	$XML DATA, $_CONFIG_DATA, $_POST_DATA and $_FILE_DATA arrays.
//
/////////////////////////////////////////////////////////////////////////////////////////////////
//	NOTE: You can now access all XML values below this comment. eg.
//	$_XML_DATA['upload_dir']; or $_XML_DATA['link_to_upload'] etc
/////////////////////////////////////////////////////////////////////////////////////////////////
//	NOTE: You can now access all config values below this comment. eg.
//	$_CONFIG_DATA['upload_dir']; or $_CONFIG_DATA['link_to_upload'] etc
/////////////////////////////////////////////////////////////////////////////////////////////////
//	NOTE: You can now access all post values below this comment. eg.
//	$_POST_DATA['client_id']; or $_POST_DATA['check_box_1_'] etc
/////////////////////////////////////////////////////////////////////////////////////////////////
//	NOTE: You can now access all file (slot, name, size, type) info below this comment. eg.
//	$_FILE_DATA['upfile_0']->name  or  $_FILE_DATA['upfile_0']->getFileInfo('name')
/////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Create thumnail example.
//	createThumbFile(source_file_path, source_file_name, thumb_file_path, thumb_file_name, thumb_file_width, thumb_file_height)
//
//	EXAMPLE
//	$file_extension = getFileExtension($_FILE_DATA['upfile_0']->name);
//
//	if($file_extension == 'jpg' || $file_extension == 'jpeg' || $file_extension == 'png'){ $success = createThumbFile($_CONFIG_DATA['upload_dir'], $_FILE_DATA['upfile_0']->name, $_CONFIG_DATA['upload_dir'], 'thumb_' . $_FILE_DATA['upfile_0']->name, 120, 100); }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Create file upload table
$_FILE_DATA_TABLE = getFileDataTable($_FILE_DATA, $_CONFIG_DATA, $_POST_DATA);

// Create and send email
if($_CONFIG_DATA['send_email_on_upload']){ emailUploadResults($_FILE_DATA, $_CONFIG_DATA, $_POST_DATA); }

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<meta http-equiv="pragma" content="no-cache">
		<meta http-equiv="cache-control" content="no-cache">
		<meta http-equiv="expires" content="-1">
		<meta name="robots" content="none">
		<link rel="stylesheet" type="text/css" href="<?php print $PATH_TO_CSS_FILE; ?>">
		<script type="text/javascript">
		function loadBugId()
		{
		var data1 = '<table><tr><th><label for="bugId">BugID of your Bug Report*</label></th><td><input type="text" id="bugId" name="bugId" /></td></tr></table>';
		var data2='<table><tr><th><label for="who">Username of person whose code you\'ve downloaded(if any)</label></th><td><input type="text" id="who" name="who" /></td></tr><tr><th><label for="whoFilename">FileName of code which you have used(if any)</label></th><td><input type="text" id="whoFilename" name="whoFilename" /></td></tr></table>';
		var val=document.getElementById('category').value;
		if(val==1)
			{
			document.getElementById('newDivTr').innerHTML=data1;
			}
		else
			{
			document.getElementById('newDivTr').innerHTML=data2;			}
		}
		</script>
		<style type="text/css">
		h1{
		padding:6px; margin:0; border:0;
		background: gray;
		color: white;
		font-size:11px;
		font-weight:bold;
		border-top: 2px solid black;
		border-bottom:2px solid black;
		text-align: center;
		}
		  body
		  {
		  font-size: 12px;
		  font-family: Verdana, Arial, SunSans-Regular, Sans-Serif;
		  color:#564b47;  }
	</style>
	</head>
	<?php if($_CONFIG_DATA['embedded_upload_results'] == 1){ scriptParent(); } ?>
	<body class="ubrBody" onLoad="loadBugId();">
		<div class="ubrWrapper">
			<div align="center">
				<br>
				<h1>Your file has been successfully uploaded. You need to verify your file's integrity before your submission can complete.</h1>

				<?php print $_FILE_DATA_TABLE; ?>
			
				<table>
				<h1>Please furnish your MD5 Checksum here</h1>
				<?php if($_CONFIG_DATA['embedded_upload_results'] != 1){ ?>
				<tr><th><label for="md5"> MD5*</label></th><td><input type="text" name="md5" id="md5"/></td></tr>
				<tr><th>
				  <label for="language">Language(C/Java)*</label></th>
				  <td><SELECT id="language" name="language" value="0">
					<OPTION VALUE="0">C Language</OPTION>
					<OPTION VALUE="1">Java</OPTION>
				  	</SELECT></td></tr>
				 
			<tr><th>
				<label for="category"> Submission Category*  </label></th><td>
				<SELECT id="category" name="category" onChange="loadBugId();" >
					<OPTION VALUE="2">Optimization
					<OPTION VALUE="1" >Bug Patch
					<OPTION VALUE="3">Feature
					<OPTION VALUE="4">GUI
					<OPTION VALUE="5">Themes
					<OPTION VALUE="6">Language Packs
				  </SELECT>
				  </td></tr>
				  </table>
				 <div id="newDivTr"></div>
				  <br><input type="button" value="Complete My Submission" onClick="parent.checkMd5();" />
				<?php } ?>
			</div>
		</div>
		------------------Please do not refresh this page--------------
		<p><span class="blue">Fields marked * are mandatory</span></p>
		<p><span class="white">TROUBLESHOOT:</span>
		<br/>Q1. How to calculate md5?
		<br/>A1. Make use of command line tool called "md5sum"(in ubuntu). It must be available in your repository.
		
		</p>
	</body>
</html>