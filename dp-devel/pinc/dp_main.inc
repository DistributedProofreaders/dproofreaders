<?PHP
include($relPath.'fmain.inc');
include($relPath.'connect.inc');

//global db vars
$db_Connection=new dbConnect();
if ($db_Connection->error !='') {die('Error: Unable to connect to database.');}
$db_link=$db_Connection->db_lk;

include($relPath.'cookiecheck.inc');

//all $_GET to globals
if (!empty($_GET)) {extract($_GET);}

//all $_POST to globals
if (!empty($_POST)) {extract($_POST);}

//Global Variables
$admin_email='';
$admin_name='';
$pguser=$cookieID;
$userP=$cookieC->getUserPrefs($pguser);
?>