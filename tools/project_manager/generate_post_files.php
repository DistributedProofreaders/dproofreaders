<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'misc.inc'); // get_integer_param(), get_enumerated_param()
include_once('./post_files.inc');

require_login();

$valid_round_ids = array_keys($Round_for_round_id_);
array_unshift($valid_round_ids, '[OCR]');

if (@$_REQUEST['projectid'] == 'many') {
    $projectid        = 'many';
} else {
    $projectid        = validate_projectID('projectid', @$_REQUEST['projectid']);
}
$round_id             = get_enumerated_param($_REQUEST, 'round_id', null, $valid_round_ids);
$which_text           = get_enumerated_param($_REQUEST, 'which_text', null, array('EQ', 'LE'));
$include_proofers     = get_integer_param($_REQUEST,'include_proofers',0,0,1);
$save_files           = get_integer_param($_REQUEST,'save_files',0,0,1);

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
