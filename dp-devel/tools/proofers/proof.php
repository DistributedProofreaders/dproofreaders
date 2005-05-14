<?
$relPath="./../../pinc/";
include_once($relPath.'dp_main.inc');
include_once($relPath.'slim_header.inc');

// (User clicked on "Start Proofreading" link or
// one of the links in "Done" or "In Progress" trays.)

$projectid      = @$_GET['project'];
$expected_state = @$_GET['proofstate'];

if (empty($projectid))      die( "parameter 'project' is empty" );
if (empty($expected_state)) die( "parameter 'proofstate' is empty" );

$project = mysql_fetch_object(mysql_query("
    SELECT nameofwork, state FROM projects WHERE projectid = '$projectid';
"));
if (!$project) die( "no project with projectid='$projectid'" );

// Check $expected_state.
$expected_state_text = project_states_text($expected_state);
if (empty($expected_state_text)) die( "parameter 'proofstate' has bad value: '$expected_state'" );
if ($expected_state != $project->state)
{
    slim_header( $project->nameofwork, TRUE, TRUE );

    echo "<p>";
    echo sprintf(
        _('Warning: The project "%s" is no longer in state "%s"; it is now in state "%s".'),
        $project->nameofwork,
        $expected_state_text,
        project_states_text($project->state)
    );
    echo "</p>\n";

    $expected_round = get_Round_for_project_state($expected_state);
    echo "<p>";
    echo sprintf(
        _('Back to <a href="%s">%s</a>'),
        "$code_url/tools/proofers/round.php?round_id={$expected_round->id}",
        $expected_round->name
    );
    echo "</p>\n";

    return;
}

// Check that the project is in a proofable state
$round = get_Round_for_project_state($project->state);
if ( is_null($round) || $project->state != $round->project_available_state )
{
    // I think this can only happen via URL-tweaking.

    slim_header( $project->nameofwork, TRUE, TRUE );

    echo "<p>";
    echo sprintf(
        _('The project "%s" is in state "%s", so users are not allowed to proof it.'),
        $project->nameofwork,
        project_states_text($project->state)
    );
    echo "</p>\n";

    echo "<p>";
    echo sprintf(
        _('Back to <a href="%s">%s</a>'),
        "$code_url/activity_hub.php",
        _('Activity Hub')
    );
    echo "</p>\n";

    return;
}

// Check user's access to the project's current round
$uao = $round->user_access($pguser);
if (!$uao->can_access)
{
    slim_header( $project->nameofwork, TRUE, TRUE );

    echo "<p>";
    echo sprintf(
        _('Error: The project "%s" is in "%s", and you are not yet allowed to work in that round.'),
        $project->nameofwork,
        $round->name
    );
    echo "</p>\n";

    echo "<p>";
    echo sprintf(
        _('Back to <a href="%s">%s</a>'),
        "$code_url/activity_hub.php",
        _('Activity Hub')
    );
    echo "</p>\n";

    return;
}


//load the master frameset
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<?php

// Add name of round before nameofwork
$rn = $round->id;
$nameofwork = "[" . $rn . "] " . $project->nameofwork;
slim_header($nameofwork." - "._("Proofreading Interface"),FALSE,FALSE);
$frameGet="?" . $_SERVER['QUERY_STRING'];

// Re src="dp_foo.js?1.xx" in the following <script> tags:
// When a JS script file changes, the browser should note this and update its
// cached version. However, it appears that some browsers are not very good
// at this, and continue to use a cached version after it is out of date.
// To thwart this, we add a query string to the src reference, and update it
// when the JS script file changes. (The query string can be anything, but
// it makes sense to just use the CVS rev number. An alternatve would be date.)
// The browser sees that the src URL no longer matches that of its cached
// script, and so fetches the new version. (Of course, the JS script doesn't
// do anything with the query string, but the browser doesn't know that.)
?>
<script language="JavaScript" type="text/javascript" src="dp_proof.js?1.34"></script>
<script language="JavaScript" type="text/javascript" src="dp_scroll.js?1.14"></script>
</head>
<frameset rows="*,73">
<frame name="proofframe" src="<?PHP echo "$code_url/tools/proofers/proof_frame.php{$frameGet}";?>" marginwidth="2" marginheight="2" frameborder="0">
<frame name="menuframe" src="ctrl_frame.php" marginwidth="2" marginheight="2" frameborder="0">
</frameset>
<noframes>
<? _("Your browser currently does not display frames!"); ?>
</noframes>
</html>
