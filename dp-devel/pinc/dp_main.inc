<?PHP
include_once($relPath.'connect.inc');
include_once($relPath.'session.inc');
include_once($relPath.'cookiecheck.inc');

//global db vars
$db_Connection=new dbConnect();
if ($db_Connection->error !='') {die('Error: Unable to connect to database.');}
$db_link=$db_Connection->db_lk;

//all $_GET to globals
if (!empty($_GET)) {extract($_GET);}

//all $_POST to globals
if (!empty($_POST)) {extract($_POST);}

if($use_cookies) {
//Global Variables

$pguser=$cookieID;
if (isset($_COOKIE[$pguser.'_prefs'])){
$cookieC->deleteOldPrefs($pguser);
$cookieC->setUserPrefs($pguser);
echo 'Cookie reset by site update.  Please refresh current page.';
exit;}
$userP=$cookieC->getUserPrefs($pguser);
} else {
//Start the PHP session
startSession('');

//Check to see if the session is active or not
checkForActiveSession();

//all $_SESSION to globals
if (!empty($_SESSION)) {extract($_SESSION);};
}

//Check to see if we are in a maintenance mode
isMaintenanceMode();


function ptog($varNM)
{if (isset($_POST[$varNM]))
{return $_POST[$varNM];}
else {return NULL;}}

function gtog($varNM)
{if (isset($_GET[$varNM]))
{return $_GET[$varNM];}
else {return NULL;}}

?>
