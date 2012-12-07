<?php
$relPath='../../pinc/';
include_once($relPath.'connect.inc');
include_once($relPath.'project_edit.inc');
include_once($relPath.'project_trans.inc');
include_once('projectmgr.inc');
new dbConnect();

$curr_state = get_enumerated_param($_GET, 'curr_state', null, $PROJECT_STATES_IN_ORDER);
$new_state  = get_enumerated_param($_GET, 'new_state', null, $PROJECT_STATES_IN_ORDER);
$projectids = explode( ',', $_GET['projects'] );
foreach($projectids as $projectid) {
    validate_projectID('projects', $projectid);
}

abort_if_not_manager();

echo "<pre>\n";

echo sprintf( _("Moving projects from '%1\$s' to '%2\$s'..."), $curr_state, $new_state);
echo "\n\n";

foreach( $projectids as $projectid )
{
    echo "\n";
    echo "$projectid ...\n";

    $result = user_can_edit_project($projectid);
    if ( $result == PROJECT_DOES_NOT_EXIST )
    {
        echo "    " . _("does not exist.") . "\n";
        continue;
    }
    else if ( $result == USER_CANNOT_EDIT_PROJECT )
    {
        echo "    " . _("You are not allowed to edit that project.") . "\n";
        continue;
    }

    $res = mysql_query("
        SELECT state, nameofwork
        FROM projects
        WHERE projectid='$projectid'
    ") or die(mysql_error());

    $project = mysql_fetch_assoc( $res );

    echo "    {$project['nameofwork']}\n";

    if ( $project['state'] != $curr_state )
    {
        echo "    " . sprintf( _("is no longer in %1\$s. Now in %2\$s."), $curr_state,  $project['state']) . "\n";
        continue;
    }

    $error_msg = project_transition( $projectid, $new_state, $pguser );
    if ( $error_msg )
    {
        echo "    $error_msg\n";
        continue;
    }

    echo "    " . _("successfully moved.") . "\n";
}

echo "</pre>\n";

echo "<hr>\n";
echo "<p>" . sprintf(_("Back to the <a href='%s'>project manager</a> page."), "projectmgr.php") . "</p>\n";
?>
