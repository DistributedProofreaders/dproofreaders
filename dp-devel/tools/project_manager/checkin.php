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
        $result = mysql_query("UPDATE $project SET round1_text = '', round1_user = '', round1_time = '', state = '".AVAIL_FIRST."' WHERE fileid = '$fileid'");
        metarefresh(0, "projectmgr.php?project=$project", "Page Checked In (1)", "");

    } else if (($inRound=='SECOND') && ($pagestate == SAVE_SECOND)) {
        $result = mysql_query("UPDATE $project SET round2_text = '', round2_user = '', round2_time = '', state = '".AVAIL_SECOND."' WHERE fileid = '$fileid'");
        metarefresh(0, "projectmgr.php?project=$project", "Page Checked In (2)", "");

    } else {
        print "File can not be checked back in due to the project not currently being available or available in a different state. Go <a href=\"projectmgr.php?project=$project\">back</a>.";
    }
?>

