<?PHP
$relPath="./../pinc/";
include($relPath.'html_main.inc');
$htmlC->startHeader("Login Form");
$htmlC->startBody(0,1,0,0);
$tb=$htmlC->startTable(0,0,0,1);
$tr=$htmlC->startTR(0,0,1);
$td1=$htmlC->startTD(2,0,2,0,"center",0,0,1);
$td2=$htmlC->startTD(1,0,0,0,"center",0,0,1);
$td3=$htmlC->startTD(0,0,0,0,"center",0,0,1);
$td4=$htmlC->startTD(1,0,2,0,"center",0,0,1);
$td5=$htmlC->startTD(0,0,2,0,"center",0,0,1);
$tde=$htmlC->closeTD(1);
$tre=$htmlC->closeTD(1).$htmlC->closeTR(1);

echo "<form action='login.php?destination={$_GET['destination']}' method='POST'>";
echo $tb;
echo $tr.$td1;
echo '<B>Sign in to Distributed Proofreaders</B>';
echo $tre.$tr.$td2;
echo '<STRONG>Username</STRONG>';
echo $tde.$td3;
echo '<INPUT TYPE="text" NAME="userNM" SIZE="12" MAXSIZE="50">';
echo $tre.$tr.$td2;
echo '<STRONG>Password</STRONG>';
echo $tde.$td3;
echo '<INPUT TYPE="password" NAME="userPW" SIZE="12" MAXSIZE="50">';
echo $tre.$tr.$td1;
echo '<INPUT TYPE="submit" VALUE="Auth me">';
echo $tre.$tr.$td5;
echo '<B>Note:</B>Username/Password are case sensitive.<BR>Make sure your caps lock is not on.';
echo $tre.$tr.$td4;
echo '<A HREF="addproofer.php"><B>New User?</B></A>';
echo $tre.$htmlC->closeTable(1)."</form>".$htmlC->closeBody(1);
?>
