<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
include($relPath.'slim_header.inc');

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

$round = get_Round_for_project_state($project->state);

//load the master frameset
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<?php

// Add name of round before nameofwork
$rn = $round->id;
$nameofwork = "[" . $rn . "] " . $project->nameofwork;
slim_header($nameofwork." - "._("Proofreading Interface"),FALSE,FALSE);
$frameGet="?" . $_SERVER['QUERY_STRING'];
?>
<script language="JavaScript" type="text/javascript" src="dp_proof.js?1.33.1"></script>
<script language="JavaScript" type="text/javascript" src="dp_scroll.js"></script>
</head>
<frameset rows="*,73">
<frame name="proofframe" src="<?PHP echo "$code_url/tools/proofers/proof_frame.php{$frameGet}";?>" marginwidth="2" marginheight="2" frameborder="0">
<frame name="menuframe" src="ctrl_frame.php" marginwidth="2" marginheight="2" frameborder="0">
</frameset>
<noframes>
<? _("Your browser currently does not display frames!"); ?>
</noframes>
</html>
