<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'stages.inc');
include_once('./post_files.inc');

if (!user_is_a_sitemanager())
{
    echo _('You are not authorized to invoke this script.');
    exit;
}

$projectid = @$_REQUEST['projectid'];
$round_id  = @$_REQUEST['round_id'];

echo "projectid = '$projectid'<br>\n";
echo "round_id  = '$round_id'<br>\n";

if ( empty($projectid) )
{
    die( "parameter 'projectid' is empty or unset" );
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
    echo "<p>generating files for $projectid ($title) ...</p>\n";
    flush();
    generate_post_files( $projectid, $round_id );
    flush();
}

// vim: sw=4 ts=4 expandtab
?>
