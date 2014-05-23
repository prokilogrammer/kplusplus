function doggie(file,sid)
{

new Ajax.Updater('resultDiv','evalDownload.php',{
 evalScripts: true,
 method: 'post',
 parameters: {filename: file,submissionId: sid}
} );
alert("finished");
}

function doggieBugReport(file,sid)
{

new Ajax.Updater('resultDiv','evalBugReport.php',{
 evalScripts: true,
 method: 'post',
 parameters: {filename: file,submissionId: sid}
} );
//alert("finished");
}


function submitMarks()
{
var filenam = document.getElementById("filename").value;
var mark = document.getElementById("marks").value;
var comment = document.getElementById("comments").value;
new Ajax.Updater('container','submitMarks.php',{
 evalScripts: true,
 method: 'post',
 parameters: {filename: filenam,marks:mark,comments:comment }
} );
/*
var req = ajaxObject('submitMarks.php','container');
req.send("filename="+filename+"&marks="+marks+"&comments="+comments);
*/
}



