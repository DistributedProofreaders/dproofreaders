<?PHP
include_once($relPath.'site_vars.php');
include_once($relPath.'connect.inc');
include_once($relPath.'dpsession.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'maintenance_mode.inc');

//global db vars
$db_Connection=new dbConnect();
if ($db_Connection->error !='')
{
    die('Error: Unable to connect to database.');
}
$db_link=$db_Connection->db_lk;

// The next two extract()s simulates having register_globals enabled which
// is disabled by default in PHP 4.2 and later. The use of extract() on
// $_GET and $_POST is explicitly warned against in the PHP online manual.
// We need to strongly work at removing the dependency for such usage.

//all $_GET to globals
if (!empty($_GET))
{
    extract($_GET);
}

//all $_POST to globals
if (!empty($_POST))
{
    extract($_POST);
}

// Require that the sender be logged in.
$user_is_logged_in = dpsession_resume();
if ( !$user_is_logged_in  )
{
    // Remember the current REQUEST_URI, so that we can send the user there
    // after the login process.
    // (urlencode it because it might contain characters [specifically, &]
    // that would confuse parsing of the signin.php URL.)
    $request_uri_encoded = urlencode($_SERVER['REQUEST_URI']);
    $url="$code_url/accounts/signin.php?destination=$request_uri_encoded";
    $body="<A HREF=\"$url\">" . _("Please Sign In") . "</A>";
    $title = _("Please Sign In");
    metarefresh(0,$url,$title,$body);
    exit;
}

//Check to see if we are in a maintenance mode
abort_if_in_maintenance_mode();

// vim: sw=4 ts=4 expandtab
?>
