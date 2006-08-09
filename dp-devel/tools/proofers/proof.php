<?
$relPath="./../../pinc/";
include_once($relPath.'dp_main.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'slim_header.inc');

// (User clicked on "Start Proofreading" link or
// one of the links in "Done" or "In Progress" trays.)

$projectid      = @$_GET['projectid'];
$expected_state = @$_GET['proj_state'];

if (empty($projectid))      die( "parameter 'projectid' is empty" );
if (empty($expected_state)) die( "parameter 'proj_state' is empty" );

$project = new Project($projectid);

// Check $expected_state.
$expected_state_text = project_states_text($expected_state);
if (empty($expected_state_text)) die( "parameter 'proj_state' has bad value: '$expected_state'" );
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
list($code,$msg) = $project->can_be_proofed_by_current_user();
if ( $code != $project->CBP_OKAY )
{
    // I think this can only happen via URL-tweaking.

    slim_header( $project->nameofwork, TRUE, TRUE );

    echo "project: \"{$project->nameofwork}\"<br>\n";
    echo "state: ", project_states_text($project->state), "<br>\n";
    echo "<p>$msg</p>\n";

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
$round = get_Round_for_project_state($project->state);
$nameofwork = "[" . $round->id . "] " . $project->nameofwork;
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
<script language="JavaScript" type="text/javascript" src="dp_proof.js?1.49"></script>
<script language="JavaScript" type="text/javascript" src="dp_scroll.js?1.14"></script>
</head>
<frameset rows="*,73">
<frame name="proofframe" src="<?PHP echo "$code_url/tools/proofers/proof_frame.php{$frameGet}";?>" marginwidth="2" marginheight="2" frameborder="0">
<frame name="menuframe" src="ctrl_frame.php?round_id=<? echo $round->id; ?>" marginwidth="2" marginheight="2" frameborder="0">
</frameset>
<noframes>
<? _("Your browser currently does not display frames!"); ?>
</noframes>
</html>
