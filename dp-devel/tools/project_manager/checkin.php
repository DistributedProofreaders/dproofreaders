<?
$relPath="./../../pinc/";
include($relPath.'v_site.inc');
include($relPath.'metarefresh.inc');
include($relPath.'dp_main.inc');
include_once($relPath.'page_states.inc');
include_once($relPath.'f_project_states.inc');
include($relPath.'project_edit.inc');

    $project = $_GET['project'];
    $fileid = $_GET['fileid'];
    $pagestate = $_GET['state'];

    abort_if_cant_edit_project( $project );

    $sql = mysql_query("SELECT state FROM projects WHERE projectid = '$project'");
    $projstate = mysql_result($sql, 0, "state");

    $inRound=projectStateRound($projstate);

    if (($inRound=='NEW' || $inRound=='PR' || $inRound=='FIRST') && ($pagestate == SAVE_FIRST)) {
	$round_number = 1;
	$text_field_name = 'round1_text';
	$user_field_name = 'round1_user';
	$time_field_name = 'round1_time';
	$new_state = AVAIL_FIRST;

    } else if (($inRound=='SECOND') && ($pagestate == SAVE_SECOND)) {
	$round_number = 2;
	$text_field_name = 'round2_text';
	$user_field_name = 'round2_user';
	$time_field_name = 'round2_time';
	$new_state = AVAIL_SECOND;

    } else {
        print "File can not be checked back in due to the project not currently being available or available in a different state. Go <a href=\"projectmgr.php?project=$project\">back</a>.";
	exit;
    }

    $result = mysql_query("
	UPDATE $project
	SET $text_field_name='',
	    $user_field_name='',
	    $time_field_name='',
	    state='$new_state'
	WHERE fileid = '$fileid'
    ");
    metarefresh(0, "projectmgr.php?project=$project", "Page Checked In ($round_number)", "");
?>

