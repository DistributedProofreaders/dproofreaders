<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'slim_header.inc');

require_login();

// (User clicked on "Start Proofreading" link or
// one of the links in "Done" or "In Progress" trays.)

$projectid      = get_projectID_param($_GET, 'projectid');
$expected_state = get_enumerated_param($_GET, 'proj_state', $PROJECT_STATES_IN_ORDER[0], $PROJECT_STATES_IN_ORDER);

$project = new Project($projectid);

// Check $expected_state.
if ($expected_state != $project->state)
{
    slim_header( $project->nameofwork );

    echo "<p>";
    echo sprintf(
         ('Warning: Project "%1$s" is no longer in state "%2$s"; it is now in state "%3$s".'),
        $project->nameofwork,
        project_states_text($expected_state),
        project_states_text($project->state)
    );
    echo "</p>\n";

    $expected_round = get_Round_for_project_state($expected_state);
    echo "<p>";
    echo sprintf(
        _('Back to <a href="%1$s">%2$s</a>'),
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
    echo pgettext("project state", "State")   . ": " . project_states_text($project->state) . "<br>\n";
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

$header_args = array(
    "js_files" => array(
        "$code_url/tools/proofers/dp_proof.js",
        "$code_url/tools/proofers/dp_scroll.js",
    )
);
slim_header_frameset($nameofwork." - "._("Proofreading Interface"), $header_args);

$frameGet="?" . $_SERVER['QUERY_STRING'];
?>
<frameset id="proof_frames" rows="*,85">
<frame name="proofframe" src="<?php echo "$code_url/tools/proofers/proof_frame.php{$frameGet}";?>" marginwidth="2" marginheight="2" frameborder="0">
<frame name="menuframe" src="ctrl_frame.php?projectid=<?php echo $projectid; ?>&amp;round_id=<?php echo $round->id; ?>" marginwidth="2" marginheight="2" frameborder="0">
</frameset>
<noframes>
<?php echo _("Your browser currently does not display frames!"); ?>
</noframes>
