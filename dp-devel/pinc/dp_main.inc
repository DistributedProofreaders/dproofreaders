<?PHP
include($relPath.'cookiecheck.inc');
include($relPath.'connect.inc');
$pguser=$cookieID;
$db_Connection=new dbConnect();
if ($db_Connection->error !='') {die('Error: Unable to connect to database.');}
$db_link=$db_Connection->db_lk;
include($relPath.'fmain.inc');

//Global Variables
var $admin_email='';
var $admin_name='';
?>