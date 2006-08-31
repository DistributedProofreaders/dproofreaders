<?
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'Project.inc');
include_once('./post_files.inc');

$projectid = @$_REQUEST['projectid'];
$round_id  = @$_REQUEST['round_id'];
$which_text= @$_REQUEST['which_text'];
$include_proofers = @$_REQUEST['include_proofers'];
$save_files = @$_REQUEST['save_files'];

// only sitemanagers are allowed to save files
if ($save_files && !user_is_a_sitemanager())
{
    echo _('You are not authorized to invoke this script.');
    exit;
}

// only people who can see names on the page details page
// can see names here.
$project = new Project($projectid);

if ($include_proofers && ! $project->names_can_be_seen_by_current_user)
{
    echo _('You are not authorized to invoke this script.');
    exit;
}

// if we are not saving files, then we are just downloading the zip. 
// don't send anything out other than the headers and zip file contents.
if ($save_files) 
{
    echo "projectid       = '$projectid'<br>\n";
    echo "round_id        = '$round_id'<br>\n";
    echo "which_text      = '$which_text'<br>\n";
    echo "include_proofers= '$include_proofers'<br>\n";
    echo "save_files= '$save_files'<br>\n";
}

if ( empty($projectid) )
{
    die( "parameter 'projectid' is empty or unset" );
}

if ( empty($round_id) )
{
    die( "parameter 'round_id' is empty or unset" );
}

if ( empty($which_text) )
{
    die( "parameter 'which_text' is empty or unset" );
}

// don't check $include_proofers, as it is allowed to be '0' which counts
// as empty. 
// if it hasn't been set, it'll default to FALSE, which will be OK. 

// same for $save_files

if ($projectid == 'many' && !$save_files )
{
    die( "can only generate file for one project at a time on the fly" );
}

set_time_limit(0); // no time limit

if ( $projectid == 'many' )
{
    $project_condition = "
           state = '".PROJ_POST_FIRST_AVAILABLE."'
        OR state = '".PROJ_POST_FIRST_CHECKED_OUT."'
    ";
}
else
{
    $project_condition = "projectid='$projectid'";
}

$myresult = mysql_query("
    SELECT projectid, nameofwork FROM projects WHERE $project_condition
") or die(mysql_error());

if ( mysql_num_rows($myresult) == 0 )
{
    die( "no projects matched condition: $project_condition" );
}

while ($row = mysql_fetch_assoc($myresult)) 
{
    $projectid = $row['projectid'];
    $title = $row['nameofwork'];
    if ($save_files) 
    {
        echo "<p>generating files for $projectid ($title) ...</p>\n";
        flush();
        generate_post_files( $projectid, $round_id, $which_text, $include_proofers,  '' );
        flush();
    }
    else 
    {
        generate_interim_file( $projectid, $round_id, $which_text, $include_proofers);
    }
}

// vim: sw=4 ts=4 expandtab
?>
