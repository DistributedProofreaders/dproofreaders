<?PHP
$relPath="./../pinc/";
include($relPath.'v_site.inc');
include($relPath.'username.inc');
include($relPath.'connect.inc');
include($relPath.'user.inc');
include($relPath.'metarefresh.inc');
include($relPath.'theme.inc');

function abort_login( $error )
{
    global $reset_password_url;
    global $site_manager_email_addr;

    theme("Login Failed", "header");

    echo "<br>\n";
    echo "<b>$error</b><br>\n";
    echo "<br>\n";
    echo "Please hit Back & try again.<br>\n";
    echo "If the problem persists, you can try to <A HREF=\"$reset_password_url\">reset</A> your password.<br>\n";
    echo "If this fails, contact the <a href=\"mailto:$site_manager_email_addr\">site manager</a>.<br>\n";
    echo "<br>\n";
    echo "<a href=\"signin.php\">Back</A> to the sign in page.";

    theme("", "footer");
    exit();
}

extract($_POST);

$err = check_username($userNM);
if ($err != '')
{
    abort_login($err);
}

if ($userPW == '')
{
    abort_login( "You did not supply a password." );
}

// $userNM = str_replace("\'", "''", $userNM);

$userC=new db_udb();

$uC=$userC->checkLogin($userNM,$userPW);
if (!$uC)
{
    abort_login('Username or password is incorrect.');
}

$uP=$userC->getUserPrefs($userNM);
if (!$uP)
{
    abort_login('Username or password is incorrect.');
}

// The login is successful!

// send them to the correct page
// isn't this the same as the manager field in users?
//        $result = mysql_query("SELECT value FROM usersettings WHERE username = '$username' AND setting = 'manager'");
// needs to be included in user.inc, if not....
if ($userC->manager=='yes')
{
  $url = "../tools/project_manager/projectmgr.php";
}
else
{
  $url = "../tools/proofers/proof_per.php";
}
metarefresh(1,$url,"Login","");

?>
