<?PHP
$relPath="./../pinc/";
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');

$db_Connection=new dbConnect();

$signin = _("Sign In");
theme($signin, "header");

echo _("ID and Password are case sensitive.<BR>Make sure your caps lock is not on.");

theme("", "footer");
?>
