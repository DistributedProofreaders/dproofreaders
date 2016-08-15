<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'slim_header.inc');

require_login();

// (User clicked on "Start Proofreading" link or
// one of the links in "Done" or "In Progress" trays.)

$projectid      = validate_projectID('projectid', @$_GET['projectid']);
$expected_state = @$_GET['proj_state'];

if (empty($expected_state)) die( "parameter 'proj_state' is empty" );

$project = new Project($projectid);

// Check $expected_state.
$expected_state_text = project_states_text($expected_state);
if (empty($expected_state_text)) die( "parameter 'proj_state' has bad value: '$expected_state'" );
if ($expected_state != $project->state)
{
    slim_header( $project->nameofwork );

    echo "<p>";
    echo sprintf(
         ('Warning: Project "%1$s" is no longer in state "%2$s"; it is now in state "%3$s".'),
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

    exit;
}

// Check that the project is in a proofable state
list($code,$msg) = $project->can_be_proofed_by_current_user();
if ( $code != $project->CBP_OKAY )
{
    // I think this can only happen via URL-tweaking.

    slim_header( $project->nameofwork );

    echo _("Project") . ": \"{$project->nameofwork}\"<br>\n";
    echo _("State")   . ": " . project_states_text($project->state) . "<br>\n";
    echo "<p>$msg</p>\n";

    echo "<p>";
    echo sprintf(
        _("Back to <a href='%s'>Activity Hub</a>"),
        "$code_url/activity_hub.php"
    );
    echo "</p>\n";

    exit;
}

//load the master frameset

// Add name of round before nameofwork
$round = get_Round_for_project_state($project->state);
$nameofwork = "[" . $round->id . "] " . $project->nameofwork;

// Re src="dp_foo.js?YYMMDD##" in the following <script> tags:
// When a JS script file changes, the browser should note this and update its
// cached version. However, it appears that some browsers are not very good
// at this, and continue to use a cached version after it is out of date.
// To thwart this, we add a query string to the src reference, and update it
// when the JS script file changes. (The query string can be anything, but
// it makes sense to use the date that the JS file was changed.)
// The browser sees that the src URL no longer matches that of its cached
// script, and so fetches the new version. (Of course, the JS script doesn't
// do anything with the query string, but the browser doesn't know that.)
$header_args = array(
    "js_files" => array(
        "dp_proof.js?2015122901",
        "dp_scroll.js?1.18",
    )
);
slim_header_frameset($nameofwork." - "._("Proofreading Interface"), $header_args);

$frameGet="?" . $_SERVER['QUERY_STRING'];
?>
<frameset id="proof_frames" rows="*,73">
<frame name="proofframe" src="<?php echo "$code_url/tools/proofers/proof_frame.php{$frameGet}";?>" marginwidth="2" marginheight="2" frameborder="0">
<frame name="menuframe" src="ctrl_frame.php?round_id=<?php echo $round->id; ?>" marginwidth="2" marginheight="2" frameborder="0">
</frameset>
<noframes>
<?php echo _("Your browser currently does not display frames!"); ?>
</noframes>
