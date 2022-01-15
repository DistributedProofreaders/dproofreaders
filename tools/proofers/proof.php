<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'abort.inc');

require_login();

// (User clicked on "Start Proofreading" link or
// one of the links in "Done" or "In Progress" trays.)

try {
    $projectid = get_projectID_param($_GET, 'projectid');
} catch (Exception $exception) {
    abort($exception->getMessage());
}

$expected_state = get_enumerated_param($_GET, 'proj_state', null, $PROJECT_STATES_IN_ORDER, true);

$project = new Project($projectid);

// Check $expected_state.
if (!$expected_state) {
    abort(_("No expected state found in request."));
} elseif ($expected_state != $project->state) {
    slim_header($project->nameofwork);

    echo "<p>";
    echo sprintf(
         ('Warning: Project "%1$s" is no longer in state "%2$s"; it is now in state "%3$s".'),
        $project->nameofwork,
        project_states_text($expected_state),
        project_states_text($project->state)
    );
    echo "</p>\n";

    provide_escape_links();
    exit;
}

// Check that the project is in a proofable state
[$code, $msg] = $project->can_be_proofed_by_current_user();
if ($code != $project->CBP_OKAY) {
    abort($msg);
}

//load the master frameset

// Add name of round before nameofwork
$round = get_Round_for_project_state($project->state);
$nameofwork = "[" . $round->id . "] " . $project->nameofwork;

$header_args = [
    "js_files" => [
        "$code_url/tools/proofers/dp_proof.js",
        "$code_url/tools/proofers/dp_scroll.js",
    ],
];
slim_header_frameset($nameofwork." - "._("Proofreading Interface"), $header_args);

$frameGet = "?" . $_SERVER['QUERY_STRING'];
?>
<frameset id="proof_frames" rows="*,85">
<frame name="proofframe" src="<?php echo "$code_url/tools/proofers/proof_frame.php{$frameGet}"; ?>" marginwidth="2" marginheight="2" frameborder="0">
<frame name="menuframe" src="ctrl_frame.php?projectid=<?php echo $projectid; ?>&amp;round_id=<?php echo $round->id; ?>" marginwidth="2" marginheight="2" frameborder="0">
</frameset>
<noframes>
<?php echo _("Your browser currently does not display frames!"); ?>
</noframes>
