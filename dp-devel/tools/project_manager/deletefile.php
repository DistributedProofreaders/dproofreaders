<?
$relPath="./../../pinc/";
include($relPath.'v_site.inc');
include($relPath.'metarefresh.inc');
include($relPath.'dp_main.inc');
include($relPath.'project_edit.inc');

    $project = $_GET['project'];
    $fileid = $_GET['fileid'];
    $confirm = $_GET['confirm'];
    
    abort_if_cant_edit_project( $project );

    if (!empty($confirm)) {
        if ($fileid == '') {
           $sql = "DELETE FROM $project WHERE 1";
        } else $sql = "DELETE FROM $project WHERE fileid = '$fileid'";
        mysql_query($sql);

        metarefresh(0, "project_detail.php?project=$project", "Page(s) Deleted", ""); 
    } else {
        echo "Confirm delete of $fileid <a href="deletefile.php?project=$project&fileid=$fileid&confirm=yes">here</a>";
    }
?>