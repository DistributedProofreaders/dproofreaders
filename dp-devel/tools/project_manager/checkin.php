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
        print "File can not be checked back in due to the project not currently being available or available in a different state. Go <a href=\"project_detail.php?project=$project\">back</a>.";
	exit;
    }



    // if page is cleared, decrement user's page count

    $result = mysql_query("
	SELECT $user_field_name
	FROM $project
	WHERE fileid = '$fileid'
    ");
    
    $data = mysql_fetch_array($result);
    $proofer = $data[0];

    $sql = "UPDATE users SET pagescompleted = pagescompleted-1 WHERE username = '$proofer'";
    $result = mysql_query($sql);


    // also decrement the counts of their teams    

    $result = mysql_query("
	SELECT team_1, team_2, team_3
	FROM users
	WHERE username = '$proofer'
    ");
    
    $data = mysql_fetch_array($result);
    $team1 = $data['team_1'];
    $team2 = $data['team_2'];
    $team3 = $data['team_3'];
    

    $sql = "UPDATE user_teams SET page_count = page_count-1 WHERE id=1 OR id=$team1 OR id=$team2 OR id=$team3";
    $result = mysql_query($sql);



    // now clear the page


    (if $writeBIGtable) {
	    $result = mysql_query("
		UPDATE project_pages
		SET $text_field_name='',
		    $user_field_name='',
		    $time_field_name='',
		    state='$new_state'
		WHERE projectid = '$project' AND fileid = '$fileid'
    ");


    }

    $result = mysql_query("
	UPDATE $project
	SET $text_field_name='',
	    $user_field_name='',
	    $time_field_name='',
	    state='$new_state'
	WHERE fileid = '$fileid'
    ");



    metarefresh(0, "project_detail.php?project=$project", "Page Checked In ($round_number)", "");
?>

