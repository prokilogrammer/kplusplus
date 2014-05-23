KPlusPlus is the website of a software development contest that mimics the process used in the open source world. The folder $ROOT/baseCode has the code for a music player with some some bugs. Participants of this contest have to download this code, fix bugs and/or add new features and upload it. Points will be awarded based on the number of fixes, complexity of the fix, usefulness of new feature etc. This contest was active for around two months. As far as I remember, around 500 users participated in it. 

Live site (databases not setup. so not fully operational): http://kplusplus.herokuapp.com/

Here is a walk-through of the code:

$ROOT/baseCode - C and Java code for a simple music player. I wrote the C code while someone else wrote the Java code. 
$ROOT/baseCode/MuziK-C - Simple ogg player written in C to work Linux. This has few obvious bugs that users need to report and fix.

$ROOT/bugPatch - Uploads patches for bug fixes and stores them in a folder. Updates all tables reflecting the presence of this fix

$ROOT/bugReport - Report bugs in the base code. Also generates UI to display all submitted bug reports
$ROOT/bugReport/bugReports - Some sample bug reports uploaded by real users. Data from this file is parsed and displayed to user

$ROOT/displayScores - Generate tables that displays scores users

$ROOT/evaluator - A console for admins to evaluate user submissions and submit scores. Administrators were separate from regular users. They had thier own login and special powers :) 

$ROOT/global - Scripts used by other php code

$ROOT/login - All scripts for login

$ROOT/monitorUsers - Lets a user check if someone has downloaded one of their patchsets and submitted it back to K++ without credits to the original author

$ROOT/pendingEvaluations - Displays all evaluations that are pending for a particular user

$ROOT/recentActivity - Displays recent activity of users

$ROOT/register - Code to signup for this K++

$ROOT/uploadCode - Code to let user upload a file. Actual upload was taken care of a 3rd party library
$ROOT/uploads - Some sample patches uploaded by real users.
