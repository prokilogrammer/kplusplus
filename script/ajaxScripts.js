function registerMe()
{
var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
if($('username').value=="" || $('password').value=="" || !pattern.test($('email').value))
{
alert("Registration Form Invalid !! Try Again after furnishing valid information");
return;
}
if($('password').value != $('repassword').value)
{
alert('Two passwords did not match');
return;
}

user=document.getElementById('username').value;
pass=document.getElementById('password').value;
repass=document.getElementById('repassword').value;
mail=document.getElementById('email').value;
ajaxObject('register/register.php','content',"username="+user+"&password="+pass+"&email="+mail+"&repassword="+repass);
}


function forgotPassword()
{
user = $('username').value;
ajaxObject('forgotPassword/forgotPassword.php','content','username='+user);
}

function forgotPasswordDisplay()
{
ajaxObject('forgotPassword/forgotPasswordDisplay.php','content');
}

function validateMail()
{
var mail=$('email').value;
var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
    if(pattern.test(mail)){
    		$('emailError').innerHTML="";         
    		return true;
    }else{   
		$('emailError').innerHTML="Invalid!!";
		return false;
    }

}

function register()
{
ajaxObject('register/register-display.php','content');
}

function problemStatement()
{
ajaxObject('docs/prob_stat.php','content');
}
function downloadBaseCode()
{
ajaxObject('docs/downloadBaseCode.php','content');
}
function reportError()
{
ajaxObject('docs/reportError.php','content');
}
function howToPlay()
{
ajaxObject('docs/howToPlay.php','content');
}
function aboutUs()
{
ajaxObject('docs/aboutUs.php','content');
}

function contactUs()
{
ajaxObject('docs/contactUs.php','content');
}

function goHome()
{
ajaxObject('docs/home.php','content');
}
function scoringSystem()
{
ajaxObject('docs/scoringSystem.php','content');
}

function monitorUsers()
{
ajaxObject('monitorUsers/monitorUsers.php','content');

}
function monitorOnceMore(sno)
{
ajaxObject('monitorUsers/monitorOnceMore.php','error',"sno="+sno);
}
function complain(sno,username)
{
ajaxObject('monitorUsers/complain.php','error',"sno="+sno+"&username="+username);
}

function downloadCode(filename,kid)
{

if(filename == "")
	alert("The item you're requesting cant be Downloaded :P");
else
{
ajaxObject('downloadCode/downloadCode.php','error',"filename="+filename+"&kid="+kid);
}
}

function downloadFile(filename)
{

tryToDownload("downloadFile.php?filename="+filename);

}

function uploadCode()
{
current='uploadCode()';
ajaxObject('uploadCode/index-upload.php','content');
}

function checkMd5()
{
document.getElementById('spinner').style.visibility='visible';
var filename = window.frames[0].document.getElementById('filename').value;
var md5=window.frames[0].document.getElementById('md5').value;
var category = window.frames[0].document.getElementById('category').value;
var language = (window.frames[0].document.getElementById("language").value).toLowerCase();

if(category=='1')
{
var bugId=window.frames[0].document.getElementById('bugId').value;
var req=new Ajax('bugPatch/upload.php',{method:'post',
			   	data:'filename='+filename+'&md5='+md5+'&category='+category+'&who='+who+'&whoFilename='+whoFilename+"&language="+language+"&bugId="+bugId,
			   	update: window.document.getElementById('content'),
			   	evalScripts: true}).request();

}
else
{
var who=window.frames[0].document.getElementById('who').value;
var whoFilename=window.frames[0].document.getElementById('whoFilename').value;

var req=new Ajax('uploadCode/upload.php',{method:'post',
			   	data:'filename='+filename+'&md5='+md5+'&category='+category+'&who='+who+'&whoFilename='+whoFilename+"&language="+language,
			   	update: window.document.getElementById('content'),
			   	evalScripts: true}).request();
}
document.getElementById('spinner').style.visibility='hidden';
}

function loadLogin()
{

var req = ajaxObjectLogin('login/load-login.php','container');

}

function loadLeftPannel()
{

var req = ajaxObject('leftPannel/loadLeftPannel.php','left');

}

function logout()
{
var req = ajaxObjectLogin('login/logout.php','container');


}

function loginMe()
{
var loginData = document.getElementById("log").value;
var passwd = document.getElementById("pwd").value;

var req = ajaxObjectLogin('login/login-exec.php','container',"login="+loginData+"&pwd="+passwd);

}

function loadBugReport()
{
current='loadBugReport()';
var req=ajaxObject('bugReport/bugReportDisplay.php','content');

}

function bugReport()
{
var language = (document.getElementById("language").value).toLowerCase();
var summaryData = escape(document.getElementById("summary").value);
var packagesData = escape(document.getElementById("packages").value);
var descriptionData = escape(document.getElementById("description1").value);

var req=ajaxObject('bugReport/bugReport.php','content',"summary="+summaryData+"&packages="+packagesData+"&language="+language+"&description="+descriptionData);
//req.send("summary="+summaryData+"&packages="+packagesData+"&description="+descriptionData);
}

function displayMyScores()
{
current="displayMyScores()";
getHisPage('null');
}

function displayAllScores()
{
current="displayAllScores()";
document.getElementById('content').innerHTML ="<table><th style=\"cursor: pointer;\" onClick='displayLangScores(\"c\")'>Scores for C Language Submissions</th><th onClick='displayLangScores(\"java\")' style=\"cursor: pointer;\">Scores for Java Language Submissions</th></table>";

}
function displayLangScores(language)
{

ajaxObjectTable('displayScores/displayAllScores.php','content','language='+language);

}

function pendingEvaluations()
{
current="pendingEvaluations()";
ajaxObjectTable('pendingEvaluations/pendingEvaluations.php','content');
}

function recentActivity()
{
current="recentActivity()";
ajaxObjectTable('recentActivity/recentActivity.php','content');
}

function getHisPage(username)
{
current='getHisPage()';
ajaxObjectTable('displayScores/displayMyScores.php','content','username='+username);
}

function ajaxObjectTable(link,division,params)
{
document.getElementById('spinner').style.visibility='visible';
new Ajax(link,{method:'post',
			   	update: $(division),
			   	data: params,
			   	evalScripts: true,
			   	onComplete: function(){tablecloth();hideSpinner();}
			   	}).request();
			   	
}

function ajaxObjectLogin(link,division,params)
{

var req = new Ajax(link,{method:'post',
			   	data: params,
			   	update: $(division),
			   	evalScripts: true,
			   	onComplete: function(){loadLeftPannel();goHome();}
			   	}).request();

return req;
}

function ajaxObject(link,division,params)
{
document.getElementById('spinner').style.visibility='visible';
var req = new Ajax(link,{method:'post',
			   	data: params,
			   	update: $(division),
			   	onComplete: function(){hideSpinner();},
			   	evalScripts: true}).request();


return req;
}

function hideSpinner()
{
document.getElementById('spinner').style.visibility='hidden';			   	
}
